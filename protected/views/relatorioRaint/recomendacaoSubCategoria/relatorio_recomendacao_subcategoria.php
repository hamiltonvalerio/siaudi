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
$cs->registerCssFile($baseUrl . '/protected/common/extensions/jqplot/jquery.jqplot.css');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/jquery.jqplot.min.js');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/plugins/jqplot.barRenderer.min.js');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/plugins/jqplot.categoryAxisRenderer.min.js');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/plugins/jqplot.pointLabels.min.js');

$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/plugins/jqplot.logAxisRenderer.min.js');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/plugins/jqplot.canvasTextRenderer.min.js');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/plugins/jqplot.dateAxisRenderer.min.js');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/plugins/jqplot.categoryAxisRenderer.min.js');
$cs->registerScriptFile($baseUrl . '/protected/common/extensions/jqplot/plugins/jqplot.barRenderer.min.js');
?>
<script type="text/javascript">

    function salvarImagem() {
        var imgData = $('#chartdiv').jqplotToImageStr({});
        if (imgData) {
            window.location.href = imgData.replace("image/png", "image/octet-stream");
        }
    }

    $(function() {
        $.jqplot.config.enablePlugins = true;
<?
$legenda = Array();
$strValor = '[';
$strDescricao = '[';
$total_geral_categoria = 0;
$indiceCategoria = 1;
$indiceSubCategoria = 1;
foreach ($dadosCategoria as $vetorCategoria) {
    $legenda[$vetorCategoria['id']] = $indiceCategoria;
    $total_geral_categoria += $vetorCategoria['total_categoria'];
    foreach ($dadosSubCategoria as $vetorSubCategoria) {
        if ($vetorSubCategoria['recomendacao_categoria_fk'] == $vetorCategoria['id']) {
            $percentual = number_format((($vetorSubCategoria['total_subcategoria'] / $vetorCategoria['total_categoria']) * 100), 1, '.', '');
            $strValor .= $percentual . ',';
            $strDescricao .= "'" . $indiceCategoria . '.' . $indiceSubCategoria . ' - ' . $vetorSubCategoria['nome_subcategoria'] . "',";
            $indiceSubCategoria++;
        } else {
            $indiceSubCategoria = 1;
        }
    }
    $indiceCategoria++;
}
$strValor = substr($strValor, 0, -1) . ']'; //retirando a �ltima ','
$strDescricao = substr($strDescricao, 0, -1) . ']'; //retirando a �ltima ','
?>
		var valores = <? echo $strValor ?>;
        var ticks = <? echo $strDescricao ?>;
        plot1 = $.jqplot('chartdiv', [valores], {
            animate: !$.jqplot.use_excanvas,
            seriesDefaults: {
                renderer: $.jqplot.BarRenderer,
                pointLabels: {show: true},
                rendererOptions: {
                    varyBarColor: true
                }
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                    tickRenderer: $.jqplot.CanvasAxisTickRenderer,
                    tickOptions: {
                        angle: -90,
                        fontFamily: 'Arial',
                        fontSize: '8pt',
                        labelPosition: 'auto'
                    },
                    ticks: ticks
                },
                yaxis: {
                    tickOptions: {formatString: '%.1f%', fontSize: '8pt',}

                }
            },
            highlighter: {show: false}
        });
        var imgData = $('#chartdiv').jqplotToImageStr({}); 
        var imgElem = $('<img/>').attr('src',imgData); 
        $('#chartdiv').hide();
        $('#chartdivimg').empty(); // remove the old graph
        $('#chartdivimg').append(imgElem);
        
    });

</script>


<div class="tabelaListagemItensWrapper">
    <div id="chartdiv" style="position:relative; height: 550px; width: 600px; float:left;"></div>
    <div id="chartdivimg" style="position:relative; height: 550px; width: 600px; float:left;"></div>
    <div class="tabelaListagemItens" style="position: relative; float: left;">
        <table  class="tabelaListagemItensYii">
            <tr>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">Categoria</th>
                <th align="center" bgcolor="#FFFFFF" rowspan="1" colspan="12">Percentual</th>
            </tr>
            <?php foreach ($dadosCategoria as $vetorCategoria): ?>
                <tr>
                    <td align=left><?= $legenda[$vetorCategoria['id']] . ' - ' . $vetorCategoria['nome_categoria']; ?> </td>
                    <td align=center rowspan="1" colspan="12"><?= number_format((($vetorCategoria['total_categoria'] / $total_geral_categoria) * 100), 1, ',', '') . '%'; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>    

<?php
if ($descRecomendacao != null) {
    ?>
    <div class="tabelaListagemItensWrapper" >
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

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<center>
    <a href="./RecomendacaoPorSubCategoriaAjax" class="imitacaoBotao">Voltar</a>
</center>




