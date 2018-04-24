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
<?php echo $this->renderPartial('/layouts/_dialogo_view'); ?>

<?php
    // carrega tela de consulta conforme perfil do usu�rio 
    if (!$id){
        $perfil = strtolower(Yii::app()->user->role);
        $perfil = str_replace("siaudi2","siaudi",$perfil);
        $arquivo = "_search_manifestacao_saida_auditor"; // arquivo padr�o; 
        if ($perfil=="siaudi_cliente") { $arquivo = "_search_manifestacao_saida_cliente"; }
        if ($perfil=="siaudi_gerente" || $perfil=="siaudi_chefe_auditoria") { $arquivo = "_search_manifestacao_saida_gerente"; }        
        $this->renderPartial($arquivo, array('model' => $model,));
        
    // carrega tela com o relat�rio
    }else {

    // pega dados sobre a manifesta��o, relat�rio, perfil e login do usu�rio
    $Manifestacao = Manifestacao::model()->findByAttributes(array('id'=>$id));
    $Relatorio= Relatorio::model()->findByAttributes(array('id'=>$Manifestacao->relatorio_fk));
    $Especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id'=>$Relatorio->especie_auditoria_fk));
    $UnidadeAdministrativa = UnidadeAdministrativa::model()->findByAttributes(array('id'=>$Manifestacao->unidade_administrativa_fk)); 
    
    $perfil = strtolower(Yii::app()->user->role);      
    $perfil = str_replace("siaudi2","siaudi",$perfil);    
    $login = strtolower(Yii::app()->user->login);  
    
    
    // Verifica se manifesta��o (id) existe. 
   if(sizeof($Manifestacao)==0){
        echo "<br><br><div align=center><b>Manifesta��o n�o encontrada. </b></div>";            
        $manifestacao_erro=1; 
   }       

   // Verifica se usu�rio tem permiss�o para acessar este relat�rio de acordo com perfil 
   
   // regras para clientes
   if($perfil=="siaudi_cliente"){    
        $RelatorioAcesso = RelatorioAcesso::model()->findByAttributes(array('relatorio_fk'=>$Relatorio->id,'nome_login'=>$login));
        if(sizeof($RelatorioAcesso)>0){       
             $autorizado=1;       
        }           
    }  
            
    //regras de acesso para auditores
   if($perfil=="siaudi_auditor"){    
         // criar regras
         $autorizado=1;       
   }

    //regras de acesso para gerentes
   if($perfil=="siaudi_gerente" || $perfil=="siaudi_chefe_auditoria"){    
         // criar regras
         $autorizado=1;       
   }   
    
    if(!$autorizado){ echo "<br><br><center><font size=2 face=Verdana><b>Acesso negado.</b></font></center>"; }
       
    if(!$manifestacao_erro && $autorizado){   
           
     // exibe relat�rio  de manifesta��o
?>

<div class="formulario" Style="width:50%;margin-left:25%;">
            <fieldset class="visivel">
                <legend class="legendaDiscreta">Dados do relat�rio</legend>
                <table align="center">
                    <?php if ($Relatorio->data_relatorio) {
                        echo "<tr>
                            <td align='right'><b>N� Relat�rio: </b></td>
                            <td>" . $Relatorio->numero_relatorio." - ". $Especie_auditoria->nome_auditoria . "</td>            
                            </tr>";
                    }else {
                        echo "<Tr>
                              <td align='right'><b>Relat�rio ID: </b></td>
                              <td>".$Relatorio->id ." -  " . $Especie_auditoria->nome_auditoria."</td>            
                              </tr>";                       
                    } ?>
                       <Tr>
                           <td align="right"><b>Unidade Auditada: </b></td>
                           <td><?php echo $UnidadeAdministrativa->nome;?></td>            
                       </tr>        
                   </table>
            </fieldset>

    <br><br>
            <fieldset class="visivel">
                <legend class="legendaDiscreta">Manifestacao de <?php echo $Manifestacao->nome_login;?> em  <?php echo $Manifestacao->data_manifestacao;?></legend>
            <?php echo $Manifestacao->descricao_manifestacao;?>
            </fieldset>
   
<?php if ($Manifestacao->descricao_resposta){ ?>
        <br><br>
            <fieldset class="visivel">
                <legend class="legendaDiscreta">Resposta de <?php echo $Manifestacao->nome_login_resposta;?> em  <?php echo $Manifestacao->data_resposta;?></legend>
            <?php echo $Manifestacao->descricao_resposta;?>
            </fieldset>
<?php } ?>        
        <br>
        <center>
        <?php echo CHtml::link(Yii::t('app', 'Voltar'), $this->createUrl('/Manifestacao/ManifestacaoSaidaAjax'), array('class' => 'imitacaoBotao')); ?>
        </center>
</div>
<?php
    }
  }
?>