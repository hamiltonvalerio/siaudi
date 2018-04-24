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

Yii::import('application.models.table._base.BaseAcaoSureg');

class AcaoSureg extends BaseAcaoSureg
{
    public $acao_sureg; 
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'acao_sureg' => Yii::t('app', 'Unidades Regionais'),
            'unidade_administrativa_fk' => Yii::t('app', 'ID'),
            'acao_fk' => null,
            'acaoFk' => null,
        );
        return array_merge($attribute_default, $attribute_custom);
    }
    

    // Exclui as Unidades Regionais salvas da a��o
    // e inclui as Unidades Regionais marcadas no select m�ltiplo
    public function salvar($id, $suregs) {
        $this->deleteAll("acao_fk=" . $id);
        if (is_array($suregs)) {
            foreach ($suregs as $vetor) {
                $values .= " (" . $id . "," . $vetor . "),";
            }

            $values = substr($values, 0, -1);
            $schema = Yii::app()->params['schema'];
            $sql = 'INSERT INTO ' . $schema . '.tb_acao_sureg (acao_fk, unidade_administrativa_fk) VALUES ' . $values;
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();
        }
    }
            
   
    // Carrega todas as Unidades Regionais de uma a��o
    public function carrega_acaosureg($acao) {    
        $schema = Yii::app()->params['schema'];
        $sql = 'SELECT acs.unidade_administrativa_fk as id,  vw.sigla as nome_sureg 
                FROM '.$schema .'.tb_acao_sureg acs, '.$schema .'.tb_unidade_administrativa vw
                WHERE acs.unidade_administrativa_fk=vw.id and acs.acao_fk='.$acao;
          $command = Yii::app()->db->createCommand($sql);
          $result = $command->query();
          $rs = $result->readAll();
         return($rs);
    }
}