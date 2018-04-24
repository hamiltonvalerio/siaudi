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
foreach($dados as $vetor){
	$dados_organizado[$vetor['relatorio_fk']][$vetor['unidade_administrativa_fk']][] = $vetor;
	$total_recomendacao[$vetor['relatorio_fk']] += 1; 
	
}
foreach ($dados_organizado as $relatorio_fk=>$relatorio) {
	$Relatorio = Relatorio::model()->findAllByAttributes(array('id' => $relatorio_fk));
?>
<div class="tabelaListagemItensWrapper">
        <div class="tabelaListagemItens">
            <div class="tabelaListagemItens">
                <table border="0" cellpadding="0" cellpadding="0" align="center">
                    <tr>
                        <th align="center" bgcolor="#FFFFFF" rowspan="1">N� do Relat�rio</th>
                        <th align="center" bgcolor="#FFFFFF" rowspan="1">Unidade Auditada</th>
                        <th align="center" bgcolor="#FFFFFF" rowspan="1">N� da Recomenda��o</th>
                        <th align="center" bgcolor="#FFFFFF" rowspan="1">Dias �teis Homologados</th>
                        <th align="center" bgcolor="#FFFFFF" rowspan="1">Dias da �ltima avalia��o do auditor</th>
                    </tr>
                    <tr>
                        <td align="center" rowspan="<?php echo $total_recomendacao[$relatorio_fk]; ?>">
                            <?php
                            echo $Relatorio[0]->numero_relatorio . ' de ' . $Relatorio[0]->data_relatorio;
                            ?>
                        </td>                    
<?php	
	foreach($relatorio as $unidade_administrativa_fk=>$recomendacao){
?>
                        <td align="center" rowspan="<?php echo sizeof($recomendacao); ?>">
                            <?php
                                $UnidadeAdministrativa = UnidadeAdministrativa::model()->findbyAttributes(array('id' => $unidade_administrativa_fk));
                                echo $UnidadeAdministrativa->sigla;
                            ?>
                        </td>
<?php 
		$iteracao_rowspan=1;
		foreach($recomendacao as $vetorDados){
			if ($iteracao_rowspan==1):
    ?>
                        <td align="center">
                            <?php
                                echo $vetorDados['numero_item'] . '.' . $vetorDados['numero_recomendacao'];
                            ?>
                        </td>
                        <td align="center">
                            <?php
                                echo (Feriado::model()->CalculaQtdeDiasUteis(MyFormatter::converteData($vetorDados['data_relatorio']), date('d/m/y'))*-1);
                            ?>
                        </td>
                        <td align="center">
                            <?php
                            echo (Feriado::model()->CalculaQtdeDiasUteis(MyFormatter::converteData($vetorDados['data_resposta_auditor']), date('d/m/y'))*-1);
                            ?>
                        </td>
    <?php else: ?>
        <tr>
                                <td align="center">
                            <?php
                                echo $vetorDados['numero_item'] . '.' . $vetorDados['numero_recomendacao'];
                            ?>
                        </td>
                        <td align="center">
                            <?php
                                echo (Feriado::model()->CalculaQtdeDiasUteis(MyFormatter::converteData($vetorDados['data_relatorio']), date('d/m/y'))*-1);
                            ?>
                        </td>
                        <td align="center">
                            <?php
                            echo (Feriado::model()->CalculaQtdeDiasUteis(MyFormatter::converteData($vetorDados['data_resposta_auditor']), date('d/m/y'))*-1);
                            ?>
                        </td>
        </tr>
	<?php endif; $iteracao_rowspan++;?>
    <?php
    } ?>
    </tr>
    <?php 
	} ?>
	                </table>
            </div>
        </div>
    </div>
<?php 	
}
?>

<br><br>
<center>
    <a href="./RecomendacaoPendenteRespostaAuditadoAjax" class="imitacaoBotao">Voltar</a>
</center>