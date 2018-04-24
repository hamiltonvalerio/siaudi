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
<div class="formulario" Style="width: 70%;">

    <?php
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
            ));
    ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Consultar -  <?php echo $model->label(); ?></legend>

        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'nome_login'); ?></div>
            <div class='field'>
                <?php echo $form->textField($model, 'nome_login', array('maxlength' => 50, 'size' => 35)); ?>
            </div>
        </div>                                                                            
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'nome_usuario'); ?></div>
            <div class='field'>
            <?php
                 $plan_usuario_dados= array(null=>"Selecione") + CHtml::listData(Usuario::model()->findAll(array('order'=>'nome_usuario')), 'id', 'nome_usuario');                 
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                    'model' => $model,
                    'dropDownAttribute' => 'nome_usuario',
                    'data' => $plan_usuario_dados,
                    'dropDownHtmlOptions' => array(
                        'id' => 'nome_usuario',
                    ),
                    'options' => array(
                        'selectedList' => 1,
                        'minWidth' => '250',
                        'filter'=>true,                        
                        'multiple' => false,                                                
                    ),
                    'filterOptions'=> array(
                         'width'=>150,
                        'label' => Yii::t('application','Filtrar:'),
                        'placeholder'=>Yii::t('application','digite aqui'),
                        'autoReset'=>false,                        
                     ),                        
                ));  
                    
                ?>                            
            </div>
        </div>                                
        <div style="height:3px"></div>
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'perfil_fk'); ?></div>
            <div class='field'>
                <?php echo $form->dropDownList($model, 'perfil_fk', GxHtml::listDataEx(Cargo::model()->findAllAttributes(null, true)), array('prompt' => Yii::t('app', ' Selecione'), 'style'=>'width:250px')); ?>
            </div>
        </div>                                
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'nucleo_fk'); ?></div>
            <div class='field'>
                <?php echo $form->dropDownList($model, 'nucleo_fk', GxHtml::listDataEx(Nucleo::model()->findAllAttributes(null, true)), array('prompt' => Yii::t('app', ' Selecione'), 'style'=>'width:250px')); ?>
            </div>
        </div>                
        <div align='right'>
            <input type="hidden" name="Usuario_sort" value="nome_usuario">
                <div class="rowButtonsN1">


        <?php echo GxHtml::submitButton(Yii::t('app', 'Search'), array('class' => 'botao')); ?>

        <input type="reset" class='botao' value='Limpar'>
    </div>
    </fieldset>

    <p class="note">
        <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
    </p>

    <div class="rowButtonsN1">
        <?php echo CHtml::link(Yii::t('app', 'Create'), $this->createUrl('Usuario/admin'), array('class' => 'imitacaoBotao')); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- search-form -->
