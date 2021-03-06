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
 * This is the model base class for the table "{{relatorio_despacho}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "RelatorioDespacho".
 *
 * Columns in table "{{relatorio_despacho}}" available as properties of the model,
 * and there are no model relations.
 *
 * @property integer $id
 * @property string $descricao_finalizado
 * @property string $descricao_homologado
 *
 */
abstract class BaseRelatorioDespacho extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{relatorio_despacho}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Despacho|Despachos', $n);
	}

	public static function representingColumn() {
		return 'descricao_finalizado';
	}

	public function rules() {
		return array(
			array('descricao_finalizado, descricao_homologado, descricao_pre_finalizado', 'length', 'max'=>2000),
			array('id, descricao_finalizado, descricao_homologado, descricao_pre_finalizado', 'safe', 'on'=>'search'),
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
			'descricao_finalizado' => Yii::t('app', 'Descricao finalizado'),
			'descricao_homologado' => Yii::t('app', 'Descricao homologado'),
			'descricao_pre_finalizado' => Yii::t('app', 'Descricao pr�-finalizado'),
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('descricao_finalizado', $this->descricao_finalizado, true);
		$criteria->compare('descricao_homologado', $this->descricao_homologado, true);
		$criteria->compare('descricao_pre_finalizado', $this->descricao_pre_finalizado, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}