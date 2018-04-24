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
    $perfil = str_replace("siaudi2","siaudi",$perfil);    
    if($perfil!="siaudi_chefe_auditoria" && $perfil!="siaudi_gerente"){
        echo "<br><br><b><center>";
        echo "Funcionalidade dispon�vel apenas para o Gerente e o Chefe de Auditoria";        
        echo "</center></b>";
    } else {
        
    $baseUrl = Yii::app()->baseUrl; 
    $cs = Yii::app()->getClientScript();    
    $cs->registerScriptFile($baseUrl.'/js/Relatorio.js');        
    $form = $this->beginWidget('GxActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'post',
        'id' =>'reiniciar_contagem'
            ));
        

?>
<div class="formulario" Style="width: 70%;">
    <fieldset class="visivel">
        <legend class="legendaDiscreta">Reiniciar Contagem </legend>
        <div class='row'><font size="2">
            <?php if (!$_POST[reiniciar]){ ?>
                    Clicando no bot�o abaixo, a contagem dos itens do Relat�rio ser� reiniciada, <br>
                    come�ando do n� 1, a partir do <b>PR�XIMO</b> Relat�rio que for homologado. 
                        </font><br><br>
                    <p align="center">
                        <input type="hidden" name="reiniciar" value="1">
                    <a class="imitacaoBotao" href="javascript:valida_reiniciarContagem();">Confirmar</a>            
                    </p>
                    <br><br>
            <?php } else { ?>
                    <br>   
                    Solicita��o efetuada com sucesso! O <b>PR�XIMO</b> Relat�rio de Auditoria <br>
                    que for homologado, ter� a contagem dos itens reiniciada, come�ando do n� 1.<br><br>
            <?php } ?>
       </div>      
    </fieldset>
    <div style="height:5px;"></div>
    <div class="rowButtonsN1">
        <?php echo CHtml::link(Yii::t('app', 'Cancel'), $this->createUrl('Site/index'), array('class' => 'imitacaoBotao')); ?>
    </div>    
 
<?php 
$relatorio_reiniciar = RelatorioReiniciar::model()->findAll('st_reiniciar=false order by data DESC');
if(sizeof($relatorio_reiniciar)>0 && !$_POST[reiniciar] ){?>
    <br>
    <table class="tabelaListagemItensYii" style="width:200px;">
           <thead>
           <tr><th id="criterio-grid_c0" colspan="2">Solicita��es anteriores </th></tr>
           <tr><th id="criterio-grid_c0">Data</th><th>Login</th></tr>           
           </thead>
           <tbody>
               <?php foreach($relatorio_reiniciar as $vetor){
                        $class=($class=="odd")? "even":"odd";
                        echo "<tr class='".$class."'>
                        <td width='30%' align=center>".$vetor->data."</td>
                        <td>".$vetor->login."</td>                
                        </tr>";         
                    } ?>
           </tbody>
   </table>
</div>    
<?php } ?>
</div>

<?php 
 $this->endWidget(); 

} ?>