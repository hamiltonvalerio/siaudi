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
<style type="text/css">
    p {
        display: inline;
    }
</style>
<div class="tabelaListagemItensWrapper">
    <div class="tabelaListagemItens">
        <div class="tabelaListagemItens">
            <table border="0" cellpadding="0" cellpadding="0" align="center">
                <tr>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">N� do Relat�rio</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">A��es</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">Valor</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">Constata��o</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1" colspan="2">Recomenda��o / Est�gio de Implementa��o</th>
                </tr>
                <?php
                foreach ($dados as $vetorDados) {
                    $rowspan = sizeof($vetorDados->recomendacaoFk);
                    $rowspan = ($rowspan==0)? 1: $rowspan ;
                    if ($rowspan!=0)
                        $vetorDados->recomendacaoFk = array_reverse($vetorDados->recomendacaoFk);
                    ?>
                    <tr>
                        <td align="center" rowspan="<?php echo $rowspan; ?>">
                            <?php echo Item::model()->ItemRelatorio($vetorDados->id,null,1) ?>
                            <br>
                            <?php echo "(" . RelatorioSureg::model()->sureg_por_relatorio($vetorDados->capituloFk->relatorioFk->id) . ")"; ?>
                        </td>
                        <td align="center" rowspan="<?php echo $rowspan; ?>">
                            <?php
                            $dados_objeto = Objeto::model()->findByAttributes(array('id' => $vetorDados['objeto_fk']));
                            echo $dados_objeto['nome_objeto'];
                            ?>
                        </td>                        
                        <td align="right" width="100" rowspan="<?php echo $rowspan; ?>"> <?=($vetorDados['valor_reais'])? 'R$ '. $vetorDados['valor_reais']:"n�o se aplica"; ?> </td>
                        <td align="left" rowspan="<?php echo $rowspan; ?>"> <?php echo $vetorDados['nome_item']; ?> </td>
                                <?php 
                                $iteracao_rowspan=1;
                                foreach ($vetorDados->recomendacaoFk as $key => $recomendacao): 
                                    if ($iteracao_rowspan==1){
                                        echo "<td width=500 align='left'>$vetorDados->numero_item.$recomendacao->numero_recomendacao - $recomendacao->descricao_recomendacao </td>
                                              <td align=center width=50>".Resposta::model()->FollowUp_Ultimo_Status($recomendacao->id)."</td></tr>";
                                    } else {
                                        echo "<tr><td width=500 align='left'>$vetorDados->numero_item.$recomendacao->numero_recomendacao - $recomendacao->descricao_recomendacao</td>
                                                  <td align=center width=50>".Resposta::model()->FollowUp_Ultimo_Status($recomendacao->id)."</td>
                                              </tr>";
                                    }
                                    $iteracao_rowspan++;
                                 endforeach; 
                                 if (sizeof($vetorDados->recomendacaoFk)==0){
                                     echo "<td>&nbsp;</td><td>&nbsp;</td></tr>";
                                 }
                 } ?>
            </table>
        </div>
    </div>
</div>

<br><br>
<center>
    <a href="./RelatorioCGUAjax" class="imitacaoBotao">Voltar</a>
</center>
