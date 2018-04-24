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
        <legend class="legendaDiscreta">Dados da Consulta</legend>


        <div class='row'>
            <div class='label'>Per�odo</div>
            <div class='field'>
                <?php
                $form->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'language' => 'pt-BR',
                    'model' => $model,
                    'attribute' => 'data_entrada',
                    'value' => $model->data_entrada,
                    'htmlOptions' => array('size' => 15),
                    'options' => array(
                        'showButtonPanel' => true,
                        'changeYear' => true,
                        'dateFormat' => 'dd/mm/yy',
                        'changeMonth' => true,
                        'changeYear' => true,
                    ),
                ));
                ;
                ?> <span class="required">*</span>  &nbsp; &nbsp; &nbsp;  a  &nbsp; &nbsp; &nbsp; 
                
                <?php
                $form->widget('zii.widgets.jui.CJuiDatePicker', array(
                    'language' => 'pt-BR',
                    'model' => $model,
                    'attribute' => 'data_entrada2',
                    'value' => $model->data_entrada2,
                    'htmlOptions' => array('size' => 15),
                    'options' => array(
                        'showButtonPanel' => true,
                        'changeYear' => true,
                        'dateFormat' => 'dd/mm/yy',
                        'changeMonth' => true,
                        'changeYear' => true,
                    ),
                ));
                ;
                ?>  <span class="required">*</span>               
            </div>
        </div>                                


    </fieldset>

    <p class="note">
        <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
    </p>
                <div class="rowButtonsN1">


        <?php echo GxHtml::submitButton(Yii::t('app', 'Search'), array('class' => 'botao')); ?>

        <input type="reset" class='botao' value='Limpar'>
        
    </div>


<?php $this->endWidget(); ?>
</div><!-- search-form -->
