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
    $auditor_fk = (integer) $_GET["auditor"];
    $relatorio_fk = (integer)  $_GET["relatorio"];
    $unidade_administrativa_fk = (integer) $_GET["sureg"];
    $login= Yii::app()->user->login;
    
    $visualizar_pdf = $_GET["visualizar_pdf"];
    /* se a avalia��o abriu ap�s a tentativa de visualizar
     * o PDF do relat�rio (em nova janela, sem barra de menu),
     * ent�o oculta o layout e exibe somente o conte�do da avalia��o. 
     */
      
    if ($visualizar_pdf==1){
        $this->layout=false;
        $baseUrl = Yii::app()->baseUrl; 
        
        
?>
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=<?php echo Yii::app()->charset ?>" />
            <meta name="language" content="pt-BR" />

            <!-- blueprint CSS framework -->
            <!--[if lt IE 8]>
                    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
                    <![endif]-->


            <!-- link rel="SHORTCUT ICON" href="<?php echo Yii::app()->request->baseUrl; ?>/images/Logo.ico"/ -->
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/estiloGeral.css" />
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/css/estiloEspecifico.css" />
            <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/themes/estilo_yii.css" />

            <?php
            $_jj = Yii::app()->getClientScript();
            $_jj->registerScriptFile(Yii::app()->request->baseUrl . '/js/menu.js');

            if (Yii::app()->controller->id!='paint'){
            $_jj->registerScriptFile(Yii::app()->request->baseUrl . '/js/init.js');
            }

            $_jj->registerScriptFile(Yii::app()->request->baseUrl . '/js/lib/jquery.maskedinput-1.1.4.pack.js');
            ?>

            <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        </head>
        <body>
<?php 
    }
        
        
    // --------------------------------
    //  Verifica��es de seguran�a para
    //  negar o acesso � avalia��o
    //  --------------------------------
   
    // s� abre a tela para cadastrar novas avalia��es
    // (sem possibilidade de abrir a tela com as notas j� carregadas)
    if($model->id) { $acesso_negado=1; }

    // verifica se prazo dos 5 dias �teis j� expirou 
    /*
    $Relatorio = Relatorio::model()->findByPk($relatorio_fk);                
    $data_final = Feriado::model()->DiasUteis($Relatorio->data_finalizado,5);
    $data_final = explode("/",$data_final); 
    $data_final = $data_final[2].$data_final[1].$data_final[0];
    $hoje = date("Ymd");
    if ($hoje>$data_final){ $acesso_negado=1; $motivo="Prazo para avalia��o neste relat�rio expirou."; }
*/
    // verifica se avalia��o j� existe no banco (de acordo com relat�rio, Unidade Regional e auditor)
    $Avaliacao = Avaliacao::model()->findByAttributes(array('relatorio_fk'=>$relatorio_fk,'unidade_administrativa_fk'=>$unidade_administrativa_fk,'usuario_fk'=>$auditor_fk));   
    if (sizeof($Avaliacao)>0){ $acesso_negado=1; $motivo="Avalia��o j� preenchida.";}    
    
    // verifica se usu�rio passado no GET realmente tem acesso 
    // a este relat�rio de acordo com a sureg
    $RelatorioAcesso =  RelatorioAcesso::model()->findByAttributes(array('relatorio_fk'=>$relatorio_fk,'nome_login'=>$login,'unidade_administrativa_fk'=>$unidade_administrativa_fk));
    if (sizeof($RelatorioAcesso)==0){ $acesso_negado=1; $motivo="Avalia��o inexistente ou sem permiss�o de acesso."; }
    
    // verifica se o auditor passado no GET realmente pertence a este relatorio
    $RelatorioAuditor = RelatorioAuditor::model()->findByAttributes(array('relatorio_fk'=>$relatorio_fk,'usuario_fk'=>$auditor_fk));    
    if (sizeof($RelatorioAuditor)==0){ $acesso_negado=1; $motivo="Auditor n�o participa deste relat�rio."; }
     
     if ($acesso_negado) { 
         echo "<br><br><b><center>Acesso Negado.</b><br>{$motivo}</center>";
     } else { 
        
        if ($_GET["confirma"]==1){
            $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
        }
         
        $Auditor = Usuario::model()->findByPk($auditor_fk);
         if ($visualizar_pdf==1){ 
           echo" <div class='formulario' Style='width: 90%;'>";
         } else {
           echo" <div class='formulario' Style='width: 70%;'>";
         }
        

        $form = $this->beginWidget('GxActiveForm', array(
            'id' => 'avaliacao-form',
            'enableAjaxValidation' => false,
                ));
        
        echo $form->errorSummary($model); 
        ?>
        <font face="Verdana" size="2">
        Diante de previs�o no Manual de Auditoria e recomenda��o do Tribunal de Contas da Uni�o a t�tulo de boa pr�tica internacional, 
        solicitamos a Vossa Senhoria o preenchimento do 
        Formul�rio de Avalia��o dos Auditores, no intuito primeiro de que a Auditoria
        Interna possa melhor prestar seus servi�os em prol da melhoria da gest�o da Companhia.
        <br><Br>
        <center>
            <b>Auditor avaliado:</b> <?php echo $Auditor->nome_usuario; ?> <br><br>
        </center>
        </font>


        <fieldset class="visivel">
            <legend class="legendaDiscreta"> <?php echo($model->isNewRecord ? 'Adicionar' : 'Atualizar'); ?> -  <?php echo $model->label(); ?></legend>
            <?php 
                // verifica se existem crit�rios de avalia��o no exerc�cio atual
                $exercicio = date("Y");
                $Criterios= AvaliacaoCriterio::model()->findAll("valor_exercicio=".$exercicio." ORDER BY numero_questao ASC");            
                if (sizeof($Criterios)==0){
                    echo "<br>";
                    echo "N�o h� crit�rios de avalia��o cadastrados para o exerc�cio de {$exercicio}.
                          Entre em contato com a ger�ncia da Auditoria para dar continuidade � avalia��o.";
                    echo "<br><br><br>";
                    // envia e-mail para gerentes e chefe de auditoria
                    $envia_email= AvaliacaoCriterio::model()->VerificaAvaliacaoCriterio();
                } else {

                    // escreve javascript para soma din�mica dos campos
                  echo " <script language='JavaScript'>  
                      
                        function valida_envio() {
                            if (";
                            //form.Relatorio_id.value
                            foreach ($Criterios as $vetor){
                              $condicao.= "document.getElementById('Nota_Criterio[{$vetor->id}]').value=='' || ";
                            }
                            $condicao = substr($condicao,0,-3);
                            echo $condicao;
                            echo "){
                                alert('Informe todas as notas.');
                                envio=0;
                            } else {
                                document.getElementById('botao_submit').innerHTML='Aguarde...';                            
                                document.forms['avaliacao-form'].submit();
                            }
                        }

                      function soma_nota (valor){
                        valor = document.getElementById('Nota_Criterio['+valor+']').value;
                        valor = parseFloat(valor); 
                        if (isNaN(valor)==true){  
			   	return 0; } 
        		   else {
                            return valor;
                        }                        
                      }
                       function soma(input){ \n                          
                            campo =document.getElementById('Nota_Criterio['+input+']'); 
                            inteiro = campo.value.replace(/[^1-5]/g,''); //somente n�meros
                            campo.value=inteiro;
                        
                        
                            nota = campo.value;
                             nota = parseFloat(nota); 
                            if (nota <1 || nota >5) {  
                              alert ('A nota deve estar no intervalo entre 1 e 5.'); 
                              campo.value='';
                              campo.focus();
                            }   
                         var total=0;
                         var total=";                     
                        foreach ($Criterios as $vetor){                       
                          echo "(soma_nota({$vetor->id})) +";
                        }
                       echo " 0; \n
			var total = total/".sizeof($Criterios)."; \n
                        var total = total.toFixed(2); \n
                        if (total!=0) {
                            document.getElementById('media_total').innerHTML='<b>'+total+'</b>';
                        } else {
                           document.getElementById('media_total').innerHTML='';
                        }
                        } \n
                       </script>"; 
                    
                    
            echo "
                       <p align=right> 5 - �timo; 4 - Muito bom; 3 - Bom; 2 - Regular; 1 - Ruim.</span>
                <table class='tabelaListagemItensYii' style='width:100%;'>
                   <thead>
                   <tr>
                        <th id='criterio-grid_c0'>N�</th>
                        <th id='criterio-grid_c0'>Crit�rio</th>
                        <th id='criterio-grid_c0'>Nota <br>(1 a 5)</th>
                   </tr>
                   </thead>
                   <tbody>";
                    foreach ($Criterios as $vetor){
                        $class=($class=="odd")? "even":"odd";
                        echo "<tr class='".$class."'>
                        <td width='30' align=center>".$vetor->numero_questao."</td>
                        <td>".$vetor->descricao_questao."</td>
                        <td width='50' align=center>";
                        
                        echo "<input maxlength='1' size='2' name='Nota_Criterio[".$vetor->id."]'  
                       id='Nota_Criterio[".$vetor->id."]'  type='text' onblur='soma({$vetor->id})'
                       /><span class='required'>*</span> "; 
                             
  
                        echo "</td>                            
                              </tr>";                                 
                    }
                $class=($class=="odd")? "even":"odd";                    
                echo "
                        <tr class='".$class."'>
                        <td colspan=2 align=right><b>M�dia</b></td>
                        <td align=center><div id='media_total'></div></td>
                        </tr>
                    </tbody>
                    </table><br><br>";
                }
            ?>   
            <table align="center">
            <tr>
                <td valign="top"><?php echo $form->labelEx($model, 'observacao'); ?>:</td>
                <td valign="top"><?php echo $form->textArea($model,'observacao', array('cols'=>'100', 'rows'=>'5', 'maxlength'=>2056)); ?></td>
            </tr>    
            </table>
        </fieldset>
        <?php 
        if ($visualizar_pdf){ echo "<input type='hidden' name='visualizar_pdf' value=1>"; } 
        if (sizeof($Criterios)>0){ ?>
        <p class="note">
            <?php echo Yii::t('app', 'Fields with'); ?>
            <span class="required">*</span>
            <?php echo Yii::t('app', 'are required'); ?>.
        </p>
        
        <div id="botao_submit" style="text-align:right;">
            <div class="rowButtonsN1">
                <input type="button" name="text" value="Confirmar" id="form_submit" onclick="valida_envio();" class="botao" />
                <?php 
                  if (!$visualizar_pdf){ 
                    echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('/Manifestacao'), array('class' => 'imitacaoBotao'));
                  }  
                ?>            
            </div>
        </div>
        <?php } $this->endWidget(); ?>
    </div><!-- form -->
<?php 
} 
if ($visualizar_pdf){ echo "<br><br>"; } 
?>