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
 * This is the model base class for the table "{{relatorio_auditor}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "RelatorioAuditor".
 *
 * Columns in table "{{relatorio_auditor}}" available as properties of the model,
 * followed by relations of table "{{relatorio_auditor}}" available as properties of the model.
 *
 * @property string $relatorio_fk
 * @property integer $auditor_fk
 *
 * @property mixed $relatorioFk
 * @property mixed $auditorFk
 */
abstract class BaseRelatorioAuditor extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{relatorio_auditor}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'RelatorioAuditor|RelatorioAuditors', $n);
	}

	public static function representingColumn() {
		return 'relatorio_fk';
	}

	public function rules() {
		return array(
			array('relatorio_fk', 'required'),
			array('auditor_fk', 'numerical', 'integerOnly'=>true),                    
			array('usuario_fk', 'numerical', 'integerOnly'=>true),
			array('relatorio_fk', 'numerical', 'integerOnly'=>true),
			array('relatorio_fk, usuario_fk', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'relatorioFk' => array(self::BELONGS_TO, 'Siaudi.relatorio', 'relatorio_fk'),
			'usuarioFk' => array(self::BELONGS_TO, 'Siaudi.usuario', 'usuario_fk'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'relatorio_fk' => null,
			'usuario_fk' => null,
			'relatorioFk' => null,
			'usuarioFk' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;
		$criteria->compare('relatorio_fk', $this->relatorio_fk);
		$criteria->compare('usuario_fk', $this->usuario_fk);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}