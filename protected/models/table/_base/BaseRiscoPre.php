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

/**
 * This is the model base class for the table "{{risco_pre}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "RiscoPre".
 *
 * Columns in table "{{risco_pre}}" available as properties of the model,
 * and there are no model relations.
 *
 * @property string $id
 * @property string $descricao_impacto
 * @property string $nome_risco
 * @property string $descricao_mitigacao
 *
 */
abstract class BaseRiscoPre extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{risco_pre}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Risco Pr�-Identificado|Riscos Pr�-Identificados', $n);
	}

	public static function representingColumn() {
		return 'nome_risco';
	}

	public function rules() {
		return array(
			array('nome_risco, processo_riscopre', 'required'),
			array('descricao_mitigacao, descricao_impacto', 'safe'),                    
			array('id, descricao_impacto, nome_risco, descricao_mitigacao', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {		
		return array(
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'id' => Yii::t('app', 'ID'),
			'nome_risco' => Yii::t('app', 'Risco'),                    
			'descricao_impacto' => Yii::t('app', 'Impacto'),
			'descricao_mitigacao' => Yii::t('app', 'Mitiga��o'),
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('descricao_impacto', $this->descricao_impacto, true);
		$criteria->compare('nome_risco', $this->nome_risco, true);
		$criteria->compare('descricao_mitigacao', $this->descricao_mitigacao, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}