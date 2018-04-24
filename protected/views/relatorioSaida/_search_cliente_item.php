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
<script type="text/javascript" src="js/RelatorioSaida2.js"></script>
<script type="text/javascript">
     $(function()
    {
        
        <?php 
            $data = array('Relatorio' => array('tipo_relatorio' => 2)); //Trazer somente os relat�rio homologados 
            echo(CHtml::ajax(array
            (
                'type' => 'POST',
                'data' => $data,
                'url' => array('RelatorioSaida/CarregaRelatorioSaidaAjax'),
                'update' => '#Relatorio_id',
            )));
        ?>
    });    
</script>

<div class="formulario" Style="width: 70%;">

    <?php
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'id' => 'relatorioSaida'
    ));
    ?>
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Consultar -  <?php echo $model->label(); ?></legend>  
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'relatorio_fk'); ?> *</div>
            <div class='field'>
                <?php
                echo $form->dropDownList($model, 'id', array(), array(
                    'style' => 'width:200px;',
                    'onchange' => 'valida()'
                ));
                ?>
            </div> 
        </div>
    </fieldset>

    <p class="note">
        <b><?php echo Yii::t('app', 'Fields with'); ?> <span class="required">*</span> <?php echo Yii::t('app', 'are required'); ?>.</b>
    </p>
    <br><br>
    <font size="2">
    <b>OBS.:   N�O � PERMITIDO SALVAR, COPIAR OU IMPRIMIR O RELAT�RIO DE AUDITORIA.</b><br>
    TODAS AS OPERA��ES REALIZADAS NO SISTEMA S�O AUDITADAS.</font>

    <?php $this->endWidget(); ?>
</div><!-- search-form -->



