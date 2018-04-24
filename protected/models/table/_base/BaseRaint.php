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
 * This is the model base class for the table "{{raint}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Relatorioitem".
 *
 * Columns in table "{{raint}}" available as properties of the model,
 * followed by relations of table "{{raint}}" available as properties of the model.
 *
 * @property integer $id
 * @property string $nome_titulo
 * @property string $descricao_texto
 * @property integer $numero_sequencia
 * @property boolean $numeravel
 * @property string $nome_titulo_desformatado
 * @property string $nome_tipo_item
 * @property string $anexo_id
 * @property integer $numero_item_pai
 * @property string $relatorio_template_id
 *
 * @property mixed $numeroItemPai
 * @property mixed $anexo
 * @property mixed $relatorioTemplate
 */
abstract class BaseRaint extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{raint}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Raint|Raint', $n);
	}

	public static function representingColumn() {
		return 'nome_titulo';
	}

	public function rules() {
		return array(
			array('valor_exercicio', 'required'),
			array('numero_sequencia, numero_item_pai', 'numerical', 'integerOnly'=>true),
			array('nome_titulo', 'length', 'max'=>1024),
			array('anexo_id', 'numerical', 'integerOnly'=>true),
                        array('nome_titulo, descricao_texto, numero_sequencia, anexo_id, numero_item_pai, numero_pdf', 'safe'),
			array('id, nome_titulo, descricao_texto, numero_sequencia, anexo_id, numero_item_pai', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'numeroItemPai' => array(self::BELONGS_TO, 'Raint', 'numero_item_pai'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'id' => Yii::t('app', 'ID'),
			'nome_titulo' => Yii::t('app', 'Nome Titulo'),
			'descricao_texto' => Yii::t('app', 'Descricao Texto'),
			'numero_sequencia' => Yii::t('app', 'Numero Sequencia'),
			'anexo_id' => null,
			'numero_item_pai' => null,
			'numero_pdf' => null,                    
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('nome_titulo', $this->nome_titulo, true);
		$criteria->compare('descricao_texto', $this->descricao_texto, true);
		$criteria->compare('numero_sequencia', $this->numero_sequencia);
		$criteria->compare('anexo_id', $this->anexo_id);
		$criteria->compare('numero_item_pai', $this->numero_item_pai);
		$criteria->compare('numero_pdf', $this->numero_pdf);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}