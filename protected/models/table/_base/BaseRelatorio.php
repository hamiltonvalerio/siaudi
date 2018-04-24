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
 * This is the model base class for the table "{{relatorio}}".
 * DO NOT MODIFY THIS FILE! It is automatically generated by giix.
 * If any changes are necessary, you must set or override the required
 * property or method in class "Relatorio".
 *
 * Columns in table "{{relatorio}}" available as properties of the model,
 * followed by relations of table "{{relatorio}}" available as properties of the model.
 *
 * @property string $id
 * @property integer $numero_relatorio
 * @property string $data_relatorio
 * @property integer $especie_auditoria_fk
 * @property string $descricao_introducao
 * @property integer $categoria_fk
 * @property string $data_finalizado
 * @property integer $valor_prazo
 * @property integer $st_libera_homologa
 * @property string $data_gravacao
 * @property string $data_regulariza
 * @property string $login_finaliza
 * @property string $login_homologa
 *
 * @property mixed $especieAuditoriaFk
 * @property mixed $categoriaFk
 */
abstract class BaseRelatorio extends GxActiveRecord {
    public $auditor_fk, $gerente_fk; 
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{relatorio}}';
    }

    public static function label($n = 1) {
        return Yii::t('app', 'Relat�rio|Relat�rios', $n);
    }

    public static function representingColumn() {
        return 'id';
    }

    public function rules() {
        return array(
            array('diretoria_fk, unidade_administrativa_fk, auditor_fk, gerente_fk, especie_auditoria_fk, categoria_fk, nucleo, descricao_introducao', 'required'),
            array('numero_relatorio, especie_auditoria_fk, categoria_fk, st_libera_homologa', 'numerical', 'integerOnly' => true),
            array('login_finaliza, login_pre_finaliza, login_homologa', 'length', 'max' => 60),
            array('data_relatorio, descricao_introducao, data_finalizado, data_pre_finalizado, data_gravacao, data_regulariza, login_criacao, valor_prazo, diretoria_fk, unidade_administrativa_fk, nucleo, filtro_acesso', 'safe'),
            array('numero_relatorio, especie_auditoria_fk, categoria_fk, valor_prazo, st_libera_homologa, plan_especifico_fk', 'numerical', 'integerOnly' => true),
            array('id, numero_relatorio, data_relatorio, especie_auditoria_fk, descricao_introducao, categoria_fk, data_finalizado, data_pre_finalizado, valor_prazo, st_libera_homologa, data_gravacao, data_regulariza, login_finaliza, login_pre_finaliza, login_homologa, plan_especifico_fk', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'especieAuditoriaFk' => array(self::BELONGS_TO, 'EspecieAuditoria', 'especie_auditoria_fk'),
        	'planEspecificoFk' => array(self::BELONGS_TO, 'PlanEspecifico', 'plan_especifico_fk'),
            'categoriaFk' => array(self::BELONGS_TO, 'Categoria', 'categoria_fk'),
            //'diretoriaFk' => array(self::BELONGS_TO, 'Diretoria', 'diretoria_fk'),
        );
    }

    public function pivotModels() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
            'id' => Yii::t('app', 'ID'),
            'numero_relatorio' => Yii::t('app', 'Numero Relatorio'),
            'data_relatorio' => Yii::t('app', 'Data Relatorio'),
            'especie_auditoria_fk' => null,
            'descricao_introducao' => Yii::t('app', 'Descricao Introducao'),
            'categoria_fk' => null,
            'data_pre_finalizado' => Yii::t('app', 'Data Pr�-Finalizado'),
            'data_finalizado' => Yii::t('app', 'Data Finalizado'),
            'valor_prazo' => Yii::t('app', 'Valor Prazo'),
            'st_libera_homologa' => Yii::t('app', 'St Libera Homologa'),
            'data_gravacao' => Yii::t('app', 'Data Gravacao'),
            'data_regulariza' => Yii::t('app', 'Data Regulariza'),
            'login_finaliza' => Yii::t('app', 'Login Finaliza'),
            'login_pre_finaliza' => Yii::t('app', 'Login Pr�-Finaliza'),
            'login_homologa' => Yii::t('app', 'Login Homologa'),
            'nucleo' => Yii::t('app', 'Bol Emitido pelo N�cleo'),
        	'plan_especifico_fk' =>Yii::t('app', 'Planejamento Espec�fico'),
        	'planEspecificoFk' => null,
            'especieAuditoriaFk' => null,
            'categoriaFk' => null,
        );
    }

    public function search() {
        
    }

}