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

class RelatorioSaidaController extends GxController {

    public $titulo = 'Relat�rios de Auditoria';

    public function init() {
        parent::init();
        $this->defaultAction = 'index';
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $model = new Relatorio();

        $this->render('index', array(
            'model' => $model,
            'titulo' => $this->titulo,
        ));
    }

    // recebe o tipo de relat�rio  (1=> N�o homologado, 2=> Homologado)
    // para carregar combo da RelatorioSaida
    public function actionCarregaRelatorioSaidaAjax() {
        $login = strtolower(Yii::app()->user->login);
        $perfil = strtolower(Yii::app()->user->role);
        $id_und_adm = Yii::app()->user->id_und_adm;
        $perfil = str_replace("siaudi2", "siaudi", $perfil);
        $schema = Yii::app()->params['schema'];
        $tipo_relatorio = $_POST['Relatorio']['tipo_relatorio'];
        $tipo_relatorio2 = ($tipo_relatorio == 1) ? "IS NULL" : "IS NOT NULL";
        $ano = ($tipo_relatorio == 1) ? "" : $_POST["Relatorio"]["ano"];
        $sql_ano = ($ano) ? "and  date_part('year', data_relatorio)=" . $ano : "";

        // consulta padr�o para perfil siaudi_auditor (mostra
        // todos os relat�rios de auditoria) -> verificar se continuar� assim
        $sql = "SELECT relatorio.id, relatorio.numero_relatorio, relatorio.data_relatorio, e.sigla_auditoria
                FROM " . $schema . ".tb_relatorio relatorio 
                    LEFT JOIN  " . $schema . ".tb_especie_auditoria e ON relatorio.especie_auditoria_fk = e.id 
                WHERE data_relatorio " . $tipo_relatorio2 . "  " . $sql_ano . "
                ORDER BY relatorio.id DESC";

        // consulta de relat�rios para perfil siaudi_cliente (mostra
        // somente os relat�rios onde o auditado consta na relatorio_acesso
        if ($perfil == "siaudi_cliente") {
            $sql = "SELECT relatorio.id, relatorio.numero_relatorio, relatorio.data_relatorio, e.sigla_auditoria
                    FROM " . $schema . ".tb_relatorio relatorio 
                        LEFT JOIN  " . $schema . ".tb_especie_auditoria e ON relatorio.especie_auditoria_fk = e.id 
                    WHERE data_relatorio " . $tipo_relatorio2 . " and EXISTS (select 1 from " . $schema . ".tb_relatorio_acesso ra where
                                                                            ra.relatorio_fk = relatorio.id and 
                                                                            ra.nome_login='" . $login . "') ";
            if ($tipo_relatorio == 1) {
                $sql .= " and data_finalizado is not null ";
            }

            $sql .= $sql_ano . " ORDER BY relatorio.id DESC";
        }
        // consulta de relat�rios para perfil siaudi_cliente_item (mostra
        // somente os relat�rios onde o auditado consta na relatorio_acesso_item
        if ($perfil == "siaudi_cliente_item") {
            $sql = "select relatorio.id, relatorio.numero_relatorio, relatorio.data_relatorio, e.sigla_auditoria 
                  from " . $schema . ".tb_relatorio_acesso_item acesso_item 
                 inner join " . $schema . ".tb_item item on 
                       item.id = acesso_item.item_fk 
                 inner join " . $schema . ".tb_capitulo capitulo on 
                       capitulo.id = item.capitulo_fk 
                 inner join " . $schema . ".tb_relatorio relatorio on 
                       relatorio.id = capitulo.relatorio_fk
                  left join  " . $schema . ".tb_especie_auditoria e on 
                       relatorio.especie_auditoria_fk = e.id                        
                 where upper(nome_login) = '" . strtoupper($login) . "'
                   and data_relatorio " . $tipo_relatorio2 . "
                 group by relatorio.id, relatorio.numero_relatorio, relatorio.data_relatorio, e.sigla_auditoria
                 order by relatorio.id DESC";
        }

        if ((string) strpos($perfil, "siaudi_diretor") === (string) 0) {
            //ESTA CONSULTA SER� EXECUTADA PARA TODOS OS PERFIS DE DIRETOR        
            $sql = "SELECT relatorio.id, 
                           relatorio.numero_relatorio, 
                           relatorio.data_relatorio,
                           especie_auditoria.sigla_auditoria
                      FROM " . $schema . ".tb_relatorio relatorio 
                     INNER JOIN " . $schema . ".tb_especie_auditoria especie_auditoria ON 
                           relatorio.especie_auditoria_fk = especie_auditoria.id
                     INNER JOIN " . $schema . ".tb_relatorio_diretoria relatorio_diretoria ON 
                           relatorio_diretoria.relatorio_fk = relatorio.id AND
                           relatorio_diretoria.diretoria_fk = $id_und_adm
                     WHERE (relatorio.numero_relatorio IS NOT NULL) 
                       AND (relatorio.data_regulariza IS NULL) 
                     order by relatorio.id DESC";
        }

        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        $data = $result->readAll();
        
        if ($tipo_relatorio == 0) {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode(''), true);
        } else {

            if (sizeof($data) > 0) {
                echo CHtml::tag('option', array('value' => ''), CHtml::encode('Selecione'), true);
            } else {
                echo CHtml::tag('option', array('value' => ''), CHtml::encode('Sem relat�rios'), true);
            }

            foreach ($data as $vetor) {
                $data_relatorio = MyFormatter::converteData($vetor[data_relatorio]);
                if ($tipo_relatorio == 1) {
                    echo CHtml::tag('option', array('value' => $vetor[id]), CHtml::encode($vetor[id] . " / " . $vetor[sigla_auditoria]), true);
                }
                if ($tipo_relatorio == 2) {
                    echo CHtml::tag('option', array('value' => $vetor[id]), CHtml::encode($vetor[numero_relatorio] . " de " . $data_relatorio), true);
                }
            }
        }
    }

    // recebe o tipo de relat�rio  (1=> N�o homologado, 2=> Homologado)
    // para carregar combo da RelatorioSaida para gerentes
    // (eles podem visualizar TODOS os relat�rios)
    public function actionCarregaRelatorioSaidaGerenteAjax() {
        $tipo_relatorio = $_POST['tipo_relatorio'];
        $unidade_administrativa_fk = $_POST['unidade_administrativa_fk'];
        $ano = $_POST['ano'];
        $data = Relatorio::model()->RelatorioSaidaGerente($tipo_relatorio, $unidade_administrativa_fk, $ano);
        
        if ($tipo_relatorio == 0) {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode(''), true);
        } else {
            if (sizeof($data) > 0) {
                echo CHtml::tag('option', array('value' => ''), CHtml::encode('Selecione'), true);
            } else {
                echo CHtml::tag('option', array('value' => ''), CHtml::encode('Sem relat�rios'), true);
            }
            foreach ($data as $vetor) {
                // monta combo para relat�rios n�o homologados (ID => esp�cie auditoria)
                if (($tipo_relatorio == 1)|| ($tipo_relatorio == 3)||($tipo_relatorio == 4)) {
                    echo CHtml::tag('option', array('value' => $vetor[id]), CHtml::encode($vetor[id] . " - " . $vetor[sigla_auditoria]), true);
                }
                // monta combo para relat�rios homologados (N� => data de homologa��o)
                if ($tipo_relatorio == 2) {
                    $data_relatorio = MyFormatter::converteData($vetor[data_relatorio]);
                    echo CHtml::tag('option', array('value' => $vetor[id]), CHtml::encode($vetor[numero_relatorio] . " de " . $data_relatorio), true);
                }
            }
        }
    }

    // mostra o relat�rio de auditoria com relat�rio, cap�tulos e itens
    public function actionRelatorioSaidaAjax($id = 0) {
        $this->layout = false;
        $model = $this->loadModel($id, 'Relatorio');
        //Salva IP e Login do Usu�rio no log de acesso.
        LogEntrada::model()->SalvaLog($id);
        $this->render('relatorio_saida', array(
            'model' => $model,
        ));
    }

    // Exporta relat�rio de auditoria para PDF,
    public function actionExportarPDFAjax($id = 0) {
        $perfil = strtolower(Yii::app()->user->role);
        $perfil = str_replace("siaudi2", "siaudi", $perfil);
        $login = strtolower(Yii::app()->user->login);
        //Valida��o caso haja altera��o da query string       
        if ($perfil == 'siaudi_cliente_item') {
            if (!Resposta::model()->validaAcessoAoRelatorio($id, $login)) {
                $this->redirect(array('site/acessoNegado'));
            } //o usu�rio logado n�o tem nenhum item liberado para o relat�rio informado. 
        }

        //Verifica se o relat�rio que foi passado foi emitido pelo n�cleo. 
        //O perfil siaudi_gerente_nucleo deve acessar somente os relat�rios emitidos pelo n�cleo.
        //Valida��o referente a altera��o do valor do relat�rio na QueryString. 
        if ($perfil == "siaudi_gerente_nucleo") {
            $relatorio = Relatorio::model()->findAll("id=" . $id . " and nucleo is true");
            if (!sizeof($relatorio)) {
                $this->redirect(array('site/acessoNegado'));
            }
        }


        //Valida��o referente a altera��o do valor do relat�rio na QueryString. 
        if (preg_match("/siaudi_diretor/", $perfil)) {
            $relatorioDiretoria = RelatorioDiretoria::model()->findByAttributes(array('diretoria_fk' => Yii::app()->user->id_und_adm, 'relatorio_fk' => $id));
            if (!sizeof($relatorioDiretoria)) {
                $this->redirect(array('site/acessoNegado'));
            }
        }

        $Relatorio = Relatorio::model()->findByPk($id);

        // verifica se o usu�rio precisa avaliar o auditor,
        // antes de abrir o relat�rio homologado
        
        if ($perfil == 'siaudi_cliente') {
            $verifica_avaliacao = Avaliacao::model()->VerificaAvaliacaoRelatorio($Relatorio);
        }


        // verifica se � poss�vel abrir o relat�rio em PDF
        // de acordo com o perfil do usu�rio
        $autorizar_pdf = Relatorio::model()->Relatorio_Autorizar_PDF($Relatorio->data_relatorio);
        if ($autorizar_pdf) {
            //salva IP e Login do Usu�rio no log de acesso.
            LogEntrada::model()->SalvaLog($id);

            $html2pdf = Yii::app()->ePdf->HTML2PDF();
            $arquivo = utf8_encode($this->GerarRelatorioPDF($id));
            //echo($arquivo); exit; 
            $html2pdf->pdf->SetAuthor('AUTOR');
            $html2pdf->pdf->SetTitle(utf8_encode('Relat�rio de Auditoria'));
            $html2pdf->pdf->SetSubject(utf8_encode('Relat�rio'));
            $html2pdf->pdf->SetKeywords(utf8_encode('Siaudi, Relat�rio, Auditoria'));
            //$html2pdf->addFont("verdana", "regular", Yii::app()->basePath . "\common\extensions\html2pdf\\fonts\\verdana.ttf");
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->WriteHTML($arquivo);
            //$html2pdf->createIndex(utf8_encode('�ndice'),20,13,false,true,2,'helvetica');
            $html2pdf->Output('relatorio.pdf');
            exit;
        } else {
            $this->redirect(array('site/acessoNegado'));
        }
    }

    public function GerarRelatorioPDF($id = 0) {
        $perfil = strtolower(Yii::app()->user->role);
        $perfil = str_replace("siaudi2", "siaudi", $perfil);
        $html = "";

        if ($perfil == 'siaudi_cliente_item') {
            include_once(Yii::app()->basePath . '/views/relatorioSaida/pdf/pdf_relatorio_cliente_item.inc');
        } else {
            include_once(Yii::app()->basePath . '/views/relatorioSaida/pdf/pdf_relatorio.inc');
        }
        //echo($html); exit; 
        return ($html);
    }

    // mostra o relat�rio de pend�ncias
    public function actionRelatorioPendenciasAjax($id = 0) {
        $model = new Relatorio();

        $exercicio = $_GET["exercicio"];
        if (isset($exercicio) && (!is_numeric($exercicio) || strlen($exercicio) != 4)) {
            $model->addError("valor_exercicio", "Exerc�cio incorreto ou n�o informado");
        } else {
            $exercicio_correto = 1;
            if ($id) {
                $model = $this->loadModel($id, 'Relatorio');
            }
        }
        if (!$exercicio) {
            $exercicio_correto = 0;
        }
        
        $this->subtitulo = "Acompanhar Pend�ncias dos Relat�rios de Auditoria";
        $this->render('relatorio_pendencias', array(
            'model' => $model,
            'exercicio_correto' => $exercicio_correto,
        ));
    }

    // mostra o relat�rio de registros de acessos
    public function actionRegistrosAcessosAjax($id = 0) {
        $this->titulo = "Pesquisar Registros de Acessos de Auditores/Clientes";
        if ($id) {
            $model = $this->loadModel($id, 'Relatorio');
        } else {
            $model = new Relatorio();
        }

        $critica_busca = 0; // vari�vel para verificar se existe alguma cr�tica na busca
        if (!isset($_GET['filtro_acesso']) && $_GET) {
            $model->addError("filtro_acesso", "Informe o filtro de acessos.");
        }

        // verifica se preencheu todos os campos caso tenha selecionado
        // o radiobox "cliente"        
        if ($_GET['Relatorio'] && $_GET['filtro_acesso'] == "cliente" &&
                (!$_GET['Relatorio']['id'] ||
                !$_GET['Relatorio']['periodo_inicio'] ||
                !$_GET['Relatorio']['periodo_fim'])) {
            $model->addError("", "O preenchimento de todos os campos � obrigat�rio.");
            $critica_busca = 1;
        }

        // verifica se preencheu todos os campos caso tenha selecionado
        // o radiobox "auditor"
        if ($_GET['Relatorio'] && $_GET['filtro_acesso'] == "auditor" &&
                (!$_GET['Relatorio']['periodo_inicio'] ||
                !$_GET['Relatorio']['periodo_fim'])) {
            $model->addError("", "O preenchimento de todos os campos � obrigat�rio.");
            $critica_busca = 1;
        }

        // converte datas para formato americano e compara
        // se data inicial � maior que a final
        $periodo_inicio = $_GET["Relatorio"]["periodo_inicio"];
        $periodo_fim = $_GET["Relatorio"]["periodo_fim"];
        $periodo_inicio2 = explode("/", $periodo_inicio);
        $periodo_inicio2 = $periodo_inicio2[2] . $periodo_inicio2[1] . $periodo_inicio2[0];
        $periodo_inicio2 = (is_numeric($periodo_inicio2)) ? $periodo_inicio2 : 0;
        $periodo_fim2 = explode("/", $periodo_fim);
        $periodo_fim2 = $periodo_fim2[2] . $periodo_fim2[1] . $periodo_fim2[0];
        $periodo_fim2 = (is_numeric($periodo_fim2)) ? $periodo_fim2 : 0;
        $data_atual = date(Ymd);
            
        if (($periodo_inicio != "" || $periodo_fim != "") && (strlen($periodo_inicio2) < 8 || strlen($periodo_fim2) < 8)) {
            $model->addError("", "Per�odo informado incorretamente.");
            $critica_busca = 1;
        } else {

            if (isset($periodo_inicio) && isset($periodo_fim) && $periodo_inicio2 > $periodo_fim2) {
                $model->addError("", "Data inicial maior que a data final.");
                $critica_busca = 1;
            }

            if ($periodo_inicio2 > $data_atual || $periodo_fim2 > $data_atual) {
                $model->addError("", "O per�odo n�o pode ultrapassar a data atual.");
                $critica_busca = 1;
            }
            
           // verifica se as datas s�o v�lidas.           
            if(strlen($periodo_inicio2)==8 && strlen($periodo_fim2)==8) {
                $periodo_inicio2 = explode("/", $periodo_inicio);
                $periodo_fim2 = explode("/", $periodo_inicio);
                $checa_periodo_inicio = checkdate($periodo_inicio2[1],$periodo_inicio2[0],$periodo_inicio2[2]); //mes, dia, ano
                $checa_periodo_fim = checkdate($periodo_fim2[1],$periodo_fim2[0],$periodo_fim2[2]); //mes, dia, ano
                if (!$checa_periodo_inicio || !$checa_periodo_fim) {
                    $model->addError("", "Per�odo informado incorretamente.");
                    $critica_busca = 1;
                }            
            }
        }


        $parametros = null;
        // se par�metros foram passados corretamente, ent�o faz a busca
        if ($_GET['Relatorio'] && $_GET['filtro_acesso'] && !$critica_busca) {
            $parametros = array('filtro_acesso' => $_GET['filtro_acesso'],
                'relatorio_id' => $_GET['Relatorio']['id'],
                'periodo_inicio' => $_GET['Relatorio']['periodo_inicio'],
                'periodo_fim' => $_GET['Relatorio']['periodo_fim'],
            );
            $busca_registros_acessos = Relatorio::model()->RelatorioRegistrosAcessos($parametros);
        }

        $this->render('relatorio_registro_acessos', array(
            'model' => $model,
            'parametros' => $parametros,
            'busca_registros_acessos' => $busca_registros_acessos,
        ));
    }

    // carrega relat�rios por Unidade Regional ou unidade auditada
    public function actionCarregaRelatorioSuregAjax() {
        $unidade_administrativa_fk = $_POST['Relatorio']['unidade_administrativa_fk'];
        $data = RelatorioSureg::model()->findAllByAttributes(array('unidade_administrativa_fk' => $unidade_administrativa_fk));

        if (sizeof($data) > 0) {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Selecione'), true);
        } else {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Sem relat�rios'), true);
        }

        foreach ($data as $vetor) {
            $Relatorio = Relatorio::model()->findByPk($vetor->relatorio_fk);
            $EspecieAuditoria = EspecieAuditoria::model()->findByPk($Relatorio->especie_auditoria_fk);

            // monta combo para relat�rios n�o homologados (ID => esp�cie auditoria)
            if (!$Relatorio->data_relatorio) {
                echo CHtml::tag('option', array('value' => $Relatorio->id), CHtml::encode("ID " . $Relatorio->id . " - " . $EspecieAuditoria->sigla_auditoria), true);
            } else {
                // monta combo para relat�rios homologados (N� => data de homologa��o)
                echo CHtml::tag('option', array('value' => $Relatorio->id), CHtml::encode($Relatorio->numero_relatorio . " de " . $Relatorio->data_relatorio . " - " . $EspecieAuditoria->sigla_auditoria), true);
            }
        }
    }

}