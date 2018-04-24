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
<div class="tabelaListagemItensWrapper">
    <div class="tabelaListagemItens">
        <table border="0" cellpadding="0" cellpadding="0" align="center">
            <tr>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">N� do Relat�rio</th>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">Unidade Auditada</th>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">N� de Recomenda��es</th>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">N� de Recomenda��es Solucionadas</th>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">Percentual Solucionado</th>
            </tr>
            <?php
            foreach ($dados as $vetor) {
                echo '<tr>';
                echo '<td align=center>' . $vetor['relatorio'] . '</td>';
                echo '<td align=center rowspan="1">' . $vetor['sigla'] . '</td>';
                echo '<td align=center rowspan="1">' . $vetor['total_recomendacao'] . '</td>';
                echo '<td align=center rowspan="1">' . $vetor['total_solucionadas'] . '</td>';
                $percentual_solucionado = round((($vetor['total_solucionadas'] / $vetor['total_recomendacao'])*100), 2);
                echo '<td align=center rowspan="1">' . $percentual_solucionado . '%</td>';
                echo '</tr>';
            }
            ?>
        </table>

    </div>

</div>

<br><br>
<center>
    <a href="./RecomendacaoSolucionadasAjax" class="imitacaoBotao">Voltar</a>
</center>




