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

Yii::import('application.models.table._base.BaseRiscoPre');

class RiscoPre extends BaseRiscoPre
{
    public $valor_exercicio, $processo_riscopre; 
    
    public function attributeLabels() {
		$attribute_default = parent::attributeLabels();

		$attribute_custom = array(
			'valor_exercicio' => Yii::t('app', 'Exerc�cio'),
			'processo_riscopre' => Yii::t('app', 'Processo'),
		);
		return array_merge($attribute_default, $attribute_custom);
    }    
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
	// recebe o ID do risco pr�-identificado e retorna os nomes dos processos associadas. 
	public function ProcessosPorRiscos($risco_pre_fk, $bolExercicio=false){		
		$processo_risco_pre = ProcessoRiscoPre::model()->with('processoFk')->findAll(array("condition" => 'risco_pre_fk = ' . $risco_pre_fk));
		$retorno="";
		$valor_exercicio = array();
		if (sizeof($processo_risco_pre)>0){
			foreach ($processo_risco_pre as $vetor){
				if($bolExercicio){
					$valor_exercicio[] = $vetor->processoFk->valor_exercicio;
				} else {
					$retorno.= $vetor->processoFk . "<br>";
				}
			}
		}
		
		if ($bolExercicio){
			if (sizeof($valor_exercicio)){
				$valor_exercicio = array_unique($valor_exercicio);
				foreach($valor_exercicio as $value){
					$retorno.= $value . "<br>";
				}
			}
		}
		
		return $retorno;
	}
		
}