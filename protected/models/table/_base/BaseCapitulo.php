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
 * This is the model base class for the table "{{capitulo}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Capitulo".
 *
 * Columns in table "{{capitulo}}" available as properties of the model,
 * followed by relations of table "{{capitulo}}" available as properties of the model.
 *
 * @property string $id
 * @property string $relatorio_fk
 * @property string $numero_capitulo
 * @property string $nome_capitulo
 * @property string $descricao_capitulo
 * @property string $data_gravacao
 * @property integer $numero_capitulo_decimal
 *
 * @property mixed $relatorioFk
 */
abstract class BaseCapitulo extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{capitulo}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Capitulo|Capitulos', $n);
	}

	public static function representingColumn() {
		return 'numero_capitulo';
	}

	public function rules() {
		return array(
			array('relatorio_fk, numero_capitulo, nome_capitulo', 'required'),
			array('numero_capitulo_decimal', 'numerical', 'integerOnly'=>true),
			array('numero_capitulo', 'length', 'max'=>5),
			array('nome_capitulo', 'length', 'max'=>200), 
			array('descricao_capitulo, data_gravacao, especie_auditoria_fk, categoria_fk, diretoria_fk, unidade_administrativa_fk', 'safe'),
			array('relatorio_fk', 'numerical', 'integerOnly'=>true),
			array('id, relatorio_fk, numero_capitulo, nome_capitulo, descricao_capitulo, data_gravacao, numero_capitulo_decimal', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'relatorioFk' => array(self::BELONGS_TO, 'Relatorio', 'relatorio_fk'),
                        'especieauditoriaFK' => array(self::BELONGS_TO, 'EspecieAuditoria', 'id'),
		);
	}

	public function pivotModels() {
		return array(
		);
	}

	public function attributeLabels() {
		return array(
			'id' => Yii::t('app', 'ID'),
			'relatorio_fk' => null,
			'numero_capitulo' => Yii::t('app', 'Numero Capitulo'),
			'nome_capitulo' => Yii::t('app', 'Nome Capitulo'),
			'descricao_capitulo' => Yii::t('app', 'Descricao Capitulo'),
			'data_gravacao' => Yii::t('app', 'Data Gravacao'),
			'numero_capitulo_decimal' => Yii::t('app', 'Numero Capitulo Decimal'),
			'relatorioFk' => null,
		);
	}

    
}