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
$perfil = strtolower(Yii::app()->user->role);
$perfil = str_replace("siaudi2", "siaudi", $perfil);
if ($perfil != "siaudi_chefe_auditoria") {
    echo "<br><br><b><center>";
    echo "Funcionalidade dispon�vel apenas para o Chefe de Auditoria.";
    echo "</center></b>";
} else {

    if ($_POST['Relatorio']['id']) {
        $id = $_POST['Relatorio']['id'];
        $Relatorio = Relatorio::model()->findByPk($id);
        $Relatorio_sureg = RelatorioSureg::model()->sureg_por_relatorio($id);
        echo "  <br><br>
            <div align='center'><b>Relat�rio homologado com sucesso</b>.<br><br>
                    <b>Unidade Auditada</b>: " . $Relatorio_sureg . " <br>
                    O Relat�rio passou a ser identificado como:<br><br>
                    <b>Relat�rio de Auditoria N�</b>: " . $Relatorio->numero_relatorio . "<br> 
                    <b>Data</b>: " . $Relatorio->data_relatorio . "<br><br>
            <a class='imitacaoBotao' href='javascript:history.back();'>Voltar</a>                        
            </div>";
    } else {

        $baseUrl = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseUrl . '/js/Relatorio.js');
        $form = $this->beginWidget('GxActiveForm', array(
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'post',
            'id' => 'homologar'
        ));
        ?>
        <div class="formulario" Style="width: 70%;">
            <fieldset class="visivel">
                <legend class="legendaDiscreta">Homologar <?php echo $model->label(); ?> </legend>
                <div class='row'>
                    <div class='label'>ID Relat�rio: <span class="required">*</span></div>
                    <div class='field'><?php echo $form->dropDownList($model, 'id', Relatorio::model()->ComboRelatorioFinalizado(), array('prompt' => Yii::t('app', ' Selecione'), 'style' => 'width:200px;')); ?>
                    </div>      
            </fieldset>
            <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
                <span class="required">*</span>
                <?php echo Yii::t('app', 'are required'); ?>.
            </p>
            <div class="rowButtonsN1">
                <a class="imitacaoBotao" href="javascript:valida_homologacao();">Confirmar</a>
        <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Site/index'), array('class' => 'imitacaoBotao')); ?>
            </div>    
        </div>
        <?php
        $this->endWidget();
    }
}
?>