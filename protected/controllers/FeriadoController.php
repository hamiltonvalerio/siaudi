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

class FeriadoController extends GxController {

	public $titulo = 'Feriado';

	public function init() {
		if (!Yii::app()->user->verificaPermissao("Feriado", "admin")) {
			$this->redirect(array('site/acessoNegado'));
			exit;
		}

		parent::init();
		$this->defaultAction = 'index';
		
		$this->menu_acao = array(
        	array('label' => 'Consultar', 'url' => array('Feriado/index')),
            array('label' => 'Incluir', 'url' => array('Feriado/admin')),
        );
	}

	public function actionAdmin($id = 0) {

		if (!empty($id)) {
			$this->subtitulo = 'Atualizar';
			$model = $this->loadModel($id, 'Feriado');
		} else {
			$this->subtitulo = 'Inserir';
			$model = new Feriado;
		}

		if (isset($_POST['Feriado'])) {
			$model->attributes = $_POST['Feriado'];

			$data_feriado = explode("/", $_POST["Feriado"]["data_feriado"]); //formato brasileiro
			$data_valida = checkdate($data_feriado[1], $data_feriado[0], $data_feriado[2]); //ordem dos par�metros: mes, dia e ano.
			if (!$data_valida) {
				$model->addError("data_feriado", "Data inv�lida");
			} else {
				if ($model->save()) {
					$this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
					$this->redirect(array('index?' . $_SERVER['QUERY_STRING'])); 
				}
			}
		}

		$this->render('admin', array(
            'model' => $model,
            'titulo' => $this->titulo,
		));
	}

	public function actionDelete($id) {
		$this->layout = false;
		if (Yii::app()->getRequest()->getIsPostRequest()) {
			$this->loadModel($id, 'Feriado')->delete();

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

	public function actionIndex() {
		$this->subtitulo = 'Consultar';
		$dados = null;
		$model = new Feriado();

		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Feriado'])) {
			
			if ($_GET['Feriado']["data_feriado"]!="") {
				$data_feriado = explode("/", $_GET["Feriado"]["data_feriado"]); //formato brasileiro
				if ((!is_numeric($data_feriado[0])) || (!is_numeric($data_feriado[1])) || (!is_numeric($data_feriado[2]))){
					$model->addError("data_feriado", "Data inv�lida");
					$this->render('_search', array(
                    'model' => $model,
                    'dados' => $dados,
                    'titulo' => $this->titulo,
					));
					exit;
				}
				$data_valida = checkdate($data_feriado[1], $data_feriado[0], $data_feriado[2]); //ordem dos par�metros: mes, dia e ano.
				if (!$data_valida) {
					$model->addError("data_feriado", "Data inv�lida");
					$this->render('_search', array(
                    'model' => $model,
                    'dados' => $dados,
                    'titulo' => $this->titulo,
					));
					exit;
				}
			}
		}
		
		$model->attributes = $_GET['Feriado'];
		$model->data_feriado = sizeof($data_feriado) ? date('Y-m-d', mktime(0, 0, 0, $data_feriado[1], $data_feriado[0], $data_feriado[2])) : "";
		$model->nome_feriado = $_GET['Feriado']['nome_feriado'];		
		if ($_GET['yt0']){
			$dados = $model->search();
		}

		$model->data_feriado = $_GET['Feriado']["data_feriado"];

		$this->render('index', array(
            'model' => $model,
            'dados' => $dados,
            'titulo' => $this->titulo,
		));
	}

	public function actionView($id) {
		$this->layout = false;
		$this->render('view', array(
            'model' => $this->loadModel($id, 'Feriado'),
		));
	}

}