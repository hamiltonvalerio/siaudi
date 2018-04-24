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

class RiscoController extends GxController {

	public $titulo = 'Tabela de Riscos';
	public $group;

	public function init() {
		if (!Yii::app()->user->verificaPermissao("Risco", "admin")) {
			$this->redirect(array('site/acessoNegado'));
			exit;
		}


		parent::init();
		$this->defaultAction = 'index';

		$this->menu_acao = array(
		array('label' => 'Consultar', 'url' => array('Risco/index')),
		);
	}

	public function actionAdmin($id = 0) {
		//$model_criterio = new Criterio;
		$model_processo = new Processo;

		if (!empty($id)) {
			$this->subtitulo = 'Atualizar';
			$model = $this->loadModel($id , 'Risco');
		} else {
			$this->subtitulo = 'Consultar';
			$model = new Subrisco;

		}

		if($_POST['Nota_Acao_Criterio']){
			//$salvar = $model->salvar_tabela_risco($_POST['Nota_Acao_Criterio']);
			$this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
			$this->redirect(array('index'));
		}


		if (isset($_POST['Subrisco'])) {

			$exercicio = $_POST['Subrisco']['exercicio'];
			$model->attributes = $_POST['Subrisco'];
			if ($exercicio){
				$this->redirect(array('admin', 'exercicio' => $exercicio ));
			}
		}

		if (isset($_GET['exercicio'])) {
			$exercicio = $_GET['exercicio'];
			$model_criterio_dados = Criterio::model()->findAllByAttributes(array('valor_exercicio'=>$exercicio), array('order'=>'nome_criterio'));
			//$model_acao_dados = Acao::model()->findAllByAttributes(array('valor_exercicio'=>$exercicio ));
			$model_processo_dados = Processo::carrega_tabela_risco($exercicio);
		}

		$this->render('admin', array(
            'model_criterio' => $model_criterio,
            'model_criterio_dados' => $model_criterio_dados,
            'model_processo' => $model_processo,
            'model_processo_dados' => $model_processo_dados,
            'model' => $model,
            'titulo' => $this->titulo,
		));
	}


	/*
	 public function actionGerarPDFAjax($id = 0) {
	 //$model_criterio = new Criterio;
	 $model_processo = new Processo;

	 if (!empty($id)) {
	 $this->subtitulo = 'Atualizar';
	 $model = $this->loadModel($id , 'Risco');
	 } else {
	 $this->subtitulo = 'Inserir';
	 $model = new Subrisco;

	 }

	  
	 if (isset($_GET['exercicio'])) {
	 $exercicio = $_GET['exercicio'];
	 $model_criterio_dados = Criterio::model()->findAllByAttributes(array('valor_exercicio'=>$exercicio), array('order'=>'nome_criterio'));
	 //$model_acao_dados = Acao::model()->findAllByAttributes(array('valor_exercicio'=>$exercicio ));
	 $model_processo_dados = Processo::carrega_tabela_risco($exercicio);
	 }

	 $this->render('gerar_pdf', array(
	 'model_criterio' => $model_criterio,
	 'model_criterio_dados' => $model_criterio_dados,
	 'model_processo' => $model_processo,
	 'model_processo_dados' => $model_processo_dados,
	 'model' => $model,
	 'titulo' => $this->titulo,
	 ));
	 }*/

	public function actionDelete($id) {
		$this->layout = false;
		if (Yii::app()->getRequest()->getIsPostRequest()) {
			$this->loadModel($id, 'Risco')->delete();

			if (Yii::app()->getRequest()->getIsAjaxRequest()) {
				$this->setFlashSuccesso('excluir');
				echo  $this->getMsgSucessoHtml();
			} else {
				$this->setFlashError('excluir');
				$this->redirect(array('admin'));
			}

		} else
		throw new CHttpException(400, Yii::t('app', 'Sua requisi��o � inv�lida.'));
	}

	public function actionIndex() {
		$this->subtitulo = 'Consultar';
		$dados = null;
		$model = new Subrisco();
		$model_processo = new Processo();
		$model->unsetAttributes();  // clear any default values



		$exercicio = $_GET['Subrisco']['exercicio'];
		if (isset($exercicio)){
			if (!is_numeric($exercicio) || strlen($exercicio) != 4){
				$model->addError("valor_exercicio", "Exerc�cio incorreto ou n�o informado");
				$this->render('index', array(
                    'model' => $model,
                    'dados' => $dados,
                    'titulo' => $this->titulo,
				));
				exit;
			}else {
				$this->redirect(array('admin', 'exercicio' => $exercicio, 'consultar'=>'1' ));
			}
		}

		if (isset($_GET['Subrisco'])) {
			$model->attributes = $_GET['Subrisco'];
			$dados = $model->search($_GET['Subrisco']['exercicio']);
		}

		 
		$this->render('index', array(
            'model' => $model,
            'model_processo' => $model_processo,             
            'dados' => $dados,
            'titulo' => $this->titulo,
		));

	}

	public function actionView($id) {
		$this->layout = false;
		$this->render('view', array(
                'model' => $this->loadModel($id, 'Risco'),
		));
	}
}