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
 * This is the model base class for the table "{{imagem}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Imagem".
 *
 * Columns in table "{{imagem}}" available as properties of the model,
 * and there are no model relations.
 *
 * @property integer $id
 * @property string $recomendacao_fk
 * @property string $arquivo_imagem
 * @property integer $tipo
 * @property string $login
 * @property integer $largura
 * @property integer $altura
 *
 */
abstract class BaseImagem extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{imagem}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Imagem|Imagems', $n);
	}

	public static function representingColumn() {
		return 'arquivo_imagem';
	}

	public function rules() {
		return array(
			array('tipo, largura, altura', 'numerical', 'integerOnly'=>true),
			array('arquivo_imagem', 'length', 'max'=>200),
			array('login', 'length', 'max'=>60),
			array('recomendacao_fk', 'safe'),
			array('recomendacao_fk', 'numerical', 'integerOnly'=>true),
			array('id, recomendacao_fk, arquivo_imagem, tipo, login, largura, altura', 'safe', 'on'=>'search'),
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
			'recomendacao_fk' => Yii::t('app', 'Recomenda��o  Fk'),
			'arquivo_imagem' => Yii::t('app', 'Arquivo Imagem'),
			'tipo' => Yii::t('app', 'Tipo'),
			'login' => Yii::t('app', 'Login'),
			'largura' => Yii::t('app', 'Largura'),
			'altura' => Yii::t('app', 'Altura'),
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('recomendacao_fk', $this->recomendacao_fk, true);
		$criteria->compare('arquivo_imagem', $this->arquivo_imagem, true);
		$criteria->compare('tipo', $this->tipo);
		$criteria->compare('login', $this->login, true);
		$criteria->compare('largura', $this->largura);
		$criteria->compare('altura', $this->altura);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}