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
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">Auditores</th>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1">Dias �teis</th>
                </tr>
                <?php
                foreach ($dados as $vetorDados) {
                        $RelatorioAuditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $vetorDados['id']));
                        $PlanEspecifico= PlanEspecifico::model()->findByAttributes(array('id' => $vetorDados['plan_especifico_fk']));
                  ?>
                    <tr>
                        <td align="center">
                            <?php
                            echo $vetorDados['numero_relatorio'] . ' de ' . $vetorDados['data_relatorio'];
                            ?>
                        </td>
                        <td align="center"><?php
                            foreach ($RelatorioAuditor as $vetor) {
                                $Auditor = Usuario::model()->findByPk($vetor[usuario_fk]);
                                echo $Auditor->nome_usuario . '<br>';
                            }
                            ?>
                        </td>
                        <td align="center">
                            <?php
                            if ($vetorDados['plan_especifico_fk']){
                                if (count($PlanEspecifico) > 1) {
                                    echo Feriado::model()->CalculaQtdeDiasUteis($PlanEspecifico[0]['data_inicio_atividade'], $vetorDados['data_gravacao']);
                                } else {
                                    echo Feriado::model()->CalculaQtdeDiasUteis($PlanEspecifico['data_inicio_atividade'], $vetorDados['data_gravacao']);
                                }
                            } else {
                                echo "(Sem planejamento espec�fico vinculado)";
                            }
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
    <a href="./RelatorioTempoExecucaoTrabalhoAuditorAjax" class="imitacaoBotao">Voltar</a>
</center>
