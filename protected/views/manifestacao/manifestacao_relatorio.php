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
    // Verifica se usu�rio tem permiss�o para manifestar
    // neste relat�rio (ou se o prazo j� expirou)
    $relatorios = Manifestacao::model()->RelatorioManifestacaoPrazo();
    foreach ($relatorios as $vetor){
        if($vetor[id]==$id){
            $autorizado=1;
        }
    }
    if(!$autorizado){ echo "<br><br><div align=center><b>Acesso negado</b></div>"; } 
    else {
    
     // Verifica se esta Unidade Auditada j� se manifestou para este relat�rio
     // (mesmo que o siaudi_cliente ou chefe tenha sido trocado)
       $Manifestacao_realizada = Manifestacao::model()->VerificaManifestacaoAuditado($id);
       if($Manifestacao_realizada){
            echo "<br><br><div align=center><b>A manifesta��o deste relat�rio j� foi enviada. </b></div>";            
       } else {
           

     // Verifica se existe alguma avalia��o de auditor
     // a ser feita antes de realizar a manifesta��o
       $login= Yii::app()->user->login;
       $RelatorioAcesso=  RelatorioAcesso::model()->findByAttributes(array('relatorio_fk'=>$id,'nome_login'=>$login));
       $sureg = $RelatorioAcesso->unidade_administrativa_fk;
       $avaliacao_auditor = Avaliacao::model()->VerificaAvaliacao($id,$sureg);
       
       // vari�vel $avaliacao_auditor cont�m o ID do auditor a 
       // ser avaliado ou vazio caso todas as avalia��es j� tenham
       // sido realizadas
       if ($avaliacao_auditor) {
           $confirma = ($_GET["confirma"]) ? "&confirma=1":"";
           header("Location:../../Avaliacao/admin/?relatorio={$id}&sureg={$sureg}&auditor={$avaliacao_auditor}{$confirma}");
           exit; 
       } else {
           
        
        if ($_GET["confirma"]==1){
            $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
        }           
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();    
    $cs->registerScriptFile($baseUrl.'/js/Manifestacao.js');        
    $Relatorio= Relatorio::model()->findbyAttributes(array('id'=>$id));        
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'manifestacao-form',
        'enableAjaxValidation' => false,
            ));
    ?>    
<?php echo $form->errorSummary($model); ?>
            <div style="text-align:center;width:800px;">Relat�rio ID:
             <?php $Especie_auditoria = EspecieAuditoria::model()->findbyAttributes(array('id'=>$Relatorio->especie_auditoria_fk));
                   echo $id." -  " . $Especie_auditoria->sigla_auditoria;
             ?>
            </div><br><br>
   
    <fieldset class="visivel">
        <legend class="legendaDiscreta" style="color:black;font-size:12px;"> <b>Esta manifesta��o pode ser enviada at� <?php echo Feriado::model()->DiasUteis($Relatorio->data_finalizado,5);?> </b></legend>
        <br><br><div style="font-size:13px;text-align:justify">
        A manifesta��o � um importante mecanismo de controle. No caso de n�o manifesta��o em tempo h�bil - 5 dias �teis -, considerar-se-�o pertinentes os fatos relatados pela equipe de auditores internos. 
        Os fatos abordados no Relat�rio de Auditoria <b>Id Relat�rio: <?php echo $id;?></b> est�o condizentes com os objetivos tra�ados pela equipe de auditores internos na reuni�o de abertura, com os trabalhos de campo efetivamente realizados e na reuni�o de encerramento? 
        </div>

        <div style='height:10px;'></div>
        <div class='row'>
            <div class='label'>Resposta</div>
            <div class='field'><input id='resposta_sim' name='resposta' type='radio' value="1" /><label for="resposta_sim"> Sim </label> 
                                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                              <input id='resposta_nao' name='resposta' type='radio' value="2" /><label for="resposta_nao">N�o </label><br />
            </div>
        </div><!-- row -->        

        <div style='height:10px;'></div>        
        <div class='row' id='descricao_manifestacao' style='display:none'>
            <div class='label'><?php echo $form->labelEx($model, 'descricao_manifestacao'); ?></div>
            <div class='field'><?php echo $form->textArea($model, 'descricao_manifestacao',array('cols'=>100,'rows'=>8)); ?></div>
        </div><!-- row -->        

    </fieldset>
    <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
        <span class="required">*</span>
    <?php echo Yii::t('app', 'are required'); ?>.
    </p>
    <div class="rowButtonsN1">
         <input type="hidden" name="relatorio_fk" value="<?php echo $id;?>">
         <div id="botao_submit">
             <a class="imitacaoBotao" href="javascript:valida_envio();">Confirmar</a>
        <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('/Manifestacao'), array('class' => 'imitacaoBotao')); ?>
         </div>             
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->

<?php 
        }
    }
}
?>