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
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class MyActiveRecord extends GxActiveRecord {


    public function init() {
        parent::init();
    }

    public function behaviors() {
        return array(
            'datetimeI18NBehavior' => array('class' => 'ext.DateTimeI18NBehavior'),
            'LoggableBehavior' => 'application.modules.auditTrail.behaviors.LoggableBehavior',
        );
    }

    protected function beforeValidate() {
        if ($this->scenario != 'search') {
            $this->unidade_fk = Yii::app()->user->id_und_adm;
        }
        $this->usuario_inclusao_fk = Yii::app()->user->id;
        return parent::beforeValidate();
    }
    
    
    
    public function trapaPesquisaTexto( CDbCriteria $criteria , $campo,$parans, $alias = null){

        $this->$campo = trim($this->$campo);
        if($this->$campo){
            $criteria->addCondition($alias.$campo .' ilike :'.$campo, 'OR');
            $criteria->params = array_merge($parans , array(':'.$campo=> $this->$campo.'%') );
        }
        
    }

    public function maiuscula($string){
          return strtoupper(strtr($string ,"����������������","����������������"));
    }

}
