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
 * This is the model base class for the table "{{processo_risco_pre}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "ProcessoRiscoPre".
 *
 * Columns in table "{{processo_risco_pre}}" available as properties of the model,
 * followed by relations of table "{{processo_risco_pre}}" available as properties of the model.
 *
 * @property string $processo_fk
 * @property string $risco_pre_fk
 *
 * @property mixed $riscoPreFk
 * @property mixed $processoFk
 */
abstract class BaseProcessoRiscoPre extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{processo_risco_pre}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'ProcessoRiscoPre|ProcessoRiscoPres', $n);
	}

	public static function representingColumn() {
		return 'processo_fk';
	}
	
	public function primaryKey(){ return array('processo_fk');}

	public function rules() {
		return array(
			array('processo_fk, risco_pre_fk', 'required'),
			array('processo_fk, risco_pre_fk', 'numerical', 'integerOnly'=>true),
			array('processo_fk, risco_pre_fk', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'riscoPreFk' => array(self::BELONGS_TO, 'RiscoPre', 'risco_pre_fk'),
			'processoFk' => array(self::BELONGS_TO, 'Processo', 'processo_fk'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'processo_fk' => null,
			'risco_pre_fk' => null,
			'riscoPreFk' => null,
			'processoFk' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('processo_fk', $this->processo_fk);
		$criteria->compare('risco_pre_fk', $this->risco_pre_fk);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}