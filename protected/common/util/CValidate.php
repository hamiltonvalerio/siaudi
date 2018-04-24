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
 * Classe para valida��o de valores espec�ficos.
 * 
 * Esta classe teve origem de metodos retirados da antiga CMask, que passou a
 * acomodar helpers de valida��o e tratamento de datas, dentre outros, al�m da 
 * falta de padroniza��o nos nomes dos m�todos.
 * 
 * As valida��es de Data n�o foram migradas, para dar preferencia ao uso da 
 * classe DateTime nativa do PHP.
 * 
 * Ao criar um metodo, favor siga este processo:
 * 1. Verifique se o m�todo que precisa j� n�o existe, para n�o haver l�gica duplicada
 * 2. Mantenha o mesmo padr�o de nomenclatura
 * 3. Verifique novamente se o m�todo que precisa j� n�o existe. :)
 * 4. Evite ao m�ximo o acoplamento.
 * 
 * @package \common\util
 */
class CValidate
{
    /**
     * Valida o CPF de acordo com as regras de forma��o do c�digo pela propria
     * Receita Federal.
     * 
     * @param string $cpf CPF a ser validado (com ou sem formata��o).
     * @return boolean Resultado da verifica��o.
     */
    public static function cpf($cpf)
    {
        $cpf = preg_replace('/\pP+/i', '', $cpf);

        for ($i = 0; $i < 10; $i++) {
            if ($cpf == str_repeat($i, 11) or !preg_match("@^[0-9]{11}$@", $cpf) or $cpf == "12345678909") {
                return false;
            }
            if ($i < 9) {
                $soma[] = $cpf{$i} * ( 10 - $i );
            }
            $soma2[] = $cpf{$i} * ( 11 - $i );
        }
        if (((array_sum($soma) % 11) < 2 ? 0 : 11 - ( array_sum($soma) % 11 )) != $cpf{9}) {
            return false;
        }

        return ((( array_sum($soma2) % 11 ) < 2 ? 0 : 11 - ( array_sum($soma2) % 11 )) != $cpf{10}) ? false : true;
    }

    /**
     * Valida o CNPJ de acordo com as regras de forma��o do c�digo pela propria
     * Receita Federal.
     * Valida, inclusive, se a m�scara esta aplicada corretamente.
     *
     * @param string $cnpj CNPJ a ser validado (com formata��o)
     * @return boolean Resultado da verifica��o
     */
    public static function cnpj($str)
    {
        if (!preg_match('|^(\d{2,3})\.?(\d{3})\.?(\d{3})\/?(\d{4})\-?(\d{2})$|', $str, $matches)) {
            return false;
        }

        array_shift($matches);
        $str = implode('', $matches);

        if (strlen($str) <> 14) {
            return false;
        }
        $calcular = 0;
        $calcularDois = 0;
        for ($i = 0, $x = 5; $i <= 11; $i++, $x--) {
            $x = ($x < 2) ? 9 : $x;
            $number = substr($str, $i, 1);
            $calcular += $number * $x;
        }

        for ($i = 0, $x = 6; $i <= 12; $i++, $x--) {
            $x = ($x < 2) ? 9 : $x;
            $numberDois = substr($str, $i, 1);
            $calcularDois += $numberDois * $x;
        }

        $digitoUm = (($calcular % 11) < 2) ? 0 : 11 - ($calcular % 11);
        $digitoDois = (($calcularDois % 11) < 2) ? 0 : 11 - ($calcularDois % 11);

        if ($digitoUm <> substr($str, 12, 1) || $digitoDois <> substr($str, 13, 1)) {
            return false;
        }
        return true;
    }

    /**
     * Realiza a validacao de Documento, sendo ele CPF ou CNPJ.
     * 
     * @param string $str CPF ou CNPJ a ser validado
     * @return boolean Resultado da verificacao
     */
    public function cpfOuCnpj($str)
    {
        $numeroDocumento = preg_replace('/[^0-9]/', '', $str);
        if (strlen($numeroDocumento) == 11) {
            return self::cpf($str);
        }
        return self::cnpj($str);
    }
}
