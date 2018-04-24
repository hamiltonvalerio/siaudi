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
 * This is the model base class for the table "{{resposta}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Resposta".
 *
 * Columns in table "{{resposta}}" available as properties of the model,
 * followed by relations of table "{{resposta}}" available as properties of the model.
 *
 * @property string $id
 * @property integer $tipo_status_fk
 * @property string $recomendacao_fk
 * @property string $data_resposta
 * @property string $id_usuario_log
 * @property string $descricao_resposta
 * @property string $tipo_arquivo
 * @property string $nome_arquivo
 * @property string $conteudo_arquivo
 *
 * @property mixed $tipoStatusFk
 */
abstract class BaseResposta extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{resposta}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Resposta|Respostas', $n);
	}

	public static function representingColumn() {
		return 'data_resposta';
	}

	public function rules() {
		return array(
			array('recomendacao_fk, data_resposta, id_usuario_log, descricao_resposta', 'required'),
			array('tipo_status_fk', 'numerical', 'integerOnly'=>true),
			array('id_usuario_log', 'length', 'max'=>60),
			array('tipo_arquivo', 'length', 'max'=>5),
			array('nome_arquivo', 'length', 'max'=>30),
			array('conteudo_arquivo', 'safe'),
			array('tipo_status_fk, recomendacao_fk', 'numerical', 'integerOnly'=>true),
			array('id, tipo_status_fk, recomendacao_fk, data_resposta, id_usuario_log, descricao_resposta, tipo_arquivo, nome_arquivo, conteudo_arquivo', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'tipoStatusFk' => array(self::BELONGS_TO, 'Siaudi.tipoStatus', 'tipo_status_fk'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'id' => Yii::t('app', 'ID'),
			'tipo_status_fk' => null,
			'recomendacao_fk' => Yii::t('app', 'Recomendacao Fk'),
			'data_resposta' => Yii::t('app', 'Data Resposta'),
			'id_usuario_log' => Yii::t('app', 'Id Usuario Log'),
			'descricao_resposta' => Yii::t('app', 'Descricao Resposta'),
			'tipo_arquivo' => Yii::t('app', 'Tipo Arquivo'),
			'nome_arquivo' => Yii::t('app', 'Nome Arquivo'),
			'conteudo_arquivo' => Yii::t('app', 'Conteudo Arquivo'),
			'tipoStatusFk' => null,
		);
	}

	public function search() {
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('tipo_status_fk', $this->tipo_status_fk);
		$criteria->compare('recomendacao_fk', $this->recomendacao_fk, true);
		$criteria->compare('data_resposta', $this->data_resposta, true);
		$criteria->compare('id_usuario_log', $this->id_usuario_log, true);
		$criteria->compare('descricao_resposta', $this->descricao_resposta, true);
		$criteria->compare('tipo_arquivo', $this->tipo_arquivo, true);
		$criteria->compare('nome_arquivo', $this->nome_arquivo, true);
		$criteria->compare('conteudo_arquivo', $this->conteudo_arquivo, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}