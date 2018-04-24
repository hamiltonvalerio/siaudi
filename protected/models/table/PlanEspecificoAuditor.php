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

Yii::import('application.models.table._base.BasePlanEspecificoAuditor');

class PlanEspecificoAuditor extends BasePlanEspecificoAuditor
{
    public $plan_auditor; 
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
        'plan_especifico_fk' => null,
        'auditor_fk' => Yii::t('app', 'Auditor Fk'),
        'planEspecificoFk' => null,        
        'plan_auditor' => Yii::t('app', 'Auditores'),                    
        );
        return array_merge($attribute_default, $attribute_custom);
    }
    

       
    // Exclui os auditores salvos do planejamento espef�cico
    // e inclui os auditores marcados no select m�ltiplo
    public function salvar($id, $auditores) {
        $this->deleteAll("plan_especifico_fk=" . $id);
        if (is_array($auditores)) {
            foreach ($auditores as $vetor) {
                $values .= " (" . $id . "," . $vetor . "),";
            }

            $values = substr($values, 0, -1);
            $schema = Yii::app()->params['schema'];
            $sql = 'INSERT INTO ' . $schema . '.tb_plan_especifico_auditor (plan_especifico_fk, usuario_fk) VALUES ' . $values;
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();

        }
    }    
     
              
        

}