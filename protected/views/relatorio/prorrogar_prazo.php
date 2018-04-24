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
    $cs->registerScriptFile($baseUrl . '/js/prorrogar_prazo.js');
    
    
    $perfil = strtolower(Yii::app()->user->role);
    $perfil = str_replace("siaudi2","siaudi",$perfil);
    if($perfil!="siaudi_chefe_auditoria"){
        echo "<br><br><b><center>";
        echo "Funcionalidade dispon�vel apenas para o Chefe de Auditoria.";
        echo "</center></b>";
    } else {
        
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();    
    $cs->registerScriptFile($baseUrl.'/js/Relatorio.js');        
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
        'id' =>'prorrogar_prazo'
            ));
        

?>
<div class="formulario" Style="width: 70%;">
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Selecionar <?php echo $model->label(); ?> </legend>
        <div class='row'>
            <div class='label'>N� Relat�rio: <span class="required">*</span></div>
            <div class='field'><?php
            echo $form->dropDownList($model, 'id', Relatorio::model()->ComboRelatorioHomologado(), array('prompt' => Yii::t('app', ' Selecione'), 'style' => 'width:200px;')); ?>
       </div>      
            <div style="height:10px;"></div>
        <div class='row'>
            <div class='label'>Prorrogar por: <span class="required">*</span></div>
            <div class='field'><?php echo $form->textField($model, 'dias_uteis', array('style' => 'width:40px;','maxlength'=>4)); ?> (dias �teis)</div>
        </div><!-- row -->            
    </fieldset>
        <p class="note">
            <?php echo Yii::t('app', 'Fields with'); ?>
            <span class="required">*</span>
            <?php echo Yii::t('app', 'are required'); ?>.
        </p>
        <div class="rowButtonsN1">
            <a class="imitacaoBotao" href="javascript:valida_envio();">Confirmar</a>
            <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Site/index'), array('class' => 'imitacaoBotao')); ?>
        </div>    
</div>
<?php 
 $this->endWidget(); 

} ?>