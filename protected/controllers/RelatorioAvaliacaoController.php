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

class RelatorioAvaliacaoController extends GxController {

    public $titulo = 'Relat�rio de Avalia��o do Auditor';

    public function init() {
        parent::init();
        $this->defaultAction = 'index';

        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('relatorioAvaliacao/index')),
        );
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Avaliacao();
        $valor_exercicio = $_POST['Avaliacao']['valor_exercicio'];
        $usuario_fk = $_POST['Avaliacao']['usuario_fk'];
        $bNaoEncontrouRegistro = false;
        if ($_POST != null) {
            if (((($valor_exercicio != null) || ($valor_exercicio != 0))) && (!(!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4))) {
                if ($usuario_fk != ''){
	            	$dados = $model->RecuperaAvaliacaoAuditor($valor_exercicio, $usuario_fk);
	                if (sizeof($dados)) {
	                    $total_homologados = Avaliacao::model()->RecuperaQtdeDeRelHomologados($usuario_fk, $valor_exercicio);
	                    $total_homologados_avaliados = Avaliacao::model()->RecuperaQtdeDeRelHomologadosAvaliados($usuario_fk, $valor_exercicio);
	                    $total_finalizados = Avaliacao::model()->RecuperaQtdeDeRelFinalizados($usuario_fk, $valor_exercicio);
	                    $total_finalizados_avaliados = Avaliacao::model()->RecuperaQtdeDeRelFinalizadosAvaliados($usuario_fk, $valor_exercicio);
	                    $total_avaliacao = Avaliacao::model()->RecuperaQtdeDeAvaliacoes($usuario_fk, $valor_exercicio);
	                    $this->render('relatorio_avaliacao', array(
	                        'total_homologados' => $total_homologados,
	                        'total_homologados_avaliados' => $total_homologados_avaliados,
	                        'total_finalizados' => $total_finalizados,
	                        'total_finalizados_avaliados' => $total_finalizados_avaliados,
	                        'total_avaliacao' => $total_avaliacao,
	                        'valor_exercicio' => $valor_exercicio,
	                        'model' => $model,
	                        'dados' => $dados
	                    ));
	                } else {
	                    $bNaoEncontrouRegistro = true;
	                }
                } else {
                	$model->addError("usuario_fk", "Favor preencher os campos obrigat�rios.");
                }
            } else {
                $model->addError("valor_exercicio", "Favor preencher os campos obrigat�rios.");
            }
        }
        if ($dados == Null) {
            $this->render('index', array(
                'model' => $model,
                'bNaoEncontrouRegistro' => $bNaoEncontrouRegistro,
                'titulo' => $this->titulo,
            ));
        }
    }

    public function actionCarregaAuditorPorExercicioAjax() {
        $data = Avaliacao::model()->with('usuarioFk')->findAll("perfil_fk=:perfil_fk and date_part('year', data)=:valor_exercicio", array(':perfil_fk' => (int) Perfil::model()->find("nome_interno = 'SIAUDI_AUDITOR'")->id, ':valor_exercicio' => (int) $_POST['valor_exercicio']));
        if (sizeof($data)) {
            foreach ($data as $vetor) {
                $usuarios[] = $vetor->usuarioFk;
            }
            $data = CHtml::listData($usuarios, 'id', 'nome_usuario');
            if (sizeof($data) > 0) {
                echo CHtml::tag('option', array('value' => ''), CHtml::encode('Selecione'), true);
            } else {
                echo CHtml::tag('option', array('value' => ''), CHtml::encode('Sem itens'), true);
            }
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        } else {
            echo '';
        }
    }

}