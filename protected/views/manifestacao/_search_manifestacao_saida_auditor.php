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
<script type="text/javascript" src="../js/ManifestacaoSaida.js"></script>
<div class="formulario" Style="width: 70%;">
    
    <?php
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'id' =>'manifestacaoSaida'
            ));

    ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Consultar -  Relat�rio</legend>
      
        <div class='row'>
            <div class='label'><?php echo $form->label($model, 'tipo_relatorio'); ?> *</div>
            <div class='field'>
                <?php echo $form->dropDownList($model, 'tipo_relatorio', array('1'=>'N�o Homologados','2'=>'Homologados'), 
                        array('prompt' => Yii::t('app', ' Selecione'), 
                            'style' => 'width:200px;',
                            'ajax' => array(
                            'type' => 'POST',
                            'url' => CController::createUrl('Manifestacao/CarregaManifestacaoSaidaAjax'),
                            'update' => '#Manifestacao_id') 
                         )
                        ); ?>
            </div>
        </div>                                
            <div class='row'>
                <div class='label'><?php echo $form->labelEx($model, 'id'); ?> *</div>
                <div class='field'>
                    <?php
                     echo $form->dropDownList($model, 'id', array(), array(
                         'style' => 'width:200px;',
                         'onchange'=>'valida()'
                         )); ?>
                </div>          


                

    </fieldset>

    <p class="note">
        <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
    </p>
    <?php $this->endWidget(); ?>
</div><!-- search-form -->



