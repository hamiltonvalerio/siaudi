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

Yii::import('application.models.table._base.BaseAvaliacaoCriterio');

class AvaliacaoCriterio extends BaseAvaliacaoCriterio
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'id' => Yii::t('app', 'ID'),
            'numero_questao' => Yii::t('app', 'N� da quest�o'),
            'descricao_questao' => Yii::t('app', 'Descri��o da quest�o'),
            'valor_exercicio' => Yii::t('app', 'Exerc�cio'),
            
            
        );
        return array_merge($attribute_default, $attribute_custom);
    }
    
    
    // Verifica se existem crit�rios de avalia��o cadastrados
    // para o exerc�cio atual. Caso n�o existam, envia e-mail
    // para os gerentes da Auditoria avisando.
    public function VerificaAvaliacaoCriterio(){
        $exercicio = date("Y");
        $Criterios= AvaliacaoCriterio::model()->findAllByAttributes(array('valor_exercicio'=>$exercicio));
        // se n�o encontrou crit�rios no exerc�cio, identifica gerentes e chefes de auditoria
        if (sizeof($Criterios)==0){
            //pega gerentes e chefes da AUDIN
            $cargo_gerente = Perfil::model()->findByAttributes(array('nome_interno'=>'SIAUDI_GERENTE'));
            $cargo_chefe = Perfil::model()->findByAttributes(array('nome_interno'=>'SIAUDI_CHEFE_AUDITORIA'));

            $gerentes = Usuario::model()->findAllByAttributes(array('perfil_fk'=>$cargo_gerente->id));
            $chefes = Usuario::model()->findAllByAttributes(array('perfil_fk'=>$cargo_chefe->id)); 
            if(is_array($gerentes)){
                foreach ($gerentes as $vetor){
                    $vetor_email[]=$vetor->nome_login;
                }
            }
            if(is_array($chefes)){
                foreach ($chefes as $vetor){
                    $vetor_email[]=$vetor->nome_login;
                }
            }                 
            // configura par�metros para enviar o e-mail
            $headers = "Reply-To: SIAUDI - CONAB <siaudi@conab.gov.br>\r\n";
            $headers .= "Return-Path: SIAUDI - CONAB <siaudi@conab.gov.br>\r\n";
            $headers .= "From: FROM SIAUDI <siaudi@conab.gov.br>\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
            $assunto = 'SIAUDI - Cadastrar crit�rios de avalia��o dos auditores';

            // formata texto html
            $mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
            $mensagem.= "Esta � uma mensagem autom�tica. O sistema SIAUDI informa que est� sem ";
            $mensagem.= "crit�rios de avalia��o dos auditores, para o exerc�cio de <strong>".$exercicio."</strong>.<br><br>";
            $mensagem.= "� necess�rio cadastrar tais crit�rios para que as unidades auditadas possam avaliar ";
            $mensagem.= "seus respectivos auditores. Para tanto, acesse o menu ";
            $mensagem.= " 'Configura��es -> Avalia��o do Auditor -> Gerenciar Crit�rios de Avalia��o'";
            $mensagem.= "</font></body></html>";    

            // envia e-mails            
            foreach($vetor_email as $vetor){ 
                // verifica se o e-mail j� foi enviado para evitar mais 
                // de 1 envio para o mesmo destinar�rio
                if(!$check_email[$vetor]){
                    $check_email[$vetor]=1;
                    $destinatario = $vetor."@conab.gov.br";
                    $ok = Relatorio::model()->Envia_email($destinatario, $assunto, $mensagem, $headers);
                }
            }
        }
    }
    
    
    /* Copia os crit�rios de avalia��o de um ano para o outro.
     * Se o ano atual n�o tem crit�rios cadastrados, verifica
     * se o ano anterior tem, e ent�o, copia para o ano atual. 
     */
    public function CopiaAvaliacaoCriterio(){
        $ano_atual = date("Y");
        $ano_anterior= date("Y")-1;
        $AvaliacaoCriterio = $this->findAll('valor_exercicio='.$ano_atual);
        // se n�o existem crit�rios de avalia��o no ano atual, 
        // ent�o  verifica se existem no ano anterior para copiar
        if(sizeof($AvaliacaoCriterio)==0){
            $AvaliacaoCriterio2 = $this->findAll('valor_exercicio='.$ano_anterior);
            // se exitem crit�rios no ano anterior, ent�o copia para o atual
            if(sizeof($AvaliacaoCriterio2)>0){
                foreach($AvaliacaoCriterio2 as $vetor){
                    $Criterio = new AvaliacaoCriterio();
                    $Criterio->descricao_questao = $vetor[descricao_questao];
                    $Criterio->valor_exercicio= $ano_atual;
                    $Criterio->numero_questao = $vetor[numero_questao];
                    $Criterio->save();
                }
            }
        }
    }
}