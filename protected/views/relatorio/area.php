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
<style type="text/css">
.fixed-top {
    opacity: 1;
    position: fixed;
    top: 5;
    right:70px;
}
</style>
<script type="text/javascript">
    function AdicionaElemento(e) {
        var s = document.getElementById('unidade_administrativa_fk_popup');
        var o = document.createElement('option');
        if (e.checked) {
            o.value = e.id;
            o.textContent = e.nextSibling.innerHTML;
            o.selected = "selected";
            s.appendChild(o);
        } else {
            removeOption(e.id);
        }
        $("#unidade_administrativa_fk_popup").multiselect('refresh');
    }

    function removeOption(value)
    {
        var s = document.getElementById('unidade_administrativa_fk_popup');
        var i;
        for (i = s.length - 1; i >= 0; i--) {
            if (s.options[i].value == value) {
                s.remove(i);
            }
        }
    }

    function procurar(){
    	window.find(this.textoPesquisa.value,false,true,true,true,false,false); 
    	return false;
    }

    $(document).ready(function() {
        
        var value = window.parent.parent.document.getElementById('area_fk').innerHTML;
        $("#unidade_administrativa_fk_popup").html(value);
        $("#unidade_administrativa_fk_popup").multiselect('refresh');
        $("#unidade_administrativa_fk_popup").multiselect("checkAll");
        
        var options = value;
        options.replace(/\n/gi,"#n#"); //express�o para retirar quebras de linha
        options = options.match(/(value)=("[^"]*")/gi); //express�o para obter somente o value dos options
        
        for (var index in options){
            var id = options[index].replace(/[^\d]+/g, ""); //remove tudo o que n�o � n�mero
            var li = document.getElementById("campo_" + id);
            li.childNodes[0].checked = true;
        }

        $("#unidade_administrativa_fk_popup").multiselect({
            click: function(e) {
                if (!e.target.checked) {
                    var value = e.target.value;
                    removeOption(value);
                    $("#unidade_administrativa_fk_popup").multiselect('refresh');
                    $('input[id=' + value + ']').attr('checked', false);
                }

            }
        });
    });

    function fechar() {
        try {
//            alert(window.parent.parent.carregaAreas('teste'));
            var unidade_administrativa_fk_popup = $('#unidade_administrativa_fk_popup').html();
            window.parent.parent.carregaAreas(unidade_administrativa_fk_popup);
            window.parent.close();
        }
        catch (err) {
            alert(err);
        }
    }
</script>
<div id="popupBoxFundo">&nbsp;<iframe src="javascript:false;"></iframe></div>
<div id="popupBox">
    <div>
        <span class="indicadorCarregando" style="padding-bottom: 15px;">&nbsp;</span>
        Aguarde, o sistema est� processando os dados.
    </div>
</div>
<div class="formulario" Style="width: 70%;">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/estiloGeral.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/estiloEspecifico.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/estilo_yii.css" />
    <?php
    $cs = Yii::app()->getClientScript();
    $cs->registerCoreScript('treeview');
    $baseUrl = $cs->getCoreScriptUrl();
    $cs->registerCSSFile(Yii::app()->request->baseUrl . '/themes/' . Yii::app()->params['tema'] . '/css/estiloGeral.css');
    $cs->registerCSSFile(Yii::app()->request->baseUrl . '/themes/' . Yii::app()->params['tema'] . '/css/estiloEspecifico.css');
    $cs->registerCSSFile(Yii::app()->request->baseUrl . '/themes/css/estilo_yii.css');
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'selecionar-area-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => true,
    ));
    ?>
    <fieldset class="visivel" style="width: 100;  position: relative;">
        <legend class="legendaDiscreta">Menu</legend>
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'area'); ?></div>
            <div class='field'>
                <?php
//                 $areas = CHtml::listData(UnidadeAdministrativa::model()->findAll(), 'id', 'sigla');
                $areas = array();
                $form->widget('ext.EchMultiSelect.EchMultiSelect', array(
                    'model' => $model,
                    'dropDownAttribute' => 'unidade_administrativa_fk',
                    'data' => $areas,
                    'dropDownHtmlOptions' => array(
                        'id' => 'unidade_administrativa_fk_popup',
                        'multiple' => true,
                    ),
                    'options' => array(
                        'selectedList' => 5,
                        'minWidth' => '410',
                        'filter' => true,
                    ),
                    'filterOptions' => array(
                        'width' => 150,
                        'label' => Yii::t('application', 'Filtrar:'),
                        'placeholder' => Yii::t('application', 'digite aqui'),
                        'autoReset' => false,
                    ),
                ));

                // marca no select multiplo as op��es previamente salvas

                if (is_array($areas)) {
                    echo "\n <script type='text/javascript'> { \n";
                    foreach ($areas as $vetor) {
                        echo "$(\"#unidade_administrativa_fk_popup option[value='" . $vetor->id . "']\").attr('selected', 'selected'); \n";
                    }
                    echo "}</script>";
                }
                ?>
            </div>
        </div><!-- row -->
        <div class="row">
	        <div class='field'>
	                
	    	</div>
        </div>
    </fieldset>
    <fieldset class="visivel fixed-top" style="position: fixed; z-index: 2000;">
        <legend class="legendaDiscreta">Pesquisar</legend>
        <input type="text" name="textoPesquisa" id="textoPesquisa" />
        <input type="button" name="text" value="Procurar" onclick="procurar();" class="botao" />
    </fieldset>
    <div class="clear"></div> 
    <div>
        <strong>Unidade Administrativa (Lota��o)</strong>
        <?php
        $form->widget('CTreeView', array(
            'data' => $dataTree,
//            'url' => array('Relatorio/AjaxFillTree'),
            'animated' => 'true', //remember must giving quote for boolean value in here
// 			'collapsed' => 'true', //remember must giving quote for boolean value in here
            'htmlOptions' => array(
                'class' => 'treeview treeview-gray', //there are some classes that ready to use
            ),
        ));
        ?>
        <?php
        $this->endWidget();
        ?>
    </div>
</div>
