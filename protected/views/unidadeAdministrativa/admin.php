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
    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/js/UnidadeAdministrativa.js');
    
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'unidade-administrativa-form',
        'enableAjaxValidation' => false,
    ));
    ?>    
    <?php echo $form->errorSummary($model); ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>

        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'nome'); ?></div>
            <div class='field'><?php echo $form->textField($model, 'nome', array('maxlength' => 80, 'size' => 35, 'style' => 'width:500px;')); ?></div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'sigla'); ?></div>
            <div class='field'><?php echo $form->textField($model, 'sigla', array('maxlength' => 20, 'size' => 20, 'style' => 'width:120px;')); ?></div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'uf_fk'); ?></div>
            <div class='field'>
                <?php
                $estados = array(null => "Selecione") + UnidadeAdministrativaController::ObtemDadosUF();
                echo $form->dropDownList($model, 'uf_fk', $estados, array('style' => 'width:150px;'));
                ?>
            </div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'subordinante_fk'); ?></div>
            <?php  $clausula = $model->id ? 'id not in(' . $model->id . ')' : '';    ?>
            <div class='field'><?php echo $form->dropDownList($model, 'subordinante_fk', array(null => "Selecione") + GxHtml::listDataEx(UnidadeAdministrativa::model()->findAll($clausula)), array('style' => 'width:150px;')); ?></div>
        </div><!-- row -->
        <div style="height:5px;"></div>
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'caracteristica'); ?></div>
            <div class='field'>
                <?php
//                $accountStatus = array('1' => 'Sim', '0' => 'N�o');
//                echo $form->radioButtonList($model, 'bolSureg', $accountStatus, array('separator' => ' '));
                echo $form->checkBox($model, 'sureg', array('value' => '1', 'uncheckValue' => '0'));
                echo " " . $form->label($model, 'sureg');
                echo "<div style='height:5px;'></div>";
                echo $form->checkBox($model, 'diretoria', array('value' => '1', 'uncheckValue' => '0'));
                echo " " . $form->label($model, 'diretoria');
                ?>
            </div>
        </div><!-- row -->
    <div style="height:10px;"></div>
    <div class='row'>
        <div class='label'><?php echo $form->label($model, 'data_extincao'); ?></div>
        <div class='field'>
            <?php echo $form->textField($model, 'data_extincao', array('maxlength' => 10, 'size' => 25)); ?>
            (Ex: <?php echo date("d/m/Y"); ?>)
        </div>
    </div>       
    </fieldset>
    <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
        <span class="required">*</span>
        <?php echo Yii::t('app', 'are required'); ?>.
    </p>
    <div class="rowButtonsN1">
        <?php echo GxHtml::submitButton(Yii::t('app', 'Confirm'), array('class' => 'botao')); ?>
        <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('UnidadeAdministrativa/index?' . $_SERVER['QUERY_STRING']), array('class' => 'imitacaoBotao')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->