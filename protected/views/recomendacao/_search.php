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
    $cs->registerScriptFile($baseUrl.'/js/mask.js');      
    $cs->registerScriptFile($baseUrl . '/js/Recomendacao.js');
    
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
            ));
    ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Consultar -  <?php echo $model->label(); ?></legend>

        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'relatorio_fk'); ?></div>
            <div class='field'><?php echo $form->dropDownList($model, 'relatorio_fk', GxHtml::listDataEx(Relatorio::model()->relatorio_por_especie_combo(), 'id', 'sigla_auditoria'), array('prompt' => Yii::t('app', ' Selecione'), 'style' => 'width:410px;')); ?>                
            </div>        
        <div style="height:5px;"></div>
                <div class='row'>
                    <div class='label'><?php echo $form->label($model, 'numero_capitulo'); ?></div>
                    <div class='field'>
                        <?php echo $form->textField($model, 'numero_capitulo', array('maxlength' => 5, 'size' => 5)); ?>
                    </div>
                </div>                                            
        <div style="height:5px;"></div>                                       
            <div class='row'>
                <div class='label'><?php echo $form->label($model, 'categoria_fk'); ?></div>
                <div class='field'>
                    <?php echo $form->dropDownList($model, 'categoria_fk', GxHtml::listDataEx(Categoria::model()->findAllAttributes(null, true)), array('prompt' => Yii::t('app', ' Selecione'), 'style' => 'width:410px;')); ?>
                </div>
            </div>      
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'diretoria_fk'); ?></div>
                <div class='field'>
                    <?php
                    $relatorio_diretoria_dados = array(null => "Selecione") + CHtml::listData(UnidadeAdministrativa::model()->findAll(array("condition"=>'"diretoria" = true','order' => 'sigla')), 'id', 'sigla');
                    //debug($relatorio_diretoria_dados);
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                        'model' => $model,
                        'dropDownAttribute' => 'diretoria_fk',
                        'data' => $relatorio_diretoria_dados,
                        'dropDownHtmlOptions' => array(
                            'id' => 'diretoria_fk',
                        ),
                        'options' => array(
                            'selectedList' => 1,
                            'minWidth' => '410',
                            'filter' => true,
                            'multiple' => false,
                        ),
                        'filterOptions' => array(
                            'width' => 150,
                            'label' => Yii::t('application', 'Filtrar:'),
                            'placeholder' => Yii::t('application', 'digite aqui'),
                            'autoReset' => false,
                        ),
                    ));
                    ?>

                </div>
            </div><!-- row -->   

            <div class='row'>
                <div class='label'><?php echo $form->label($model, 'unidade_administrativa_fk'); ?></div>
                <div class='field'>
                    <?php
                    $acao_sureg_dados = array(null => "Selecione") + CHtml::listData(UnidadeAdministrativa::model()->findAll(array("condition"=>'"sureg" = true','order' => 'sigla')), 'id', 'sigla');
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                        'model' => $model,
                        'dropDownAttribute' => 'unidade_administrativa_fk',
                        'data' => $acao_sureg_dados,
                        'dropDownHtmlOptions' => array(
                            'id' => 'unidade_administrativa_fk',
                        ),
                        'options' => array(
                            'selectedList' => 1,
                            'minWidth' => '410',
                            'filter' => true,
                            'multiple' => false,
                        ),
                        'filterOptions' => array(
                            'width' => 150,
                            'label' => Yii::t('application', 'Filtrar:'),
                            'placeholder' => Yii::t('application', 'digite aqui'),
                            'autoReset' => false,
                        ),
                    ));
                    ?>
                </div>
            </div>  
        <div style="height:10px;"></div>
                        <div class="rowButtonsN1">


        <?php echo GxHtml::submitButton(Yii::t('app', 'Search'), array('class' => 'botao')); ?>

        <input type="reset" class='botao' value='Limpar'>
        <?php echo CHtml::link(Yii::t('app', 'Voltar para Item'), $this->createUrl('item/'), array('class' => 'imitacaoBotao')); ?>
    </div>       
    </fieldset>

    <p class="note">
        <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
    </p>

    <div class="rowButtonsN1">
        <?php echo CHtml::link(Yii::t('app', 'Create'), $this->createUrl('Recomendacao/admin'), array('class' => 'imitacaoBotao')); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- search-form -->
