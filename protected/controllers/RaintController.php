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

class RaintController extends GxController {

    public $titulo = 'RAINT';

    public function init() {
        if (!Yii::app()->user->verificaPermissao("Raint", "admin")) {
            $this->redirect(array('site/acessoNegado'));
            exit;
        }

        parent::init();
        $this->defaultAction = 'index';

        // verifica se usu�rio tem permiss�o de edi��o para o Raint
        $acesso_raint = Raint::model()->acesso_raint();
        if ($acesso_raint) {
            $this->menu_acao = array(
                array('label' => 'Consultar', 'url' => array('Raint/index')),
                array('label' => 'Incluir', 'url' => array('Raint/admin')),
            );
        } else {
            $this->menu_acao = array(
                array('label' => 'Consultar', 'url' => array('Raint/index')),
            );
        }
    }

    // monta a �rvore do Raint para levar ao JSON
    public function arvore_raint(&$list, $parent) {
        $tree = array();
        foreach ($parent as $k => $l) {
            if (isset($list[$l['id']])) {
                $l['children'] = $this->arvore_raint($list, $list[$l['id']]);
            }
            $tree[] = $l;
        }
        return $tree;
    }

    public function actionCarregaRaintAjax() {
        // gera vetor para carregar o JSON 
        if ($_REQUEST["exercicio"]) {
            $raint = Raint::carrega_raint($_REQUEST["exercicio"]);
            if (is_array($raint)) {
                $raint_novo = array();
                foreach ($raint as $a) {
                    $raint_novo[$a['numero_item_pai']][] = $a;
                }
                $arvore = $this->arvore_raint($raint_novo, $raint_novo[0]); // changed

                if (is_array($arvore)) {
                    echo json_encode($arvore);
                }
            } else {
                echo '""';
            }
        }
        exit;
    }

    public function actionAdmin($id = 0) {
        $limite_inferior = Yii::app()->params['limite_inferior_exercicio'];
        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'Raint');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new Raint;
            $model->data_gravacao = date("Y-m-d");
        }

        
        // valida se usu�rio preencheu todos os campos do exerc�cio
        if (isset($_REQUEST["Raint"]["valor_exercicio"]) || isset($_REQUEST["exercicio"])) {
            $exercicio = (isset($_REQUEST["Raint"]["valor_exercicio"])) ? $_REQUEST["Raint"]["valor_exercicio"] : $_REQUEST["exercicio"];
            if (!is_numeric($exercicio) || strlen($exercicio) != 4) {
                $model->addError("valor_exercicio", "Exerc�cio incorreto ou n�o informado");
                $this->render('admin', array(
                    'model' => $model,
                    'dados' => $dados,
                    'titulo' => $this->titulo,
                ));
                exit;
            }

            if ($exercicio < $limite_inferior) {
                $model->addError("valor_exercicio", "Exerc�cio inferior ao limite");
            }
        }

        if ($_REQUEST["consultar"] == 1) {
            $this->subtitulo = 'Consultar';
        } else {
            // verifica se usu�rio tem permiss�o de edi��o para o Raint
            $acesso_raint = Raint::model()->acesso_raint();
            if (!$acesso_raint) {
                $this->redirect(array('site/acessoNegado'));
                exit;
            }
        }


        if ($_REQUEST["raint"]) {
            $model_raint = Raint::model()->prepara_raint();
            if ($_REQUEST["raint_sair"] == 1) {
            	$this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                $this->redirect(array('index'));
            }
            if ($_REQUEST["raint_sair"] == 2) {
                $this->redirect(array('ExportarPDFAjax', 'exercicio' => $_REQUEST["exercicio"]));
            }
        }

        if ($_REQUEST["RaintFormValidar_dados"] == 1 && $_REQUEST["Raint"]["valor_exercicio"] > $limite_inferior) {
            $this->redirect(array('admin', 'exercicio' => $_REQUEST["Raint"]["valor_exercicio"]));
        }

        if ($_REQUEST["RaintFormValidar_dados"] && !$_REQUEST["Raint"]["valor_exercicio"]) {
            $this->redirect(array('admin'));
        }

        if ($_REQUEST["Raint"]["valor_exercicio"]) {
            $this->redirect(array('admin', 'exercicio' => $_REQUEST["Raint"]["valor_exercicio"]));
        }


        if (isset($_REQUEST['Raint'])) {
            $model->attributes = $_REQUEST['Raint'];

            if ($model->save()) {
                $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                $this->redirect(array('index', 'id' => $model->id));
            }
        }

        $this->render('admin', array(
            'model' => $model,
            'titulo' => $this->titulo,
        ));
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Raint('search');

        
        $valor_exercicio = (isset($_REQUEST["Raint"]["valor_exercicio"])) ? $_REQUEST["Raint"]["valor_exercicio"] : $_REQUEST["exercicio"];

        $limite_inferior =Yii::app()->params['limite_inferior_exercicio'];
        if ($valor_exercicio){
	        if ($valor_exercicio  < $limite_inferior  ){
	        	$model->addError("valor_exercicio", "Exerc�cio inferior ao limite");
	                $this->render('admin', array(
	                    'model' => $model,
	                    'dados' => $dados,
	                    'titulo' => $this->titulo,
	                ));
	                exit;
	        }     
        }
        
        if ($_REQUEST && isset($valor_exercicio)) {
            if (!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4) {
                $model->addError("valor_exercicio", "Informe o exerc�cio");
            } else {
                $valor_exercicio = $_REQUEST["Raint"]["valor_exercicio"];
                $this->redirect(array('admin', 'exercicio' => $valor_exercicio, "consultar" => '1'));

                $model->unsetAttributes();  // clear any default values
                if (isset($_REQUEST['Raint'])) {
                    $model->attributes = $_REQUEST['Raint'];
                    $dados = $model->search();
                }
            }
        }

        $this->render('index', array(
            'model' => $model,
            'dados' => $dados,
            'titulo' => $this->titulo,
        ));
    }

    public function actionView($id) {
        $this->layout = false;
        $this->render('view', array(
            'model' => $this->loadModel($id, 'Raint'),
        ));
    }

    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            $this->loadModel($id, 'Raint')->delete();

            if (Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->setFlashSuccesso('excluir');
                echo $this->getMsgSucessoHtml();
            } else {
                $this->setFlashError('excluir');
                $this->redirect(array('admin'));
            }
        }
        else
            throw new CHttpException(400, Yii::t('app', 'Sua requisi��o � inv�lida.'));
    }

    // ==========================================================
    //                FUN��ES PARA GERAR RAINT
    // ==========================================================    


    public function actionExportarPDFAjax() {
        # HTML2PDF has very similar syntax
        $html2pdf = Yii::app()->ePdf->HTML2PDF();
        $arquivo = utf8_encode($this->GerarRaintPDF());
        //echo($arquivo); exit; 
        $html2pdf->pdf->SetAuthor('AUTOR');
        $html2pdf->pdf->SetTitle('Relat�rio Anual de Atividades da Auditoria Interna - RAINT');
        $html2pdf->pdf->SetSubject('RAINT');
        $html2pdf->pdf->SetKeywords('RAINT');
        //$html2pdf->addFont("verdana", "regular", Yii::app()->basePath . "\common\extensions\html2pdf\\fonts\\verdana.ttf");
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->WriteHTML($arquivo);
        $html2pdf->createIndex(utf8_encode('�ndice'), 20, 13, false, true, 2, 'helvetica');
        $html2pdf->Output('raint.pdf', 'D');
        exit;
    }

    public function GerarRaintPDF() {
        $html = "";
        include_once(Yii::app()->basePath . '/views/raint/pdf/pdf_raint.inc');
        return($html);
    }

}