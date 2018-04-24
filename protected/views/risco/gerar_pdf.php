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
    // recebe o valor do risco
    // e calcula seu tipo
    function calcula_risco($risco,$retornar_css){
            if ($risco>=0 && $risco<1) {
                $retorno="Irrelevante";
                $retorno_css = "irrelevante";
            }

            if ($risco>=1 && $risco<2) {
                $retorno="Baixo";
                $retorno_css = "baixo";
            }            
            if ($risco>=2 && $risco<3) {
                $retorno="M�dio";
                $retorno_css = "medio";
            }            
            if ($risco>=3 && $risco<4) {
                $retorno="Alto";
                $retorno_css = "alto";
            }            
            if ($risco>=4 && $risco<=5) {
                $retorno="Cr�tico";
                $retorno_css = "critico";
            }       

            if ($retornar_css==1) { return $retorno_css; } 
                            else { return $retorno; } 
        }
        
    // testa se existe a��es  e crit�rios cadastrados para este ano 
    if ($_GET["exercicio"]) {
        if (!empty($model_criterio_dados)) {
            $criterio_ok = 1;
        }
        if (!empty($model_processo_dados)) {
            $acao_ok = 1;
        }
    }

    if ($criterio_ok and $acao_ok) {

        // conta os tipos de a��o para gerar o rowspan
        foreach ($model_processo_dados as $vetor) {
            $tipos_de_acao[$vetor[tipo_processo_fk]]++;
        }

        // calcula soma dos pesos para fazer m�dia composta
        $peso_total = 0;
        if (is_array($model_criterio_dados)) {
            foreach ($model_criterio_dados as $vetor) {
                $peso_total +=$vetor->valor_peso;
            }
        }

        // calcula totais dos itens
        $contador = 1;
        foreach ($model_processo_dados as $vetor) {
            foreach ($model_criterio_dados as $vetor_criterio) {
                $nota = Risco::model()->RecuperaNota($vetor[id], $vetor_criterio->id);
                $vetor_soma[$contador]+= ($nota * $vetor_criterio->valor_peso);
            }
            $vetor_total[$contador] = round($vetor_soma[$contador] / $peso_total, 2);
            $contador++;
        }


        // monta cabe�alho da tabela                  
        ?>
        <table>
            <thead>
                <tr>
                    <th class="th" width="50">Tipo de Processo </th>
                    <th class="th" width="230">Processo</th>
                    <?php
                    foreach ($model_criterio_dados as $vetor) {
                        echo " <th class='th' >  " . $vetor->nome_criterio . " </th>";
                    }
                    ?>
                    <th class="th" width="40">Total</th>                                        
                    <th class="th" width="100">Grau de Risco</th>                    
                </tr>
            </thead>

            <tbody>
                <?php
                // gera linhas com as a��es 
                $contador = 1;
                $encontrou_value = 0;
                foreach ($model_processo_dados as $vetor) {
                    $class_bg = ($class_bg == "even") ? "odd" : "even";
                    echo"<tr >";
                    if (!$imprime[$vetor[tipo_processo_fk]]) {
                        echo "<td class='" . $class_bg . "' rowspan=" . $tipos_de_acao[$vetor[tipo_processo_fk]] . ">" . $vetor[nome_tipo_processo] . "</td>";
                        $imprime[$vetor[tipo_processo_fk]] = 1;
                    }
                    echo "<td class='" . $class_bg . "' >" . $vetor[nome_processo] . "</td>";

                    // gera colunas com os crit�rios
                    foreach ($model_criterio_dados as $vetor_criterio) {
                        $value = Risco::model()->RecuperaNota($vetor[id], $vetor_criterio->id);
                        $value = str_replace(".", ",", $value);
                        $value = ($value) ? $value : "";
                        if ($value && $encontrou_value == 0) {
                            $encontrou_value = 1;
                        }
                        echo " <td  class='" . $class_bg . "' align=center>{$value}</td>";
                    }
                    echo
                    "<td  class='" . $class_bg . "' align=center>" . str_replace(".", ",", $vetor_total[$contador]) . "</td>" . // total
                    "<td  class='risco_".calcula_risco($vetor_total[$contador],1)."' align=center>". calcula_risco($vetor_total[$contador],0) ."</td>" . // crit�rio
                    "</tr>";
                    $contador++;
                }
                ?>
            </tbody>
        </table>


        <br><br> 
        <table width="90%"><tr><td width="50%" align="center" valign="top">
                    <table style="width:300px;">
                        <thead>
                            <tr>
                                <th id="criterio-grid_c0">Legenda de Crit�rios </th><th>Pesos</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            if (is_array($model_criterio_dados)) {
                                foreach ($model_criterio_dados as $vetor) {
                                    $class_bg = ($class_bg == "even") ? "odd" : "even";
                                    $TipoCriterio = GxHtml::listDataEx(TipoCriterio::model()->findAllByAttributes(array('id' => $vetor->tipo_criterio_fk)));
                                    echo "<tr class='" . $class_bg . "'><td>" .
                                    $vetor->nome_criterio . " - " .
                                    $TipoCriterio[$vetor->tipo_criterio_fk] .
                                    "</td><td align=center>" . $vetor->valor_peso . "</td></tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </td>

                <td width="50%" align="center"  valign="top">
                    <table style="width:200px;">
                        <thead>
                            <tr>
                                <th id="criterio-grid_c0" colspan="2">Grau de Risco </th></tr>
                        </thead>
                        <tbody>
                            <tr class="risco_irrelevante">
                                <td width="30%" align=center>0--0,99</td>
                                <td>Irrelevante</td>                
                            </tr>
                            <tr class="risco_baixo">
                                <td align=center>1---1,99</td>
                                <td>Baixo</td>                
                            </tr>
                            <tr class="risco_medio">
                                <td align=center>2---2,99</td>
                                <td>M�dio</td>                
                            </tr>
                            <tr class="risco_alto">
                                <td align=center>3--3,99</td>
                                <td>Alto</td>                
                            </tr>
                            <tr class="risco_critico">
                                <td align=center>4---5</td>
                                <td>Cr�tico</td>                
                            </tr>            
                        </tbody>
                    </table>

                </td></tr></table>
        <?php
    }
    exit;
    ?>