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
<?
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/jquery.jqplot.min.js');
$cs->registerCssFile($baseUrl . '/protected/common/extensions/jqplot/jquery.jqplot.css');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/plugins/jqplot.pieRenderer.js');

?>

<script type="text/javascript">

    $(function() {
        plot2 = jQuery.jqplot('chartdiv',
                [[
<?php
//Fun��o recursiva para obter valor de um determinado campo.
function array_value_recursive($key, array $arr) {
    $val = array();
    array_walk_recursive($arr, function($v, $k) use ($key, &$val) {
                if ($k == $key) {
                    array_push($val, $v);
                }
            });
    return count($val) > 1 ? $val : array_pop($val);
}
if (sizeof($dados) > 1){
	$total_geral = array_sum(array_value_recursive('total_categoria', $dados));
} else {
	$total_geral = $dados[0]['total_categoria'];
}
$str = '';

foreach ($dados as $vetor) {
    $media = number_format(($vetor['total_categoria'] / $total_geral * 100), 2, ',', '');
    $str = $str . "['" . $vetor['nome_categoria'] . ' - ' . $vetor['total_categoria'] . ' - ' . $media . "%'," . $vetor['total_categoria'] . "]";
}
$str = str_replace('][', '],[', $str);
echo $str;
?>
                    ]],
                {
                    title: 'Recomendaca��es por Categoria',
                    seriesDefaults: {
                        shadow: false,
                        renderer: jQuery.jqplot.PieRenderer,
                        rendererOptions: {
                            startAngle: 180,
                            sliceMargin: 4,
							dataLabels: 'percent',
                            dataLabelFormatString:'%.2f%',
                            showDataLabels: true
                            }
                    },                  
                    legend: {
                        show: true,
                        placement: 'insideGrid',
                        location: 's'

                    }
                }
        );
        
        var imgData = $('#chartdiv').jqplotToImageStr({}); 
        var imgElem = $('<img/>').attr('src',imgData); 
        $('#chartdiv').hide();
        $('#chartdivimg').empty(); // remove the old graph
        $('#chartdivimg').append(imgElem);
    });

</script>

<div class="tabelaListagemItensWrapper">
    <div align="center" >
        <div id="chartdiv" style="height: 550px; width: 550px; margin: 0 auto"></div>
        <div id="chartdivimg" style="height: 550px; width: 550px; margin: 0 auto"></div>
    </div>
    <div style="height:16px;"></div>
    <div class="tabelaListagemItens">
        <table border="0" cellpadding="0" cellpadding="0" align="center">
            <tr>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">Categoria</th>
                <th align="center" bgcolor="#FFFFFF" rowspan="1" colspan="12">Quantidade</th>
                <th align="center" bgcolor="#FFFFFF" rowspan="1" colspan="12">Percentual</th>
            </tr>
            <?php
            foreach ($dados as $vetor) {
                $media = ($vetor['total_categoria'] / $total_geral * 100);
                echo '<tr>';
                echo '<td align=left>' . $vetor['nome_categoria'] . '</td>';
                echo '<td align=center rowspan="1" colspan="12">' . $vetor['total_categoria'] . '</td>';
                echo '<td align=center rowspan="1" colspan="12">' . number_format($media, 2, ',', '') . '%</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
    <?php
    if ($descRecomendacao != null) {
        ?>
        <div style="height:16px;"></div>
        <div class="tabelaListagemItens">
            <table border="0" cellpadding="0" cellpadding="0" align="center">
                <tr>
                    <th align="center" bgcolor="#FFFFFF" rowspan="1" colspan="12">Descri��o das Recomenda��es</th>
                </tr>
                <?php
                $descRecomendacaoAnterior = '';
                foreach ($descRecomendacao as $vetor) {
                    if ($descRecomendacaoAnterior != $vetor['nome_categoria']) {
                        echo '<tr>';
                        echo '<th align="center" bgcolor="#FFFFFF" rowspan="1">' . $vetor['nome_categoria'] . '</th>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td align=left colspan="12">' . $vetor['descricao_recomendacao'] . '</td>';
                        echo '</tr>';
                        $descRecomendacaoAnterior = $vetor['nome_categoria'];
                    } else {
                        echo '<tr>';
                        echo '<td align=left>' . $vetor['descricao_recomendacao'] . '</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </table>
        </div>
        <?php
    }
    ?>
</div>


<br><br>
<center>
    <a href="./RecomendacaoPorCategoriaAjax" class="imitacaoBotao">Voltar</a>
</center>




