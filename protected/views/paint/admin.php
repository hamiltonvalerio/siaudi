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
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();

$form = $this->beginWidget('GxActiveForm', array(
    'id' => 'paint-form',
    'enableAjaxValidation' => false,
    'method' => 'POST'
        ));
?>

<div class="formulario" Style="width: 70%;">
    <?php echo $form->errorSummary($model); ?>

    <div style="height:10px"></div>

    <div class="formulario" style="width: auto">
        <fieldSet class="visivel">
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'valor_exercicio'); ?></div>
                <div class='field'><?php
                    if (!$_REQUEST["exercicio"]) {
                        echo "<form action='./' method='post' id='PaintFormValidar' name='RelatorioitemFormValidar' onsubmit='relatorio_item_validar()'>";
                        echo "<input type=hidden name='PaintFormValidar_dados' value=1>";
                        $form->widget('CMaskedTextField', array(
                            'model' => $model,
                            'attribute' => 'valor_exercicio',
                            'mask' => '9999',
                            'placeholder' => '_',
                            'htmlOptions' => array('maxlength' => 4,
                                'value' => $_GET['exercicio'])
                        ));
                        echo "&nbsp;";
                        if ($_REQUEST["consultar"] == 1) {
                            echo "<input type=hidden name='consultar' value=1>";
                        }
                        echo GxHtml::submitButton(Yii::t('app', 'Validar'), array('class' => 'botao'));
                        echo "</form>";
                    } else {
                        echo $_GET["exercicio"];
                    }
                    ?></div>
            </div><!-- row -->
            <center>
                <?php
                // verifica se usu�rio tem permiss�o de edi��o para o Paint
                $acesso_paint = Paint::model()->acesso_paint();

                if ($_GET["consultar"] && $acesso_paint) {
                    echo "<center>";
                    echo CHtml::link("Editar PAINT", $this->createUrl('Paint/admin?exercicio=' . $_GET['exercicio']), array('class' => 'imitacaoBotao'));
                    echo "</center><br>";
                }
                ?>
            </center>
        </fieldSet>
    </div>

    <?php
    $limite_inferior = Yii::app()->params['limite_inferior_exercicio'];
    if ($_GET["exercicio"] >= $limite_inferior) {
        $cs->registerCssFile($baseUrl . '/js/jquery_easyui/themes/default/easyui.css');
        $cs->registerCssFile($baseUrl . '/js/jquery_easyui/themes/icon.css');
        $cs->registerCssFile($baseUrl . '/css/default-calendar.css');
        $cs->registerScriptFile($baseUrl . '/js/jquery_easyui/jquery.easyui.min.js');

        include_once(Yii::app()->basePath . '/../js/paint/item-template-javascript.js');
        include_once(Yii::app()->basePath . '/../js/paint/tree-js.jsp');
        include_once(Yii::app()->basePath . '/../js/paint/tiny-mce-js.jsp');

        // verifica se usu�rio tem permiss�o de edi��o para o Paint
        $acesso_paint = Paint::model()->acesso_paint();
        $paint = Paint::model()->FindAllByAttributes(array('valor_exercicio' => $_REQUEST["exercicio"]));

        if (!$acesso_paint && sizeof($paint) == 0) {
            echo "<br><br>O PAINT n�o foi cadastrado neste exerc�cio.";
            echo "<div class='rowButtonsN1'>";
            echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Paint/index'), array('class' => 'imitacaoBotao'));
            echo "</div>";
        } else {

            if ($_GET["consultar"]) {
                echo "<div style='position:absolute; left:360px; height:550px; width:780px; font-size:50px; z-index:100; '>&nbsp;</div>";
                $topo = ($acesso_paint) ? "826" : "789";
                echo "<div style='position:absolute; top:" . $topo . "px; left:1137px; height:19px; width:20px; font-size:50px; z-index:101; background-color:rgb(241, 241, 241);'>&nbsp;</div>";
            }
            ?>

            <div class="formulario" style="width: auto">

                <fieldSet class="visivel">
                    <legend class="legendaDiscreta"> Conte�do do PAINT </legend>

                    <table style="width:100%" border="0">
                        <tr>

                            <td style="width:300px;" valign="top" rowspan="3">


                                <div id="p" class="easyui-panel" title="" style="width:300px;height:550px;padding:10px;"  
                                     data-options="closable:false, collapsible:false,minimizable:false,maximizable:false" >  


                                    <div style="margin:10px;">

                                        <a href="javascript:void(0)" onclick="$('#treeViewItens').tree('expandAll');" styleClass="buttonLink" title="Expandir todos">
                                            <img src="<? echo $baseUrl; ?>/images/expand_all.gif" style="display:inline;border:0;" />
                                        </a>

                                        <a href="javascript:void(0)" onclick="$('#treeViewItens').tree('collapseAll');" styleClass="buttonLink" title="Recolher todos">
                                            <img src="<? echo $baseUrl; ?>/images/collapse_all.gif" style="display:inline;border:0;" />
                                        </a>
                                        <?php if (!$_GET["consultar"] == 1) { ?>
                                            <a href="javascript:void(0)" onclick="treeAppendItem($('#treeViewItens'))" styleClass="buttonLink" title="Adicionar Item">
                                                <img src="<? echo $baseUrl; ?>/images/adicionar_item.gif" style="display:inline;border:0;" />
                                            </a>

                                            <a href="javascript:void(0)" onclick="treeAppendItemFilho($('#treeViewItens'))" styleClass="buttonLink" title="Adicionar Sub-Item">
                                                <img src="<? echo $baseUrl; ?>/images/adicionar_subitem.gif" style="display:inline;border:0;" />
                                            </a>

                                            <a href="javascript:void(0)" onclick="treeRemoveItem($('#treeViewItens'))" styleClass="buttonLink" title="Remover Item">
                                                <img src="<? echo $baseUrl; ?>/images/excluir.gif" style="display:inline;border:0;" />
                                            </a>					
                                        <?php } ?>
                                    </div>
                                    <hr>

                                    <ul id="treeViewItens" animate="true" lines="false" dnd="true" class="treeview_template"> </ul>

                                    <div id="menu_contexto" class="easyui-menu" style="width:120px;">
                                        <div onclick="treeAppend($('#treeViewItens'))" iconCls="icon-add">Adicionar item</div>
                                        <div onclick="treeRemove($('#treeViewItens'))" iconCls="icon-remove">Remover item</div>
                                        <div class="menu-sep"></div>
                                        <div onclick="expand()">Expandir</div>
                                        <div onclick="collapse()">Recolher</div>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <tr>							
                            <td valign="top" style="height:100%">
                                <div id="divTitulo" style="display:inline">
                                    <span style="font-size:11px"> <b> Titulo: </b> </span>
                                    <textarea name="titulo" id="titulo" class="tinymce_simple"  style="width:100%;" ></textarea>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div id="divTexto" style="display:inline">
                                    <span style="font-size:11px"> <b> Texto: </b> </span>
                                    <textarea name="texto" id="texto" class="tinymce_advanced" style="width:500px; height:800px;"> </textarea>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="rowButtonsN1">
                        <?php if (!$_GET["consultar"]) { ?>
                            <input type="hidden" name="paint_sair" id="paint_sair" value="0">
                            <input type="hidden" name="exercicio" value="<?php echo $_GET["exercicio"]; ?>">
                            <input type="hidden" name="paint" id="paint" value="">
                            <input type="button" name="text"						
                                   value="Gravar"
                                   id="form_submit" onclick="envia_paint_json(0);" class="botao" />

                            <input type="button" name="text"						
                                   value="Gravar e sair"
                                   id="form_submit" onclick="envia_paint_json(1);" class="botao" />                                        


                            <input type="button" name="text"
                                   value="Adicionar atributo"                                               
                                   id="form_submit" onclick="showAtributos();" class="botao" />

                            <input type="button" name="text"
                                   value="Gravar e Gerar PDF"
                                   id="form_submit" onclick="envia_paint_json(2);" class="botao" />   
                            <?
                        }
                        if ($_GET["consultar"] == 1) {
                            echo CHtml::link(Yii::t('app', 'Gerar PDF'), $this->createUrl('Paint/ExportarPDFAjax?exercicio=' . $_GET['exercicio']), array('class' => 'imitacaoBotao')) . "&nbsp; ";
                        }

                        echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Paint/index'), array('class' => 'imitacaoBotao'));
                        ?>   

                    </div>
                </fieldSet>
            </div>
            <?
        }
        include_once(Yii::app()->basePath . '/../js/paint/item-template-atributos.jspf');
        include_once(Yii::app()->basePath . "/../js/paint/item-template-anexos.jspf");
    }
    $this->endWidget();
    ?>
</div><!-- form -->