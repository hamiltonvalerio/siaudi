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

Yii::import('application.models.table._base.BaseAcaoRiscoPre');

class AcaoRiscoPre extends BaseAcaoRiscoPre
{
    public $acao_riscopre; 
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'acao_fk' => null,
            'risco_pre_fk' => null,
            'riscoPreFk' => null,        
            'acao_riscopre' => Yii::t('app', 'Riscos Pr�-Identificados'),
        );
        return array_merge($attribute_default, $attribute_custom);
    }
    
    
    // Exclui os riscos salvos da a��o
    // e inclui os riscos pr�-identificados marcados no select m�ltiplo
    public function salvar($id, $riscos) {
        $this->deleteAll("acao_fk=" . $id);
        if (is_array($riscos)) {
            foreach ($riscos as $vetor) {
                $values .= " (" . $id . "," . $vetor . "),";
            }

            $values = substr($values, 0, -1);
            $schema = Yii::app()->params['schema'];
            $sql = 'INSERT INTO ' . $schema . '.tb_acao_risco_pre (acao_fk, risco_pre_fk) VALUES ' . $values;
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();
        }
    }
    
//     public function salvarRiscoPreAcao($id_risco, $acao) {
//     	$this->deleteAll("risco_pre_fk=" . $id_risco);
//     	if (is_array($acao)) {
//     		foreach ($acao as $acao_fk) {
//     			$values .= " (" . $acao_fk . "," . $id_risco . "),";
//     		}
    
//     		$values = substr($values, 0, -1);
//     		$schema = Yii::app()->params['schema'];
//     		$sql = 'INSERT INTO ' . $schema . '.tb_acao_risco_pre (acao_fk, risco_pre_fk) VALUES ' . $values;
    		
//     		$command = Yii::app()->db->createCommand($sql);
//     		$command->execute();
//     	}
//     }    
    
    
        
}