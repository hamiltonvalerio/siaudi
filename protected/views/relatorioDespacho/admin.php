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
<div class="formulario" Style="width: 1020px;">
    <?php
    $perfil = strtolower(Yii::app()->user->role);
    $perfil = str_replace("siaudi2", "siaudi", $perfil);

    include_once(Yii::app()->basePath . '/../js/relatorio/tiny-mce-js.jsp');
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'relatorio-despacho-form',
        'enableAjaxValidation' => false,
    ));
    ?>    
    <?php echo $form->errorSummary($model); ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>

        <? // o despacho pr�-finalizado s� pode ser visualizado por gerente de n�cleo
        if ($perfil == "siaudi_gerente_nucleo") { ?>        
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'descricao_pre_finalizado'); ?></div>
            <div><?php echo $form->textArea($model, 'descricao_pre_finalizado', array('maxlength' => 2000, 'size' => 35, 'cols' => 50, 'rows' => 5, 'class' => 'tinymce_advanced')); ?>
            </div>
        </div><!-- row -->
        <? } 
        // o despacho finalizado s� pode ser visualizado por gerente
        if ($perfil == "siaudi_gerente") { ?>   
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'descricao_finalizado'); ?></div>
                <div><?php echo $form->textArea($model, 'descricao_finalizado', array('maxlength' => 2000, 'size' => 35, 'cols' => 50, 'rows' => 5, 'class' => 'tinymce_advanced')); ?></div>               
            </div><!-- row -->
        <? } 
        // o despacho homologado s� pode ser visualizado por chefe de auditoria
        if ($perfil == "siaudi_chefe_auditoria") { ?>            
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'descricao_homologado'); ?></div>
                <div><?php echo $form->textArea($model, 'descricao_homologado', array('maxlength' => 2000, 'size' => 35, 'cols' => 50, 'rows' => 5, 'class' => 'tinymce_advanced')); ?></div>
            </div><!-- row -->
<? } ?>
    </fieldset>
    <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
        <span class="required">*</span>
<?php echo Yii::t('app', 'are required'); ?>.
    </p>
    <div class="rowButtonsN1">
        <?php echo GxHtml::submitButton(Yii::t('app', 'Confirm'), array('class' => 'botao')); ?>
    <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Site/index'), array('class' => 'imitacaoBotao')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->