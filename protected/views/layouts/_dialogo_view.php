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
<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/dialogs.js'); ?>

<?php
$this->beginWidget('application.common.extensions.jui.EDialog', array(
    'name' => 'dialog_visualizar',
    'htmlOptions' => array('title' => 'Visualizar'),
    'options' => array(
        'autoOpen' => false,
        'modal' => true,
        'title' => 'Visualizar Registro',
        'height' => 400,
        'width' => 600,
    ),
    'buttons' => array(
        "Fechar" => 'function(){$(this).dialog("close");}',
    ),
    'theme' => 'base',
        )
);
?>
<span id="visualizar_reg"> </span>
<?php $this->endWidget('application.common.extensions.jui.EDialog'); ?>


<?php
$this->beginWidget('application.common.extensions.jui.EDialog', array(
    'name' => 'dialog_excluir',
    'htmlOptions' => array('title' => 'Excluir Registro'),
    'options' => array(
        'autoOpen' => false,
        'modal' => true,
        'title' => 'Confirma a exclus�o do registro',
        'height' => 400,
        'width' => 600,
    ),
    'buttons' => array(
        "Excluir" => 'function(){
           $.post($("#ctrl_excluir").val(), { id:$("#id_excluir").val()  },
               function(data) {
                    $("#dialog_excluir").dialog("close");
                    $.fn.yiiGridView.update($("#id_grid_excluir").val());
                    $("#mensagens").html(data);
           });
        }',
        "Fechar" => 'function(){$(this).dialog("close");}',
    ),
    'theme' => 'base',
        )
);
?>

<?php echo GxHtml::hiddenField('id','', array('id'=>'id_excluir')) ?>
<?php echo GxHtml::hiddenField('ctrl_excluir','', array('id'=>'ctrl_excluir')) ?>
<?php echo GxHtml::hiddenField('ctrl_excluir','', array('id'=>'id_grid_excluir')) ?>
<span id="excluir_reg"></span>

<?php $this->endWidget('application.common.extensions.jui.EDialog'); ?>