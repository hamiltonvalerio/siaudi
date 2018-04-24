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
$_jj = Yii::app()->getClientScript();
Yii::app()->clientScript->registerScript('credenciado_cep', "
    $(document).ready(function(){  
        $('.orientacao').hide();    
    });
    $('#link_novo_acesso').live('mouseenter',function(){
        $(this).css('color','#ff0000');
    });
    $('#link_novo_acesso').live('mouseleave',function(){
        $(this).css('color','#333333');
    });
");
?>
<div class="formulario" Style="width: 400px; padding-left: 30%">
    <?php
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'login-form',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),));
    ?>
    <?php echo $form->errorSummary($model); ?>
    <fieldset class="visivel">

        <div class="row">

            <div class="label"><?php echo $form->labelEx($model, 'usuario_login'); ?></div>
        </div>

        <div class="row">

            <div class="field">
                <div class='field'>
                    <?php
                        echo $form->textField($model, 'usuario_login', array('maxlength' => 100, 'size' => 20));
                    ?>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="label"><?php echo $form->labelEx($model, 'usuario_senha'); ?></div>
        </div>
        <div class="row">
            <div class="field"><?php echo $form->passwordField($model, 'usuario_senha'); ?></div>
        </div>
        <br />
        <!--div class="row">
            Esqueceu a senha? <b><?php echo CHtml::link(Yii::t('app', 'Clique aqui'), $this->createUrl('/prestadorAcesso/admin?tipo_acesso=' . $_GET['tipo_acesso'])); ?></b>.
        </div -->

        <div class="rowButtonsN1">
            <?php echo GxHtml::submitButton(Yii::t('app', 'Entrar'), array('class' => 'botao')); ?>
        </div>

        <?php $this->endWidget(); ?>
    </fieldset>
</div><!-- form -->
