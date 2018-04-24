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
        <div class="tabelaListagemItens">
            <table border="0" cellpadding="0" cellpadding="0" align="center">
                <tr>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">N� do Relat�rio</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1" width="250">Auditores</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">Unidade Auditada</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">N� da Recomenda��o</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">�ltima resposta do auditado</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">Dias �teis</th>
                </tr>
                <?php
                foreach ($dados as $vetorDados) {
                    $RelatorioAuditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $vetorDados['relatorio_fk']));
//    $RelatorioSureg = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $vetorDados['relatorio_fk']));
                    ?>


                    <tr>
                        <td align="center" rowspan="1">
                            <?php
                            echo $vetorDados['numero_relatorio'] . ' de ' . MyFormatter::converteData($vetorDados['data_relatorio']);
                            ?>
                        </td>
                        <td align="left"><?php
                            foreach ($RelatorioAuditor as $vetor) {
                                $Auditor = Usuario::model()->findByPk($vetor[usuario_fk]);
                                echo " - " . $Auditor->nome_usuario . '<br>';
                            }
                            ?>
                        </td>
                        <td align="center">
                            <?php
                            $UnidadeAdministrativa = UnidadeAdministrativa::model()->findbyAttributes(array('id' => $vetorDados[unidade_administrativa_fk]));
                            echo $UnidadeAdministrativa->sigla;
                            ?>
                        </td>
                        <td align="center">
                            <?php
                            echo $vetorDados['numero_item'] . "." . $vetorDados['numero_recomendacao'];
                            ?>
                        </td>                        
                        <td>
                            <?php
                            echo $vetorDados['descricao_resposta'];
                            ?>
                        </td>
                        <td align="center">
                            <?php
                            echo Feriado::model()->CalculaQtdeDiasUteis(MyFormatter::converteData($vetorDados['data_resposta']), date('d/m/y'));
                            ?>
                        </td>
                    </tr>

                    <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>
<br><br>
<center>
    <a href="./RecomendacaoNaoAvaliadaAuditorAjax" class="imitacaoBotao">Voltar</a>
</center>