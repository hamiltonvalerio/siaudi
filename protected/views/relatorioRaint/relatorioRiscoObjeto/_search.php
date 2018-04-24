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
        'method' => 'post',
    ));
    echo $form->errorSummary($model);
    ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Consultar - <?php echo $model->label(); ?></legend>
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'valor_exercicio'); ?>*</div>
            <div class='field'>
                <?php
                $form->widget('CMaskedTextField', array(
                    'model' => $model,
                    'attribute' => 'valor_exercicio',
                    'mask' => '9999',
                    'placeholder' => '_',
                    'htmlOptions' => array('maxlength' => 4,
                        'value' => $_GET['exercicio'])
                ));
                ?> (Ex: <?php echo date("Y"); ?>)          
            </div>
        </div> 
        <div style="height:8px;"></div>
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'objeto_fk'); ?></div>
            <div class='field'>
                <?php
                $acao_objeto_dados = array("Todos" => "Todos") + CHtml::listData(Objeto::model()->findAll(array('order' => 'nome_objeto')), 'id', 'nome_objeto');
                $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                    'model' => $model,
                    'dropDownAttribute' => 'objeto_fk',
                    'data' => $acao_objeto_dados,
                    'dropDownHtmlOptions' => array(
                        'id' => 'objeto_fk',
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
                                        <div class="rowButtonsN1">


        <?php echo GxHtml::submitButton(Yii::t('app', 'Search'), array('class' => 'botao')); ?>

        <input type="reset" class='botao' value='Limpar'>
        
    </div>
    </fieldset>

    <p class="note">
        <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
    </p>
    
    <?php if ($bolNaoEncontrouRegistro) :  ?>
        <fieldset class="visivel">
            N�o foram encontrados valores para o exerc�cio informado.
        </fieldset>
    <?php endif; ?>    

    <?php $this->endWidget(); ?>
</div><!-- search-form -->
