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
    $objeto = Objeto::model()->findByPk($_POST[Raint][objeto_fk]);
    $nome_objeto = (sizeof($objeto)>0)? $objeto->nome_objeto:"Todos"; 
?>

<center>
    <table width="100%" border="0" cellpadding="2" cellspacing="0">
        <tr>
            <td align="right" width="50%"><b>Exerc�cio:</b></td>
            <td align="left" width="50%"><?=$_POST[Raint][valor_exercicio];?></td>
        </tr>
        <tr>
            <td align="right"><b>Objeto(s) Selecionado(s):</b></td>
            <td align="left"><?=$nome_objeto;?></td>
        </tr>
    </table>
</center><br>


<div class="tabelaListagemItensWrapper">   
    <div class="tabelaListagemItens" style="float:left; width:50%; ">
        <table  class="tabelaListagemItensYii">
            <tr>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">Riscos Pr�-identificados</th>
            </tr>
            <?php 
                $vetor_valida=array(null); // valida se a informa��o n�o est� repetida
                foreach ($dadosRiscoPreIdentificados as $vetorRiscoPreIdentificados): 
                    $nome_risco = $vetorRiscoPreIdentificados['nome_risco'];
                    if (!array_search($nome_risco,$vetor_valida) && $nome_risco){
                        $vetor_valida[]=$nome_risco;
                ?>
                <tr>
                    <td align=left rowspan="1"><?=$nome_risco;?> </td>
                </tr>
               <?php } endforeach; ?>
        </table>
    </div>
    <div class="tabelaListagemItens" style="float:left; width:50%;">
        <table  class="tabelaListagemItensYii">
            <tr>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">Riscos P�s-identificados</th>
            </tr>
            <?php 
                $vetor_valida=array(null); // valida se a informa��o n�o est� repetida            
                foreach ($dadosRiscoPosIdentificados as $vetorRiscoPosIdentificados):
                    $nome_risco = $vetorRiscoPosIdentificados['nome_risco'];
                    if (!array_search($nome_risco,$vetor_valida) && $nome_risco){
                        $vetor_valida[]=$nome_risco;
           ?>
                <tr>
                    <td align=left rowspan="1"><?=$nome_risco; ?> </td>
                </tr>
           <?php } endforeach; ?>
        </table>
    </div>
</div>


<div class="tabelaListagemItensWrapper">   
    <div class="tabelaListagemItens">
        <table class="tabelaListagemItensYii">
            <tr>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">Impactos poss�veis</th>
            </tr>
            <?php 
                $vetor_valida=array(null); // valida se a informa��o n�o est� repetida
                foreach ($dadosRiscoPreIdentificados as $vetorRiscoPreIdentificados):
                    $descricao_impacto = $vetorRiscoPreIdentificados['descricao_impacto'];
                    if (!array_search($descricao_impacto,$vetor_valida) && $descricao_impacto){
                        $vetor_valida[]=$descricao_impacto;                    
            ?>
                <tr>
                    <td align=left rowspan="1"><?=$descricao_impacto;?> </td>
                </tr>
                    <?php } endforeach; ?>
            <?php foreach ($dadosRiscoPosIdentificados as $vetorRiscoPosIdentificados): 
                 $descricao_impacto = $vetorRiscoPosIdentificados['descricao_impacto'];
                    if (!array_search($descricao_impacto,$vetor_valida) && $descricao_impacto){
                        $vetor_valida[]=$descricao_impacto;              
             ?>
                <tr>
                    <td align=left rowspan="1"><?=$descricao_impacto;?> </td>
                </tr>
             <?php } endforeach; ?>
        </table>
    </div>
</div>

<div style="height:16px;"></div>

<div class="tabelaListagemItensWrapper">   
    <div class="tabelaListagemItens">
        <table  class="tabelaListagemItensYii">
            <tr>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">Medidas mitigadoras propostas</th>
            </tr>
            <?php 
                  $vetor_valida=array(null); // valida se a informa��o n�o est� repetida
                  foreach ($dadosRiscoPreIdentificados as $vetorRiscoPreIdentificados): 
                    $descricao_mitigacao = $vetorRiscoPreIdentificados['descricao_mitigacao'];
                    if (!array_search($descricao_mitigacao,$vetor_valida) && $descricao_mitigacao){
                        $vetor_valida[]=$descricao_mitigacao;                  
             ?>
                <tr>
                    <td align=left rowspan="1"><?=$descricao_mitigacao;?> </td>
                </tr>
             <?php } endforeach; ?>
            <?php foreach ($dadosRiscoPosIdentificados as $vetorRiscoPosIdentificados): 
                    $descricao_mitigacao = $vetorRiscoPosIdentificados['descricao_mitigacao'];
                    if (!array_search($descricao_mitigacao,$vetor_valida) && $descricao_mitigacao){
                        $vetor_valida[]=$descricao_mitigacao;                        
                ?>
                <tr>
                    <td align=left rowspan="1"><?=$descricao_mitigacao;?> </td>
                </tr>
           <?php } endforeach; ?>
        </table>
    </div>
</div>
<br><br>
<center>
    <a href="./RelatorioRiscoPorObjetoAjax" class="imitacaoBotao">Voltar</a>
</center>