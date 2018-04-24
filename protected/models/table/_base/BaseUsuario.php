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
 * This is the model base class for the table "{{usuario}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Usuario".
 *
 * Columns in table "{{usuario}}" available as properties of the model,
 * followed by relations of table "{{usuario}}" available as properties of the model.
 *
 * @property string $nome_login
 * @property string $id
 * @property string $nome_usuario
 * @property string $perfil_fk
 * @property string $nucleo_fk
 * 
 * @property string $unidade_administrativa_fk
 * @property string $cargo_fk
 * @property string $substituto_fk
 * @property string $funcao_fk
 * @property string $cpf
 * @property string $email
 * @property string $senha
 * 
 *
 * @property mixed $cargoFk
 * @property mixed $nucleoFk
 * 
 * @property mixed $funcaoFk
 * @property mixed $unidadeAdministrativaFk
 * @property mixed $perfilFk
 */
abstract class BaseUsuario extends GxActiveRecord {

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return '{{usuario}}';
	}

	public static function label($n = 1) {
		return Yii::t('app', 'Usuario|Usuarios', $n);
	}

	public static function representingColumn() {
		return 'nome_usuario';
	}

	public function rules() {
		return array(
			array('nome_login, nome_usuario, perfil_fk, unidade_administrativa_fk, cargo_fk, funcao_fk, nucleo_fk', 'required'),
			array('nome_login', 'length', 'max'=>60),
			array('nome_usuario', 'length', 'max'=>400),
			array('cpf', 'length', 'max'=>11),
			array('email', 'length', 'max'=>100),
			array('senha', 'length', 'max'=>255),
			array('unidade_administrativa_fk, cargo_fk, substituto_fk, funcao_fk', 'safe'),
                        array('perfil_fk, nucleo_fk, unidade_administrativa_fk, cargo_fk, funcao_fk', 'numerical', 'integerOnly'=>true),
			array('nome_login, id, nome_usuario, perfil_fk, nucleo_fk, unidade_administrativa_fk, cargo_fk, substituto_fk, funcao_fk', 'safe', 'on'=>'search'),
		);
	}

	public function relations() {
		return array(
			'cargoFk' => array(self::BELONGS_TO, 'Cargo', 'cargo_fk'),
			'funcaoFk' => array(self::BELONGS_TO, 'Funcao', 'funcao_fk'),
			'unidadeAdministrativaFk' => array(self::BELONGS_TO, 'UnidadeAdministrativa', 'unidade_administrativa_fk'),                    
			'nucleoFk' => array(self::BELONGS_TO, 'Nucleo', 'nucleo_fk'),
			'perfilFk' => array(self::BELONGS_TO, 'Perfil', 'perfil_fk'),
			'substitutoFk' => array(self::BELONGS_TO, 'Usuario', 'substituto_fk'),
		);
	}

//	public function pivotModels() {
//		return array(
//		);
//	}

	public function attributeLabels() {
		return array(
			'nome_login' => Yii::t('app', 'Nome Login'),
			'id' => Yii::t('app', 'ID'),
			'nome_usuario' => Yii::t('app', 'Nome do usu�rio'),
			'perfil_fk' => null,
			'nucleo_fk' => null,
			'unidade_administrativa_fk' => null,
			'cargo_fk' => null,
			'substitutoFk' => null,
			'funcao_fk' => null,
			'substituto_fk' => null,
                    
			'cpf' => Yii::t('app', 'CPF'),
			'email' => Yii::t('app', 'E-mail'),
			'senha' => Yii::t('app', 'Senha'),
                    
			'cargoFk' => null,
			'funcaoFk' => null,
			'unidadeAdministrativaFk' => null,
			'nucleoFk' => null,
			'perfilFk' => null
		);
	}


}