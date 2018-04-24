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

Yii::import('application.models.table._base.BaseRisco');

class Risco extends BaseRisco
{
    public $exercicio; 
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        // busca na tabela de riscos se
        // existe alguma a��o com o exerc�cio passado
	public function busca_tabela($exercicio) {
                $schema = Yii::app()->params['schema'];        

                $sql = "select p.valor_exercicio from ". 
                        $schema . ".tb_risco r  LEFT JOIN ".
                        $schema .".tb_processo p ON (r.processo_fk = p.id)
                        WHERE ( p.valor_exercicio='".$exercicio."')
                        group by p.valor_exercicio";

                $command = Yii::app()->db->createCommand($sql);
                $result = $command->query();
               return ($result->readAll());
    }
        

        
        // busca nas tabelas de subriscos se
        // existe algum processo com o exerc�cio passado
	public function RecuperaNota($processo,$criterio) {
                $schema = Yii::app()->params['schema'];        

                // para cada crit�rio, pega os subcrit�rios e pesos
                $sql = "select id, valor_peso from ". 
                        $schema . ".tb_subcriterio
                        WHERE (criterio_fk=".$criterio.")";

                $command = Yii::app()->db->createCommand($sql);
                $result = $command->query();
                $subcriterios = $result->readAll(); 
                
                
                foreach($subcriterios as $vetor){
                    // soma os pesos do subcrit�rios para fazer m�dia composta                    
                    $subcriterios_peso +=$vetor['valor_peso']; 
                    
                    // procura pelas notas na tabela subrisco com o subcrit�rio
                    $sql = "select numero_nota from ". 
                            $schema . ".tb_subrisco
                            WHERE (subcriterio_fk=".$vetor['id']." and 
                                    processo_fk =".$processo.")";     
                    $command = Yii::app()->db->createCommand($sql);
                    $result = $command->query();
                    $rs= $result->read();
                    $nota +=$rs[numero_nota]*$vetor['valor_peso'];
                }
                if ($subcriterios_peso > 0){
                	$nota = $nota /$subcriterios_peso;
                }              
               return ($nota);
    }
}