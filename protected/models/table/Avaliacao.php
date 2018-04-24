<?php
/********************************************************************************
*  Copyright 2015 Conab - Companhia Nacional de Abastecimento                   *
*                                                                               *
*  Este arquivo � parte do Sistema SIAUDI.                                      *
*                                                                               *
*  SIAUDI  � um software livre; voc� pode redistribui-lo e/ou                   *
*  modific�-lo sob os termos da Licen�a P�blica Geral GNU conforme              *
*  publicada pela Free Software Foundation; tanto a vers�o 2 da                 *
*  Licen�a, como (a seu crit�rio) qualquer vers�o posterior.                    *
*                                                                               *
*  SIAUDI � distribu�do na expectativa de que seja �til,                        *
*  por�m, SEM NENHUMA GARANTIA; nem mesmo a garantia impl�cita                  *
*  de COMERCIABILIDADE OU ADEQUA��O A UMA FINALIDADE ESPEC�FICA.                *
*  Consulte a Licen�a P�blica Geral do GNU para mais detalhes em portugu�s:     *
*  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt                     *
*                                                                               *
*  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral do GNU             *
*  junto com este programa; se n�o, escreva para a Free Software                *
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    *
*                                                                               *
*  Sistema   : SIAUDI - Sistema de Auditoria Interna                            *
*  Data      : 05/2015                                                          *
*                                                                               *
********************************************************************************/
?>
<?php

Yii::import('application.models.table._base.BaseAvaliacao');

class Avaliacao extends BaseAvaliacao
{
    public $observacao,$nota, $valor_exercicio;
    
    public static function model($className=__CLASS__) {
            return parent::model($className);
    }

    // Verifica se o auditado (unidade administrativa ou lota��o) tem algum usuario para avaliar 
    // (de acordo com os usuarioes envolvidos no relat�rio). Caso haja, retorna o ID 
    // do usuario. Caso n�o haja, retorna null (e segue o fluxo para a manifesta��o do auditado
    // ou visualiza��o do relat�rio).
    // Par�metros de entrada: relatorio (id do relat�rio), sureg (id da sureg)
    public function VerificaAvaliacao($relatorio,$sureg){
        // verifica se relat�rio � do ano de 2014 ou maior 
        // (avalia��o n�o deve ser feita para anos anteriores)
        $Relatorio_completo = Relatorio::model()->findByPk($relatorio);
        $data_gravacao = explode("/",$Relatorio_completo->data_gravacao);
        $ano_gravacao = $data_gravacao[2];
        // pega os auditores do relat�rio            
        $relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk'=>$relatorio));
        foreach ($relatorio_auditor as $vetor){
            // para cada auditor, verifica se existe avalia��o
            $avaliacao = $this->findByAttributes(array('relatorio_fk'=> $relatorio,
                                                       'unidade_administrativa_fk'    => $sureg,
                                                       'usuario_fk'  => $vetor->usuario_fk));
            // se n�o existe avalia��o para este auditor ent�o retorna o ID do auditor
            if (sizeof($avaliacao)==0){
                return $vetor->usuario_fk;
            }
        }    
    }
    
    // Recebe o ID da avalia��o previamente salva e insere
    // as respectivas notas na tabela tb_avaliacao_nota
    // Par�metros de entrada: avaliacao (id da avalia��o) e
    // notas (array com os IDs dos crit�rios e respectivas notas)
    public function SalvaNotas($avaliacao,$notas){
        $schema = Yii::app()->params['schema'];        
        foreach ($notas as $key => $value) {
            $sql_values.="(".$avaliacao.",".$key.",".$value."),";
        }
        $sql_values = substr($sql_values,0,-1);
        
        $sql = "INSERT INTO " . $schema . ".tb_avaliacao_nota
                (avaliacao_fk, avaliacao_criterio_fk, nota) 
                VALUES ".$sql_values." ;";
        $command = Yii::app()->db->createCommand($sql);
        $command->execute();        
    }
    

    
    // Recebe o ID da avalia��o previamente salva e insere
    // a observacao na tabela tb_avaliacao_observacao
    // Par�metros de entrada: avaliacao (id da avalia��o) e
    // observacao
    public function SalvaObservacao($avaliacao,$observacao){
        if ($observacao){
            $observacao = str_replace("'",'"',$observacao);
            $schema = Yii::app()->params['schema'];        
            $sql = "INSERT INTO " . $schema . ".tb_avaliacao_observacao
                    (avaliacao_fk, ds_observacao) 
                    VALUES (".$avaliacao.",'".$observacao."'); ";
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();        
        }
    }    
    
    
    /* verifica se o usu�rio � do tipo "cliente" (auditado),
     * e, neste caso, se as avalia��es dos auditores foram preenchidas.
     * Em caso positivo, abre o relat�rio em PDF. Em caso negativo,
     * abre as avalia��o para ser preenchida.
     * 
     * @Relatorio (objeto):  Objeto da classe Relatorio().
     */
    public function VerificaAvaliacaoRelatorio($Relatorio){
        $perfil = strtolower(Yii::app()->user->role);  
        $perfil = str_replace("siaudi2","siaudi",$perfil);

        $login= Yii::app()->user->login;
        $RelatorioAcesso=  RelatorioAcesso::model()->findByAttributes(array('relatorio_fk'=>$Relatorio->id,'nome_login'=>$login));
        $sureg = $RelatorioAcesso->unidade_administrativa_fk;        
        
        //Se for sureg secundaria o cliente n�o avalia o auditor
        $relatorio_sureg = RelatorioSureg::model()->findByAttributes(array('relatorio_fk'=>$Relatorio->id,'unidade_administrativa_fk'=>$sureg));
        
        if (!$relatorio_sureg->sureg_secundaria){
	        if ($Relatorio->data_relatorio && $perfil=="siaudi_cliente"){
	            // Verifica se existe alguma avalia��o de auditor
	            // a ser feita antes de realizar a manifesta��o
	              $avaliacao_auditor = Avaliacao::model()->VerificaAvaliacao($Relatorio->id,$sureg);
	              // vari�vel $avaliacao_auditor cont�m o ID do auditor a 
	              // ser avaliado ou vazio caso todas as avalia��es j� tenham
	              // sido realizadas
	              if ($avaliacao_auditor) {
	                  $confirma = ($_GET["confirma"]) ? "&confirma=1":"";
	                  header("Location:../../Avaliacao/admin/?relatorio={$Relatorio->id}&sureg={$sureg}&auditor={$avaliacao_auditor}{$confirma}&visualizar_pdf=1");
	                  exit; 
	              }             
	            
	        }
        }
    }
    
    
    /* Recuperar Avali��o do Auditor 
     * -------------
     * 1 - Obter a avalia��o de um determinado auditor em um �nico exerc�cio. 
     * -------------
     * @exerc�cio (int): ano do exerc�cio. 
     * @usuario_fk (int): identifica��o (id) do auditor na base de dados.
     */
    public function RecuperaAvaliacaoAuditor($exercicio, $usuario_fk) {
        $schema = Yii::app()->params['schema'];       
        $sql = "select usuario.nome_usuario, avaliacao_criterio.numero_questao,  
                       avaliacao_criterio.descricao_questao, avg(avaliacao_nota.nota) as nota_media 
                  from ". $schema .".tb_avaliacao as avaliacao 
                 inner join ". $schema .".tb_usuario as usuario on 
                    usuario.id = avaliacao.usuario_fk 
                 inner join ". $schema .".tb_avaliacao_nota as avaliacao_nota on  
                    avaliacao_nota.avaliacao_fk = avaliacao.id 
                 inner join ". $schema .".tb_avaliacao_criterio as avaliacao_criterio on 
                    avaliacao_criterio.id = avaliacao_nota.avaliacao_criterio_fk 
                 where avaliacao.usuario_fk = ". $usuario_fk ." 
                   and date_part('year',avaliacao.data) = '". $exercicio ."' 
                 group by usuario.nome_usuario, avaliacao_criterio.numero_questao,  
                          avaliacao_criterio.descricao_questao 
                 order by avaliacao_criterio.numero_questao";
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        
        
        return $dados->readAll();
    }
    
    /* Recuperar Quantidade de Relat�rios Homologados 
     * -------------
     * 1 - Obter a quantidade de relat�rios homologados de um determinado auditor em um �nico exerc�cio. 
     * -------------
     * @exerc�cio (int): ano do exerc�cio. 
     * @usuario_fk (int): identifica��o (id) do auditor na base de dados.
     */
    public function RecuperaQtdeDeRelHomologados($usuario_fk, $exercicio) {
        $schema = Yii::app()->params['schema'];       
        $sql = "SELECT COUNT(1) as total_relatorio_homologado 
                  FROM ".$schema.".tb_relatorio as relatorio  
                 INNER JOIN ".$schema.".tb_relatorio_auditor as relatorio_auditor on 
                       relatorio_auditor.relatorio_fk = relatorio.id 
                 WHERE numero_relatorio is not null 
                   AND data_relatorio is not null 
                   AND date_part('year', data_relatorio) = ".$exercicio." 
                   AND relatorio_auditor.usuario_fk = ".$usuario_fk;  
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        
        return $dados->readAll();
    }
    
    /* Recuperar Quantidade de Relat�rios Homologados avaliados
     * -------------
     * 1 - Obter a quantidade de relat�rios homologados avaliados de um determinado auditor em um �nico exerc�cio. 
     * -------------
     * @exerc�cio (int): ano do exerc�cio. 
     * @usuario_fk (int): identifica��o (id) do auditor na base de dados.
     */
    public function RecuperaQtdeDeRelHomologadosAvaliados($usuario_fk, $exercicio) {
        $schema = Yii::app()->params['schema'];       
        $sql = "SELECT COUNT(1) as total_relatorio_homologado_avaliado
                  FROM ".$schema.".tb_relatorio as relatorio 
                 INNER JOIN ".$schema.".tb_avaliacao as avaliacao on
                       avaliacao.relatorio_fk = relatorio.id
                 INNER JOIN ".$schema.".tb_relatorio_auditor as relatorio_auditor on 
                       relatorio_auditor.relatorio_fk = relatorio.id and 
                       relatorio_auditor.usuario_fk = avaliacao.usuario_fk
                 WHERE numero_relatorio is not null 
                   AND data_relatorio is not null 
                   AND date_part('year', data_relatorio) = ".$exercicio." 
                   AND relatorio_auditor.usuario_fk = ".$usuario_fk;  
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        
        return $dados->readAll();
    }
    
    /* Recuperar Quantidade de Relat�rios Finalizados avaliados
     * -------------
     * 1 - Obter a quantidade de relat�rios finalizados avaliados de um determinado auditor em um �nico exerc�cio. 
     * -------------
     * @exerc�cio (int): ano do exerc�cio. 
     * @usuario_fk (int): identifica��o (id) do auditor na base de dados.
     */
    public function RecuperaQtdeDeRelFinalizadosAvaliados($usuario_fk, $exercicio) {
        $schema = Yii::app()->params['schema'];       
        $sql = "SELECT COUNT(1) as total_relatorio_finalizado_avaliado  
                  FROM ".$schema.".tb_relatorio relatorio 
                 INNER JOIN ".$schema.".tb_avaliacao as avaliacao on
                       avaliacao.relatorio_fk = relatorio.id
                 INNER JOIN ".$schema.".tb_relatorio_auditor as relatorio_auditor on 
                       relatorio_auditor.relatorio_fk = relatorio.id and 
                       relatorio_auditor.usuario_fk = avaliacao.usuario_fk
                 WHERE valor_prazo is not null
                   AND data_finalizado is not null
                   AND date_part('year', data_finalizado) = ".$exercicio."
                   AND relatorio_auditor.usuario_fk = ".$usuario_fk;  

        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        
        return $dados->readAll();
    }
    
    /* Recuperar Quantidade de Relat�rios Finalizados
     * -------------
     * 1 - Obter a quantidade de relat�rios finalizados de um determinado auditor em um �nico exerc�cio. 
     * -------------
     * @exerc�cio (int): ano do exerc�cio. 
     * @usuario_fk (int): identifica��o (id) do auditor na base de dados.
     */
    public function RecuperaQtdeDeRelFinalizados($usuario_fk, $exercicio) {
        $schema = Yii::app()->params['schema'];       
        $sql = "SELECT COUNT(1) as total_relatorio_finalizado  
                  FROM ".$schema.".tb_relatorio as relatorio 
                 INNER JOIN ".$schema.".tb_relatorio_auditor as relatorio_auditor on 
                       relatorio_auditor.relatorio_fk = relatorio.id 
                 WHERE valor_prazo is not null 
                   AND data_finalizado is not null 
                   AND date_part('year', data_finalizado) = ".$exercicio."
                   AND relatorio_auditor.usuario_fk = ".$usuario_fk;  
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }
    
    
    /* Recuperar Quantidade de Avali��es 
     * -------------
     * 1 - Obter a quantidade de avalia��es de um determinado auditor em um �nico exerc�cio. 
     * -------------
     * @exerc�cio (int): ano do exerc�cio. 
     * @usuario_fk (int): identifica��o (id) do auditor na base de dados.
     */
    public function RecuperaQtdeDeAvaliacoes($usuario_fk, $exercicio) {
        $schema = Yii::app()->params['schema'];       
        $sql = "SELECT COUNT(1) as total_avaliacao
                  FROM ".$schema.".tb_relatorio relatorio 
                 INNER JOIN ".$schema.".tb_avaliacao as avaliacao on
                       avaliacao.relatorio_fk = relatorio.id
                 WHERE date_part('year', data) = ".$exercicio."
                   AND avaliacao.usuario_fk = ".$usuario_fk;  
        $command = Yii::app()->db->createCommand($sql);
        $dados = $command->query();
        return $dados->readAll();
    }
    
    
}