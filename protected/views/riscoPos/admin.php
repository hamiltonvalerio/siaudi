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
$dadosRiscoPreIdentificados = RiscoPre::model()->findAllAttributes(null, true);
?>



<!--//<script type="text/javascript">-->
<!--//    function carregaRisco() {-->
<!--////        var indexSelect = document.getElementById("RiscoPos_id").selectedIndex;-->
<!--//        var idSelecionado = $('#RiscoPos_id').val();-->
<!--//-->        
<!--//    }-->
<!--//</script>-->


<div class="formulario" Style="width: 70%;">
    <?php
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'risco-pos-form',
        'enableAjaxValidation' => false,
    ));
    ?>    
    <?php
//    debug($this->route);
    $_jj = Yii::app()->getClientScript();
    Yii::app()->clientScript->registerScript('importar_risco_pre', "
$('#RiscoPos_id').live('change',function(){
    $.ajax({
        url:'" . Yii::app()->createUrl('RiscoPos/ImportaRiscoPreAjax') . "',
        type:'GET',
        data: 'id=' + $(this).val(),
        //dataType:'json',
        success: function(data){
         var obj = jQuery.parseJSON(data)
         //console.log(obj);
         if (obj != null){
            $('#RiscoPos_nome_risco').val(obj.nome_risco);
            $('#RiscoPos_descricao_impacto').val(obj.descricao_impacto);
            $('#RiscoPos_descricao_mitigacao').val(obj.descricao_mitigacao);
         }
         else {
            $('#RiscoPos_nome_risco').val('');
            $('#RiscoPos_descricao_impacto').val('');
            $('#RiscoPos_descricao_mitigacao').val('');
         }
        },
        error: function(data) {
            alert(\"Ocorreu um erro no sistema.\");
            alert(data);
        },
    });
});");
    ?> 
    <?php echo $form->errorSummary($model); ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>

        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'importar_risco_pre'); ?></div>
            <div class='field'><?php echo $form->dropDownList($model, 'id', GxHtml::listDataEx($dadosRiscoPreIdentificados), array('prompt' => Yii::t('app', ' Selecione'), 'style' => 'width:150px;')); ?></div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'nome_risco'); ?></div>
            <div class='field'><?php echo $form->textArea($model, 'nome_risco', array('cols' => '100', 'rows' => '5'));
    ?></div>
        </div><!-- row -->
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'descricao_impacto'); ?></div>
            <div class='field'><?php echo $form->textArea($model, 'descricao_impacto', array('cols' => '100', 'rows' => '5'));
    ?></div>
        </div><!-- row -->

        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'descricao_mitigacao'); ?></div>
            <div class='field'><?php
                echo $form->textArea($model, 'descricao_mitigacao', array('cols' => '100', 'rows' => '5')
                );
                ?></div>
        </div><!-- row -->

    </fieldset>
    <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
        <span class="required">*</span>
        <?php echo Yii::t('app', 'are required'); ?>.
    </p>
    <div class="rowButtonsN1">
        <?php echo GxHtml::submitButton(Yii::t('app', 'Confirm'), array('class' => 'botao')); ?>
        <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('RiscoPos/index'), array('class' => 'imitacaoBotao')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->