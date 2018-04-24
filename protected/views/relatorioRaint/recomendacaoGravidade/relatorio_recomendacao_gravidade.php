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
$str = '';
foreach ($dados as $vetor) {
    $valor = number_format((($vetor['total_gravidade'] / $vetor['total']) * 100), 2, ',', '');
    $str = $str . "['" . $vetor['nome_gravidade'] . ' - ' . $vetor['total_gravidade'] . ' - ' . $valor . "%'," . $vetor['total_gravidade'] . "]";
}
$str = str_replace('][', '],[', $str);
echo $str;
// exit;
?>

                    ]],
                {
                    title: 'Recomenda��es por Gravidade',
                    seriesDefaults: {
                        shadow: false,
                        renderer: jQuery.jqplot.PieRenderer,
                        rendererOptions: {
                            startAngle: 180,
                            sliceMargin: 4,
							dataLabels: 'percent',
                            dataLabelFormatString:'%.2f%',
                            showDataLabels: true}
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
        <div id="chartdiv" style="height: 500px; width: 500px; margin: 0 auto"></div>
        <div id="chartdivimg" style="height: 500px; width: 500px; margin: 0 auto"></div>
    </div>
    <br>
    <div class="tabelaListagemItens">
        <table border="0" cellpadding="0" cellpadding="0" align="center">
            <tr>
                <th align="center" bgcolor="#FFFFFF" rowspan="1">Gravidade</th>
                <th align="center" bgcolor="#FFFFFF" rowspan="1" colspan="12">N�mero de Recomenda��es</th>
            </tr>
            <?php
            foreach ($dados as $vetor) {
                echo '<tr>';
                echo '<td align=left>' . $vetor['nome_gravidade'] . '</td>';
                echo '<td align=center rowspan="1" colspan="12">' . $vetor['total_gravidade'] . '</td>';
                echo '</tr>';
                $total_gravidade = $vetor['total'];
            }
            echo '<tr>';
            echo '<td align=right>Total:</td>';
            echo '<td align=center rowspan="1" colspan="12">' . $total_gravidade . '</td>';
            echo '</tr>';
            ?>
        </table>

    </div>

</div>

<br><br>
<center>
    <a href="./RecomendacaoGravidadeAjax" class="imitacaoBotao">Voltar</a>
</center>




