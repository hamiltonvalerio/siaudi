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
// verifica se relat�rio do cap�tulo j� foi homologado, bloqueando sua edi��o
if ($model->relatorio_fk) {
    $relatorio = Relatorio::model()->findByAttributes(array('id' => $model->relatorio_fk));
}
if ($relatorio->data_relatorio) {
    echo "<center><b>N�o � poss�vel editar este cap�tulo, pois ele pertence a um relat�rio j� homologado.</b></center>";
} else {

    $baseUrl = Yii::app()->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile($baseUrl . '/js/mask.js');
    $cs->registerScriptFile($baseUrl . '/js/Capitulo.js');


    if (!$model->isNewRecord) {
        ?>
        <div class="formulario" Style="width:70%;">
            <fieldset class="visivel">
                <legend class="legendaDiscreta"> Navega��o </legend> 
                <div style="width:100%;height:45px;">
                    <div class='tabelaListagemItensWrapper' style="width:170px;margin-left:10%;position:absolute;">
                        <a href="<?php echo '../../relatorio/admin/' . $model->relatorio_fk; ?>">
                            <img src="<?php echo Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/seta1.png"; ?>" border="0" align="left"></a>
                        &nbsp; &nbsp; 
                        <a href="<?php echo '../../relatorio/admin/' . $model->relatorio_fk; ?>">Voltar para relat�rio</a>
                    </div>

                    <div class='tabelaListagemItensWrapper' style="width:220px;margin-left:25%;position:absolute;">
                        <a href="<?php echo '../../capitulo/index?yt0=Consultar&Capitulo%5Brelatorio_fk%5D=' . $model->relatorio_fk; ?>">
                            <img src="<?php echo Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/seta2.png"; ?>" border="0" align="left"></a>
                        &nbsp; &nbsp; 
                        <a href="<?php echo '../../capitulo/index?yt0=Consultar&Capitulo%5Brelatorio_fk%5D=' . $model->relatorio_fk; ?>">Ver outros cap�tulos deste relat�rio</a>
                    </div>


                    <div class='tabelaListagemItensWrapper' style="width:170px;margin-left:44%;position:absolute;">
                        <a href="<?php echo '../../item/index?yt0=Consultar&Item%5Bcapitulo_fk%5D=' . $model->id; ?>">
                            <img src="<?php echo Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/seta3.png"; ?>" border="0" align="left"></a>
                        &nbsp; &nbsp; 
                        <a href="<?php echo '../../item/index?yt0=Consultar&Item%5Bcapitulo_fk%5D=' . $model->id; ?>">Ver itens deste cap�tulo</a>
                    </div>

                </div>
            </fieldset>
        </div>
    <?php } 


        $_jj = Yii::app()->getClientScript();
        Yii::app()->clientScript->registerScript('valida_numero_capitulo', "
    $('#Capitulo_numero_capitulo').on('blur', function() {
        $.ajax({
            type: 'POST',
            url: '".CController::createUrl("Capitulo/VerificaNumeroCapituloAjax")."',
            data: {relatorio_fk: $('#relatorio_fk').val(), numero_capitulo: $(this).val(), capitulo_id:'".$model->id."'}
        })
            .done(function(msg) {
            if (msg) {
                $('#Capitulo_numero_capitulo').val('');
            } 
        });
    }); ");
  
?>
    <div class="formulario" Style="width: 58%;">
        <?php
        include_once(Yii::app()->basePath . '/../js/relatorio/tiny-mce-js.jsp');
        $form = $this->beginWidget('GxActiveForm', array(
            'id' => 'capitulo-form',
            'enableAjaxValidation' => false,
        ));
        ?>    
        <?php echo $form->errorSummary($model); ?>
        <fieldset class="visivel">
            <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>

            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'relatorio_fk'); ?></div>
                <div class='field'><?php
                    if ($_GET["relatorio_fk"] && $model->isNewRecord) {
                        $model->relatorio_fk = $_GET["relatorio_fk"];
                    }
                    $list_relatoriofk = array(null => "Selecione") + GxHtml::listDataEx(Relatorio::model()->relatorio_por_especie_combo(), 'id', 'sigla_auditoria');
                    echo CHtml::dropDownList('relatorio_fk', $model->relatorio_fk, $list_relatoriofk, array('style' => 'width:130px;'));
                    ?>
                </div>
                <div style="height:10px;"></div>
                <div class='row'>
                    <div class='label'><?php echo $form->labelEx($model, 'numero_capitulo'); ?></div>
                    <div class='field'><?php echo $form->textField($model, 'numero_capitulo', array('maxlength' => 5, 'size' => 5,
                            /*'ajax' => array(
                                'type' => 'POST',
                                'url' => CController::createUrl('Capitulo/VerificaNumeroCapituloAjax'),
                                'update' => '#numero_capitulo',
                               ) */
                        )); ?></div>
                </div><!-- row -->
                <div style="height:10px;"></div>
                <div class='row'>
                    <div class='label'><?php echo $form->labelEx($model, 'nome_capitulo'); ?></div>
                    <div class='field'><?php echo $form->textField($model, 'nome_capitulo', array('maxlength' => 200, 'style' => 'width:527px;')); ?></div>
                </div><!-- row -->
                <div style="height:10px;"></div>            
                <div class='row'>
                    <div class='label'><?php echo $form->labelEx($model, 'descricao_capitulo'); ?></div>

                    <Table border="0" cellpadding="0 cellspacing=0"><tr><td>
                                <?php echo $form->textArea($model, 'descricao_capitulo', array('cols' => '80', 'rows' => '5', 'style' => 'margin-left:300px;', 'class' => 'tinymce_advanced')); ?>
                            </td></tr></table>
                </div><!-- row -->


        </fieldset>
        <p class="note">
            <?php echo Yii::t('app', 'Fields with'); ?>
            <span class="required">*</span>
            <?php echo Yii::t('app', 'are required'); ?>.
        </p>
        <div class="rowButtonsN1">
            <input type="hidden" name="inserir_capitulo" id="inserir_capitulo" value="0">            
            <input type="hidden" name="inserir_item" id="inserir_item" value="0">            
            <input type="button" name="text" value="Gravar e inserir outro cap�tulo" id="form_submit" onclick="envia_capitulo('capitulo');" class="botao" />
            <input type="button" name="text" value="Gravar e inserir item" id="form_submit" onclick="envia_capitulo('item');" class="botao" />            
            <?php echo GxHtml::submitButton(Yii::t('app', 'Gravar e sair'), array('class' => 'botao')); ?>
            <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Capitulo/index'), array('class' => 'imitacaoBotao')); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div><!-- form -->
<?php } ?>