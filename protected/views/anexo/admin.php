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
        <?php     $form = $this->beginWidget('GxActiveForm', array(
    'id' => 'anexo-form',
    'enableAjaxValidation' => false,
    ));
    ?>    
<?php echo $form->errorSummary($model); ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>


                                                        <div class='row'>
<div class='label'><?php echo $form->labelEx($model,'nome_anexo'); ?></div>
                <div class='field'><?php echo $form->textField($model, 'nome_anexo', array('maxlength' => 1024,'size' => 35)); ?></div>
 </div><!-- row -->
                                                <div class='row'>
<div class='label'><?php echo $form->labelEx($model,'data_inclusao'); ?></div>
                <div class='field'><?php $form->widget('zii.widgets.jui.CJuiDatePicker', array(
			'language' => 'pt-BR',
                        'model' => $model,
			'attribute' => 'data_inclusao',
			'value' => $model->data_inclusao,
                        'htmlOptions' => array('size' => 10),
			'options' => array(
				'showButtonPanel' => true,
				'changeYear' => true,
				'dateFormat' => 'dd/mm/yy',
                                'changeMonth' => true,
                                'changeYear' => true,
				),
			));
; ?></div>
 </div><!-- row -->
                                                <div class='row'>
<div class='label'><?php echo $form->labelEx($model,'arquivo'); ?></div>
                <div class='field'><?php echo $form->textField($model, 'arquivo'); ?></div>
 </div><!-- row -->
                                                <div class='row'>
<div class='label'><?php echo $form->labelEx($model,'numero_tamanho_arquivo'); ?></div>
                <div class='field'><?php echo $form->textField($model, 'numero_tamanho_arquivo'); ?></div>
 </div><!-- row -->
                    
            </fieldset>
    <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
<span class="required">*</span>
<?php echo Yii::t('app', 'are required'); ?>.
    </p>
    <div class="rowButtonsN1">
        <?php echo GxHtml::submitButton(Yii::t('app', 'Confirm'),array('class' => 'botao')); ?>
<?php echo CHtml::link(Yii::t('app', 'Cancel'),$this->createUrl('Anexo/index'), array('class' => 'imitacaoBotao')); ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->