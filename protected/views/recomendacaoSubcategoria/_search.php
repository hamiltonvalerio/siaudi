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
            <div class='label'><?php echo $form->label($model, 'recomendacao_categoria_fk'); ?></div>
            <div class='field'>
                <?php echo $form->dropDownList($model, 'recomendacao_categoria_fk', GxHtml::listDataEx(RecomendacaoCategoria::model()->findAllAttributes(null, true, array('order' => 'nome_categoria'))), array('prompt' => Yii::t('app', ' Selecione'), 'style' => 'width:188px;')); ?>
            </div>
        </div>
        <div style="height:10px;"></div>
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'nome_subcategoria'); ?></div>
            <div class='field'>
                <?php echo $form->textField($model, 'nome_subcategoria', array('maxlength' => 200, 'size' => 35, 'style' => 'width: 500px')); ?>
            </div>
        </div>      
        <input type="hidden" name="RecomendacaoSubcategoria_sort" value="recomendacao_categoria_fk">
        <div class="rowButtonsN1">


            <?php echo GxHtml::submitButton(Yii::t('app', 'Search'), array('class' => 'botao')); ?>

            <input type="reset" class='botao' value='Limpar'>

        </div>
    </fieldset>


    <div class="rowButtonsN1">
        <?php echo CHtml::link(Yii::t('app', 'Create'), $this->createUrl('RecomendacaoSubcategoria/admin'), array('class' => 'imitacaoBotao')); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- search-form -->
