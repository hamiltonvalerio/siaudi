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
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();    
    $cs->registerScriptFile($baseUrl.'/js/Resposta.js'); 
    $cs->registerScriptFile($baseUrl . '/js/mask.js');   
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'id'=>'Resposta'
            ));
    ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Consultar - Relat�rio</legend>
        <div class='row'>
        		<div class='label'><?php echo $form->label($model, 'valor_exercicio'); ?></div>
        		<div class='field'>
                <?php
                    echo $form->textField($model, 'valor_exercicio', array('maxlength' => 4, 'size' => 25,
                    'onkeypress' => "return mascaraInteiro2(event);",
                    'ajax' => array(
                    'type' => 'POST',
                    'url' => CController::createUrl('resposta/CarregaRelatorioPorExercicioAjax'),
                    'update' => '#Resposta_relatorio_fk',)));
                ?>
                (Ex: <?php echo date("Y");?>)
                </div>    
        </div>
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'relatorio_fk'); ?></div>
            <div class='field'>
                <?php 
//                $resultado = Resposta::model()->FollowUp_combo();
                echo $form->dropDownList($model, 'relatorio_fk', array(), 
                                        array('prompt' => Yii::t('app', ' Selecione'),
                                              'onchange'=>'valida()')); ?>
        	</div>
        </div>                                
    </fieldset>
    
    <?php $this->endWidget(); ?>
</div><!-- search-form -->