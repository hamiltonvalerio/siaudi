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
<script type="text/javascript">
$('#acao_riscopre').live('change', function() {
    $('#acao_riscopre').multiselect({
        close: function(event, ui) {
            $("#acao_riscopre").multiselect("refresh");
        }
    });
});

	$(document).ready(function() {
	    $("#acao_riscopre").multiselect({
	    	create: function() {
	    		$("#acao_riscopre").multiselect('refresh');
	    	    $("#acao_riscopre").multiselect("checkAll");
	    	},
	    	noneSelectedText : "Selecione"
	   	});        
	});

// 

</script>

<div class="formulario" Style="width: 70%;">
    <?php
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'risco-pre-form',
        'enableAjaxValidation' => false,
    ));
    
    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/js/mask.js');    
    ?>    
    <?php echo $form->errorSummary($model); ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>

        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'nome_risco'); ?></div>
            <div class='field'><?php echo $form->textArea($model, 'nome_risco', array('cols' => '100', 'rows' => '5'));
    ?></div>
        </div><!-- row -->
        <div style="height:10px;"></div>
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'descricao_impacto'); ?></div>
            <div class='field'><?php echo $form->textArea($model, 'descricao_impacto', array('cols' => '100', 'rows' => '5'));
    ?></div>
        </div><!-- row -->
        <div style="height:10px;"></div>
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'descricao_mitigacao'); ?></div>
            <div class='field'><?php
                echo $form->textArea($model, 'descricao_mitigacao', array('cols' => '100', 'rows' => '5')
                );
                ?></div>
        </div><!-- row -->
                        <div style="height:10px;"></div>     
                                <fieldset class="visivel">
        <legend class="legendaDiscreta">Associar Processo ao Risco Pr�-Identificado</legend>
<div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'valor_exercicio'); ?></div>
                <div class='field'><?php
                	$model->valor_exercicio = date(Y);
                    echo $form->textField($model, 'valor_exercicio', array('maxlength' => 4,
                        'onkeypress' => "return mascaraInteiro2(event);",
                        'size' => 5,
                       'ajax' => array(
                           'type' => 'POST',
						   'beforeSend' => 'function(){
								$("#processo_riscopre").html("");
							}',
                           'success' => 'function(data){
											var dados = $("#processo_riscopre").html();
                							$("#processo_riscopre").html(dados + data);
											$("#processo_riscopre").multiselect("refresh");
                                        }',
						   'data'=>array('risco_pre_fk'=>$model->id, 'valor_exercicio' => 'js:$("input#RiscoPre_valor_exercicio").val()'),
                           'url' => CController::createUrl('Processo/CarregaProcessoPorExercicioAjax'))	
                    ));
                    ?> (Ex: <?php echo date("Y"); ?>)
                </div>
        </div><!-- row -->
                <div style="height:10px;"></div>
        
		<div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'processo_riscopre'); ?></div>
                <div class='field' id="div_processo">
                    <?php
                    if (!$model->isNewRecord){
	                    $dados_processo_selecionados = CHtml::listData(ProcessoRiscoPre::model()->with('processoFk')->findAll(array("condition" => 'risco_pre_fk = ' . $model->id . ' and valor_exercicio = ' . date(Y))), 'processo_fk', 'processoFk');
	                }
                    $dados_processo = CHtml::listData(Processo::model()->findAll(array("condition" => 'valor_exercicio = ' . date(Y))), 'id', 'nome_processo');
                    if (!sizeof($dados_processo)){
                    	$dados_processo = array(''=> 'Nenhum processo cadastrado em ' . date(Y));
                    }
                    
                    $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                        'model' => $model,
                        'dropDownAttribute' => 'processo_riscopre',
                        'data' => $dados_processo,
                        'dropDownHtmlOptions' => array(
                            'id' => 'processo_riscopre',
                        ),
                        'options' => array(
                            'selectedList' => 1,
                            'minWidth' => '250',
                            'filter' => true,
                            'multiple' => true,
                        ),
                        'filterOptions' => array(
                            'width' => 150,
                            'label' => Yii::t('application', 'Filtrar:'),
                            'placeholder' => Yii::t('application', 'digite aqui'),
                            'autoReset' => false,
                        ),
                    ));

                    // marca no select m�ltiplo os processos que est�o salvos
                    if (sizeof($dados_processo_selecionados) > 0) {
                    	echo "\n <script type='text/javascript'> { \n";
                    	foreach ($dados_processo_selecionados as $vetor) {
							echo "$(\"#processo_riscopre option[value='" . $vetor->id . "']\").attr('selected', 'selected'); \n";
                    	}	
                    	echo "}</script>";
                    }
                    
                    ?>
                </div>
            </div>
            </fieldset>
    </fieldset> 
    <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
        <span class="required">*</span>
        <?php echo Yii::t('app', 'are required'); ?>.
    </p>
    <div class="rowButtonsN1">
        <?php echo GxHtml::submitButton(Yii::t('app', 'Confirm'), array('class' => 'botao')); ?>
        <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('RiscoPre/index'), array('class' => 'imitacaoBotao')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div><!-- form -->