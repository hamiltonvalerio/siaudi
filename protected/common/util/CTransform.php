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
/**
 * Classe para manipula��o/transforma��o de strings espec�ficas n�o oferecidas 
 * via fun��es nativas do PHP.
 * 
 * Todos os m�todos devem transformar a string de alguma forma.
 * 
 * Esta classe teve origem de metodos retirados da antiga CMask, que passou a
 * acomodar helpers de valida��o e tratamento de datas, dentre outros, al�m da 
 * falta de padroniza��o nos nomes dos m�todos.
 * 
 * As manipula��es de Data n�o foram migradas, para dar preferencia ao uso da 
 * classe DateTime nativa do PHP.
 * 
 * Ao criar um metodo, favor siga este processo:
 * 1. Verifique se o m�todo que precisa j� n�o existe, para n�o haver duplica��o
 * 2. Mantenha o mesmo padr�o de nomenclatura
 * 3. Verifique novamente se o m�todo que precisa j� n�o existe. :)
 * 4. Evite ao m�ximo o acoplamento.
 * 
 * @package \common\util
 */
class CTransform
{
    /**
     * Remove os acentos e caracteres especiais e transformar para caixa alta
     * 
     * @param string $string String a ser manipulada
     * @return string String em maiusculo e sem caracteres especiais
     */
    public static function removeEspeciais($string)
    {
        return preg_replace('/[`^~\'"]/', null, iconv('ISO-8859-1', 'ASCII//TRANSLIT', $string));
    }


    /**
     * Transforma uma string num�rica em valor (float), removendo os separadores
     * de milhar.
     * 
     * Exemplo: CTransform::stringParaValor('9.999,99'); // retorna (float) 9999.99
     * 
     * @param string $string A ser transformada
     * @return float|integer|null Valor transformado ou nulo caso n�o seja uma string num�rica
     */
    public static function stringParaValor($string)
    {
        if (empty($string)) {
            return null;
        }

        $string = str_replace(".", "", $string);
        $string = str_replace(",", ".", $string);
        if (strpos($string, '.')) {
            return (float) $string;
        }
        return (integer) $string;
    }

    /**
     * Trasnforma um numero em extenso, pt-BR, a partir do n�mero.
     * 
     * Exemplo:
     *   CTransform::numeroParaExtenso(12345678.90, "real", "reais", "centavo", "centavos")
     * 
     * @author Chrystian Toigo
     * @since 06/12/2011
     * @param float $valor N�mero (ponto flutuante) a ser convertido
     * @return string|null N�mero por extenso, ou nulo caso um n�mero n�o seja passado
     */
    public static function numeroParaExtenso($valor, $moedaSing, $moedaPlur, $centSing, $centPlur)
    {
        if (!is_numeric($valor)) {
            return null;
        }

        $centenas = array(0,
            array(0, "cento", "cem"),
            array(0, "duzentos", "duzentos"),
            array(0, "trezentos", "trezentos"),
            array(0, "quatrocentos", "quatrocentos"),
            array(0, "quinhentos", "quinhentos"),
            array(0, "seiscentos", "seiscentos"),
            array(0, "setecentos", "setecentos"),
            array(0, "oitocentos", "oitocentos"),
            array(0, "novecentos", "novecentos"));

        $dezenas = array(0,
            "dez",
            "vinte",
            "trinta",
            "quarenta",
            "cinquenta",
            "sessenta",
            "setenta",
            "oitenta",
            "noventa");

        $unidades = array(0,
            "um",
            "dois",
            "tr�s",
            "quatro",
            "cinco",
            "seis",
            "sete",
            "oito",
            "nove");

        $excecoes = array(0,
            "onze",
            "doze",
            "treze",
            "quatorze",
            "quinze",
            "dezeseis",
            "dezesete",
            "dezoito",
            "dezenove");

        $extensoes = array(0,
            array(0, "", ""),
            array(0, "mil", "mil"),
            array(0, "milh�o", "milh�es"),
            array(0, "bilh�o", "bilh�es"),
            array(0, "trilh�o", "trilh�es"));

        $valorForm = trim(number_format($valor, 2, ".", ","));

        $inicio = 0;

        if ($valor <= 0) {
            return ( $valorExt );
        }

        for ($conta = 0; $conta <= strlen($valorForm) - 1; $conta++) {
            if (strstr(",.", substr($valorForm, $conta, 1))) {
                $partes[] = str_pad(substr($valorForm, $inicio, $conta - $inicio), 3, " ", STR_PAD_LEFT);
                if (substr($valorForm, $conta, 1) == ".") {
                    break;
                }
                $inicio = $conta + 1;
            }
        }

        $centavos = substr($valorForm, strlen($valorForm) - 2, 2);

        if (!( count($partes) == 1 and intval($partes[0]) == 0 )) {
            for ($conta = 0; $conta <= count($partes) - 1; $conta++) {

                $centena = intval(substr($partes[$conta], 0, 1));
                $dezena = intval(substr($partes[$conta], 1, 1));
                $unidade = intval(substr($partes[$conta], 2, 1));

                if ($centena > 0) {

                    $valorExt .= $centenas[$centena][($dezena + $unidade > 0 ? 1 : 2)] . ( $dezena + $unidade > 0 ? " e " : "" );
                }

                if ($dezena > 0) {
                    if ($dezena > 1) {
                        $valorExt .= $dezenas[$dezena] . ( $unidade > 0 ? " e " : "" );
                    } elseif ($dezena == 1 and $unidade == 0) {
                        $valorExt .= $dezenas[$dezena];
                    } else {
                        $valorExt .= $excecoes[$unidade];
                    }
                }

                if ($unidade > 0 and $dezena != 1) {
                    $valorExt .= $unidades[$unidade];
                }

                if (intval($partes[$conta]) > 0) {
                    $valorExt .= " " . $extensoes[(count($partes) - 1) - $conta + 1][(intval($partes[$conta]) > 1 ? 2 : 1)];
                }

                if ((count($partes) - 1) > $conta and intval($partes[$conta]) > 0) {
                    $conta3 = 0;
                    for ($conta2 = $conta + 1; $conta2 <= count($partes) - 1; $conta2++) {
                        $conta3 += (intval($partes[$conta2]) > 0 ? 1 : 0);
                    }

                    if ($conta3 == 1 and intval($centavos) == 0) {
                        $valorExt .= " e ";
                    } elseif ($conta3 >= 1) {
                        $valorExt .= ", ";
                    }
                }
            }

            if (count($partes) == 1 and intval($partes[0]) == 1) {
                $valorExt .= $moedaSing;
            } elseif (count($partes) >= 3 and ((intval($partes[count($partes) - 1]) + intval($partes[count($partes) - 2])) == 0)) {
                $valorExt .= " de " + $moedaPlur;
            } else {
                $valorExt = trim($valorExt) . " " . $moedaPlur;
            }
        }

        if (intval($centavos) > 0) {

            $valorExt .= (!empty($valorExt) ? " e " : "");

            $dezena = intval(substr($centavos, 0, 1));
            $unidade = intval(substr($centavos, 1, 1));

            if ($dezena > 0) {
                if ($dezena > 1) {
                    $valorExt .= $dezenas[$dezena] . ( $unidade > 0 ? " e " : "" );
                } elseif ($dezena == 1 and $unidade == 0) {
                    $valorExt .= $dezenas[$dezena];
                } else {
                    $valorExt .= $excecoes[$unidade];
                }
            }

            if ($unidade > 0 and $dezena != 1) {
                $valorExt .= $unidades[$unidade];
            }

            $valorExt .= " " . ( intval($centavos) > 1 ? $centPlur : $centSing );
        }

        return ( $valorExt );
    }

    /**
     * Transforma o m�s em extenso, pt-BR, a partir do n�mero do m�s.
     * 
     * Exemplo: CTransform::mesParaExtenso(2); // retorna "FEVEREIRO"
     * 
     * @param integer $mes M�s no formato num�rico. ex.: 1, 2, 10, ...
     * @return string|null M�s por extenso ou nulo caso um mes invalido seja passado
     */
    public static function mesParaExtenso($mes)
    {
        if ($mes > 0 && $mes < 13) {
            $meses = array(
                "JANEIRO", "FEVEREIRO", "MAR�O", "ABRIL", "MAIO", "JUNHO",
                "JULHO", "AGOSTO", "SETEMBRO", "OUTUBRO", "NOVEMBRO", "DEZEMBRO"
            );
            return $meses[--$mes];
        }
        return null;
    }

    /**
     * Retorna uma enumera��o (do portugu�s) com os �tens de um determinado array.
     * 
     * Exemplo:
     *   entrada: CTransform::arrayParaEnumeracao(array("Arroz", "Feij�o", "Ovo"))
     *   retorna: (string) "Arroz, Feij�o e Ovo"
     * 
     * @author igor.costa
     * @param array $array O array desejado para transforma��o
     * @return string Com a enumera��o em portugu�s.
     */
    public function arrayParaEnumeracao($array)
    {
        return join(' e ', array_filter(array_merge(array(join(', ', array_slice($array, 0, -1))), array_slice($array, -1))));
    }
}
