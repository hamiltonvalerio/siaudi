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
    // tela de consulta dos relat�rios poss�veis para resposta
    // da manifesta��o
    if (!$id){ ?>
        <div class="formulario" Style="width: 70%;">

            <?php
            $baseUrl = Yii::app()->baseUrl; 
            $cs = Yii::app()->getClientScript();    
            $cs->registerScriptFile($baseUrl.'/js/Manifestacao.js');
            $form = $this->beginWidget('GxActiveForm', array(
                'action' => Yii::app()->createUrl($this->route),
                'method' => 'get',
                'id'=>'Manifestacao'
                    ));
            ?>
            <fieldset class="visivel">
                <legend class="legendaDiscreta">Consultar -  Manifesta��es por Relat�rio / Unidade Regional</legend>

                <div class='row'>
                    <div class='label'><?php echo $form->label($model, 'relatorio_fk'); ?></div>
                    <div class='field'>
                            <?php
                            $list_relatoriofk= array(null=> "Selecione") + GxHtml::listDataEx(Manifestacao::model()->RelatorioManifestacaoResponder(),'id','sigla_auditoria');
                            echo CHtml::dropDownList('relatorio_fk', null, $list_relatoriofk, 
                                                                    array('style' => 'width:130px;',
                                                                          'onchange'=>'valida_responder_manifestacao()')); 
                            ?>                
                    </div>
                </div> 
            </fieldset>

        <?php $this->endWidget(); ?>
        </div><!-- search-form -->

<?php 
        // tela com manifesta��o 
        }else{ 
?>

<div class="formulario" Style="width: 70%;">
    <?php
        // Verifica se manifesta��o (id) existe. 
       $Manifestacao = Manifestacao::model()->findByAttributes(array('id'=>$id));
       if(sizeof($Manifestacao)==0){
            echo "<br><br><div align=center><b>Manifesta��o n�o encontrada. </b></div>";            
            $manifestacao_erro=1;
       }        
       
     // Verifica se esta manifesta��o j� foi respondida .
     // (mesmo que o siaudi_cliente ou chefe tenha sido trocado)
       $Manifestacao_respondida = Manifestacao::model()->find('id='.$id.' AND descricao_resposta IS NOT NULL');       
       if($Manifestacao_respondida){
            echo "<br><br><div align=center><b>A manifesta��o deste relat�rio j� foi respondida. </b></div>";            
            $manifestacao_erro=1;
       } 
       
    // Se n�o houve erro, ent�o exibe a tela de resposta da manifesta��o
    if($manifestacao_erro==0){
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();    
    $cs->registerScriptFile($baseUrl.'/js/Manifestacao.js');        
    
    $Relatorio= Relatorio::model()->findByAttributes(array('id'=>$Manifestacao->relatorio_fk));        
    $Especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id'=>$Relatorio->especie_auditoria_fk));
    $UnidadeAdministrativa = UnidadeAdministrativa::model()->findByAttributes(array('id'=>$Manifestacao->unidade_administrativa_fk));
    $form = $this->beginWidget('GxActiveForm', array(
        'id' => 'manifestacao-form',
        'enableAjaxValidation' => false,
            ));
    echo $form->errorSummary($model); ?>

    <table align="center">
        <Tr>
            <td align="right"><b>Relat�rio ID: </b></td>
            <td><?php echo $Relatorio->id ." -  " . $Especie_auditoria->nome_auditoria;?></td>            
        </tr>
        <Tr>
            <td align="right"><b>Unidade Auditada: </b></td>
            <td><?php echo $UnidadeAdministrativa->nome;?></td>            
        </tr>        
    </table>

    <br><br>
   
    <fieldset class="visivel">
        <legend class="legendaDiscreta" style="color:black;font-size:12px;"> <b>Manifesta��o de <?php echo $Manifestacao->nome_login; ?> em <?php echo $Manifestacao->data_manifestacao;?> </b></legend>
        <br><div style="font-size:13px;text-align:justify">
          <?php echo $Manifestacao->descricao_manifestacao; ?>  
        </div>

        <div style='height:30px;'></div>        
        <div class='row'>
            <div class='label'><?php echo $form->labelEx($model, 'descricao_resposta'); ?><span class="required">*</span></div>
            <div class='field'><?php echo $form->textArea($model, 'descricao_resposta',array('cols'=>100,'rows'=>8)); ?></div>
        </div><!-- row -->        

    </fieldset>
    <p class="note">
        <?php echo Yii::t('app', 'Fields with'); ?>
        <span class="required">*</span>
    <?php echo Yii::t('app', 'are required'); ?>.
    </p>
    <div class="rowButtonsN1">
      <div id="botao_submit">
         <a class="imitacaoBotao" href="javascript:valida_envio_resposta();">Confirmar</a>
        <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('/Manifestacao/ResponderManifestacaoAjax'), array('class' => 'imitacaoBotao')); ?>
      </div>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->

<?php 
  }
}
?>