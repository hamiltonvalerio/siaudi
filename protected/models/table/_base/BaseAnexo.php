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
 * This is the model base class for the table "{{anexo}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Anexo".
 *
 * Columns in table "{{anexo}}" available as properties of the model,
 * and there are no model relations.
 *
 * @property string $id
 * @property string $nome_anexo
 * @property string $data_inclusao
 * @property string $arquivo
 * @property integer $numero_tamanho_arquivo
 *
 */
abstract class BaseAnexo extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{anexo}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Anexo|Anexos', $n);
	}

	public static function representingColumn() {
		return 'nome_anexo';
	}

	public function rules() {
		return array(
			array('nome_anexo, data_inclusao, arquivo, numero_tamanho_arquivo', 'required'),
			array('numero_tamanho_arquivo', 'numerical', 'integerOnly'=>true),
			array('nome_anexo', 'length', 'max'=>1024),
			array('id, nome_anexo, data_inclusao, arquivo, numero_tamanho_arquivo', 'safe', 'on'=>'search'),
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
			'nome_anexo' => Yii::t('app', 'Nome Anexo'),
			'data_inclusao' => Yii::t('app', 'Data Inclusao'),
			'arquivo' => Yii::t('app', 'Arquivo'),
			'numero_tamanho_arquivo' => Yii::t('app', 'Numero Tamanho Arquivo'),
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('nome_anexo', $this->nome_anexo, true);
		$criteria->compare('data_inclusao', $this->data_inclusao, true);
		$criteria->compare('arquivo', $this->arquivo, true);
		$criteria->compare('numero_tamanho_arquivo', $this->numero_tamanho_arquivo);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}