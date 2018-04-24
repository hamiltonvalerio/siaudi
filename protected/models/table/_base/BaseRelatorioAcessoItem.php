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
 * This is the model base class for the table "{{relatorio_acesso_item}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "RelatorioAcessoItem".
 *
 * Columns in table "{{relatorio_acesso_item}}" available as properties of the model,
 * followed by relations of table "{{relatorio_acesso_item}}" available as properties of the model.
 *
 * @property string $id
 * @property string $item_fk
 * @property string $nome_login
 * @property string $unidade_administrativa_fk
 *
 * @property mixed $itemFk
 */
abstract class BaseRelatorioAcessoItem extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{relatorio_acesso_item}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Permiss�o de Acesso ao Item|Permiss�es  de Acesso ao Item', $n);
	}

	public static function representingColumn() {
		return 'nome_login';
	}

	public function rules() {
		return array(
			array('item_fk, nome_login, relatorio_fk, unidade_administrativa_fk', 'required'),
			array('nome_login', 'length', 'max'=>60),
			array('item_fk, unidade_administrativa_fk', 'numerical', 'integerOnly'=>true),
			array('id, item_fk, nome_login, unidade_administrativa_fk', 'safe', 'on'=>'search'),
		);
	}
        

	public function relations() {
		return array(
			'itemFk' => array(self::BELONGS_TO, 'item', 'item_fk'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'id' => Yii::t('app', 'ID'),
			'item_fk' => null,
			'nome_login' => Yii::t('app', 'Nome Login'),
			'unidade_administrativa_fk' => Yii::t('app', 'Unidade Administrativa (Lota��o)'),
			'itemFk' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('item_fk', $this->item_fk);
		$criteria->compare('nome_login', $this->nome_login, true);
		$criteria->compare('unidade_administrativa_fk', $this->unidade_administrativa_fk, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}