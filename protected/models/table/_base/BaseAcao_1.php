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
 * This is the model base class for the table "{{acao}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Acao".
 *
 * Columns in table "{{acao}}" available as properties of the model,
 * followed by relations of table "{{acao}}" available as properties of the model.
 *
 * @property string $id
 * @property string $nome_acao
 * @property integer $valor_exercicio
 * @property integer $especie_auditoria_fk
 * @property integer $processo_fk
 *
 * @property mixed $processoFk
 * @property mixed $especieAuditoriaFk
 */
abstract class BaseAcao extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{acao}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Acao|Acaos', $n);
	}

	public static function representingColumn() {
		return 'nome_acao';
	}

	public function rules() {
		return array(
			array('nome_acao, valor_exercicio, especie_auditoria_fk, processo_fk', 'required'),
			array('valor_exercicio, especie_auditoria_fk, processo_fk', 'numerical', 'integerOnly'=>true),
			array('nome_acao', 'length', 'max'=>200),
			array('id', 'numerical', 'integerOnly'=>true),
			array('id, nome_acao, valor_exercicio, especie_auditoria_fk, processo_fk', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'processoFk' => array(self::BELONGS_TO, 'processo', 'processo_fk'),
			'especieAuditoriaFk' => array(self::BELONGS_TO, 'especieAuditoria', 'especie_auditoria_fk'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'id' => Yii::t('app', 'ID'),
			'nome_acao' => Yii::t('app', 'Nome Acao'),
			'valor_exercicio' => Yii::t('app', 'Valor Exercicio'),
			'especie_auditoria_fk' => null,
			'processo_fk' => null,
			'processoFk' => null,
			'especieAuditoriaFk' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('nome_acao', $this->nome_acao, true);
		$criteria->compare('valor_exercicio', $this->valor_exercicio);
		$criteria->compare('especie_auditoria_fk', $this->especie_auditoria_fk);
		$criteria->compare('processo_fk', $this->processo_fk);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}