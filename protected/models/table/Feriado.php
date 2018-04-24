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

Yii::import('application.models.table._base.BaseFeriado');

class Feriado extends BaseFeriado {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function afterFind() {
        parent::afterFind();
        if (isset($this->data_feriado))
            $this->data_feriado = MyFormatter::converteData($this->data_feriado);
    }

// recebe data no formato americano 
// (aaaa-mm-dd) e retorna o dia da semana
    public function DiaSemana($data) {
        $ano = substr("$data", 0, 4);
        $mes = substr("$data", 5, -3);
        $dia = substr("$data", 8, 9);

        $diasemana = date("w", mktime(0, 0, 0, $mes, $dia, $ano));

        switch ($diasemana) {
            case"0": $diasemana = "domingo";
                break;
            case"1": $diasemana = "segunda";
                break;
            case"2": $diasemana = "terca";
                break;
            case"3": $diasemana = "quarta";
                break;
            case"4": $diasemana = "quinta";
                break;
            case"5": $diasemana = "sexta";
                break;
            case"6": $diasemana = "sabado";
                break;
        }

        return $diasemana;
    }

// recebe data no formato brasileiro
// (dd/mm/yyyy) e n�mero de dias a contar (prazo),
// retorna o dia �til final ap�s o prazo (formato brasileiro)
    public function DiasUteis($data, $numero_dias) {
        $data = explode("/", $data);
        $dia = $data[0];
        $mes = $data[1];
        $ano = $data[2];
        for ($i = 1; $i <= $numero_dias; $i++) {
            $dias[$i] = date('Y-m-d', mktime(0, 0, 0, $mes, $dia + $i, $ano));
// verifica se � s�bado ou domingo
            $dia_da_semana = $this->DiaSemana($dias[$i]);
            if ($dia_da_semana == "sabado" || $dia_da_semana == "domingo") {
                $numero_dias++;
            } else {
// verifica se o dia da semana � um feriado
                $feriado = $this->findbyAttributes(array('data_feriado' => $dias[$i]));
                if (sizeof($feriado) > 0) {
                    $numero_dias++;
                }
            }
            $data_final = $dias[$i];
        }
        $data_final = date('Y-m-d', mktime(0, 0, 0, $mes, $dia + $numero_dias, $ano));

// volta com a data final para o formato brasileiro
        $data_final = explode("-", $data_final);
        $data_final = $data_final[2] . "/" . $data_final[1] . "/" . $data_final[0];
        return($data_final);
    }

// fun��o trazida do SIAUDI vers�o 1 (antigo)
    function isDiaUtil($data_verif) {
        if (!is_array($_SESSION["feriados"])) {
            $feriados = $this->findAll();
            if (sizeof($feriados) > 0) {
                foreach ($feriados as $vetor_feriados) {
                    $_SESSION["feriados"][] = $vetor_feriados[data_feriado];
                }
            } else {
                $_SESSION["feriados"] = array();
            }
        }


        $diaUtil = false;
        $dataVerifParte = explode("-", $data_verif);
        $diasemana = date("w", mktime(0, 0, 0, $dataVerifParte[1], $dataVerifParte[2], $dataVerifParte[0]));

        if (($diasemana != '0' ) && ($diasemana != '6')) {
            $diaUtil = true;
            foreach ($_SESSION["feriados"] as $diaFeriado) {
                if ($diaFeriado == $data_verif) {
                    $diaUtil = false;
                    break;
                }
            }
        }
        return $diaUtil;
    }

// fun��o trazida do SIAUDI vers�o 1 (antigo)
    function getDataFormatada($dia, $mes, $ano) {
        settype($dia, "integer");
        settype($mes, "integer");
        settype($ano, "integer");
        return date('Y-m-d', mktime(0, 0, 0, $mes, $dia, $ano));
    }

// fun��o trazida do SIAUDI vers�o 1 (antigo)	
    function getDataNumeral($dataTrans) {
        $dataTransParte = explode('-', $dataTrans);
        return mktime(0, 0, 0, $dataTransParte[1], $dataTransParte[2], $dataTransParte[0]);
    }

// fun��o trazida do SIAUDI vers�o 1 (antigo)
// para calcular o n�mero de dias �teis restantes
// em rela��o ao prazo que foi passado.
    function dias_uteis_restantes($data, $qtDias) {
        $arData = explode('-', $data);
        $DiasRestantes = $qtDias;
        $hoje = date('Y-m-d');
        $hojeNum = $this->getDataNumeral($hoje);
        $contDiasUteis = 0;
        while ($contDiasUteis < $qtDias) {
            $novaData = $this->getDataFormatada($arData[2] + 1, $arData[1], $arData[0]);
            if ($this->isDiaUtil($novaData)) {
                if ($this->getDataNumeral($novaData) < $hojeNum) {
                    $DiasRestantes--;
                }
                $contDiasUteis++;
            }
            $arData = explode('-', $novaData);
        }
        $arData = explode('-', $novaData);
        $dataPZO = $arData[2] . "/" . $arData[1] . "/" . $arData[0];
        $dataNum = $this->getDataNumeral($novaData);
        while ($dataNum < $hojeNum) {
            $novaData = $this->getDataFormatada($arData[2] + 1, $arData[1], $arData[0]);
            if ($this->isDiaUtil($novaData)) {
                $DiasRestantes--;
            }
            $arData = explode('-', $novaData);
            $dataNum = $this->getDataNumeral($novaData);
        }
        return ($DiasRestantes);
    }

// Calcula quantos dias existem entre 2 datas.
// Recebe data_inicial e data_final no formato AMERICANO yyyy-mm-dd.
    function diferenca_entre_datas($data_inicial, $data_final) {

// Usa a fun��o strtotime() e pega o timestamp das duas datas:
        $time_inicial = strtotime($data_inicial);
        $time_final = strtotime($data_final);

// Calcula a diferen�a de segundos entre as duas datas:
        $diferenca = $time_final - $time_inicial;

// Calcula a diferen�a de dias
        $dias = (int) floor($diferenca / (60 * 60 * 24))
        ;
        return $dias;
    }

//    Calcula a quantidade de dias �teis entre duas datas
//    Par�metros: 
//            @data_inicial: formato dd/MM/yyyy 
//            @data_final: formato dd/MM/yyyy
    public function CalculaQtdeDiasUteis($data_inicial, $data_final) {
        
        $arData = explode('/', $data_inicial);
        $data_inicial = $this->getDataFormatada($arData[0], $arData[1], $arData[2]);
        $arData = explode('/', $data_final);
        $data_final = $this->getDataFormatada($arData[0], $arData[1], $arData[2]);
        $qtde_dias_uteis = 0;
        while ($data_inicial <= $data_final) {
            if ($this->isDiaUtil($data_inicial)) {
                $qtde_dias_uteis = $qtde_dias_uteis + 1;
            }
            $arData = explode('-', $data_inicial);
            $dia = $arData[2] + 1;
            $mes = $arData[1];
            $ano = $arData[0];
            $data_inicial = $this->getDataFormatada($dia, $mes, $ano);
        }
        return $qtde_dias_uteis;
    }

//    Atualiza os feriados que est�o marcados para repetir todo os anos. 
    public function AtualizaFeriadosAutomaticamente() {
        $feriados = Feriado::model()->findAll("repetir_todo_ano = 't'");
        foreach ($feriados as $vetor_feriado) {

            $data_feriado = $vetor_feriado['data_feriado'];
            $ar_data = explode('/', $data_feriado);
            $data_feriado = $this->getDataFormatada($ar_data[0], $ar_data[1], $ar_data[2] + 1);

            //verifica se a atualiza��o ser� para o pr�ximo ano
            //caso contratorio os registros n�o devem ser atualizados
            //pois, o processo j� foi executado.
            if ((($ar_data[2] + 1) - date('Y')) == 1) {  
                //atualiza registro atual para n�o mais repetir
                $vetor_feriado->repetir_todo_ano = '0'; //0 -> n�o
                $vetor_feriado->save();

                //inserindo o novo registro
                $model_feriado = new Feriado();
                $model_feriado->data_feriado = $data_feriado;
                $model_feriado->nome_feriado = $vetor_feriado->nome_feriado;
                $model_feriado->repetir_todo_ano = '1'; //1 -> sim
                $model_feriado->save();
            }
        }
    }

}