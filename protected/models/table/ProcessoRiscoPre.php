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

Yii::import('application.models.table._base.BaseProcessoRiscoPre');

class ProcessoRiscoPre extends BaseProcessoRiscoPre
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
	public function salvarProcessoRiscoPre($risco_pre_fk, $processo_fk, $valor_exercicio) {
		$schema = Yii::app()->params['schema'];
		$sql = "DELETE 
                  FROM ".$schema.".tb_processo_risco_pre 
                 WHERE processo_fk IN (SELECT processo.id
    			                         FROM ".$schema.".tb_processo as processo
			                            WHERE processo.valor_exercicio = ".$valor_exercicio.")
                   AND risco_pre_fk = " . $risco_pre_fk;
		$command = Yii::app()->db->createCommand($sql);
		$command->execute();
		if (is_array($processo_fk)) {
			foreach ($processo_fk as $proc_fk) {
				$values .= " (" . $proc_fk . "," . $risco_pre_fk . "),";
			}
	
			$values = substr($values, 0, -1);
			$schema = Yii::app()->params['schema'];
			$sql = 'INSERT INTO ' . $schema . '.tb_processo_risco_pre (processo_fk, risco_pre_fk) VALUES ' . $values;
	
			$command = Yii::app()->db->createCommand($sql);
			$command->execute();
		}
	}	
	
	
	public function verificaSePodeDeletarRisco($risco_pre_fk){
		$schema = Yii::app()->params['schema'];
		$sql = "SELECT ACAO.ID
				  FROM ".$schema.".TB_PROCESSO_RISCO_PRE PROCESSO_RISCO_PRE 
				 INNER JOIN ".$schema.".TB_PROCESSO PROCESSO ON 
				       PROCESSO.ID = PROCESSO_RISCO_PRE.PROCESSO_FK 
				 INNER JOIN ".$schema.".TB_ACAO ACAO ON 
				       ACAO.PROCESSO_FK = PROCESSO.ID
				 WHERE PROCESSO_RISCO_PRE.RISCO_PRE_FK = " . $risco_pre_fk;
		$command = Yii::app()->db->createCommand($sql);
		$dados = $command->query();
		return sizeof($dados->readAll());
	}
	
}