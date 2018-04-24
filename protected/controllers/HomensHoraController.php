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

class HomensHoraController extends GxController {

    public   $titulo = 'Tabela de Homens Hora';
    
    public function init() {
        if (!Yii::app()->user->verificaPermissao("HomensHora", "admin")) {
            $this->redirect(array('site/acessoNegado')); 
            exit;
        } 
        
        parent::init();
        $this->defaultAction = 'index';

        $this->menu_acao = array(
        	array('label' => 'Consultar', 'url' => array('HomensHora/index')),
            array('label' => 'Incluir', 'url' => array('HomensHora/admin')),
        );
    }       

    public function actionAdmin($id = 0) {

        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id , 'HomensHora');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new HomensHora;
        }
        
        if (isset($_POST['HomensHora'])) {
            $model->attributes = $_POST['HomensHora'];

                $valor_exercicio = $_POST['HomensHora']['valor_exercicio'];
         	$limite_inferior =Yii::app()->params['limite_inferior_exercicio'];  
	        if ($valor_exercicio  < $limite_inferior  ){
	        	$model->addError("valor_exercicio", "Exerc�cio inferior ao limite");
	                $this->render('admin', array(
	                    'model' => $model,
	                    'dados' => $dados,
	                    'titulo' => $this->titulo,
	                ));
	                exit;
	        }              
          
          		if ($model->save()) {
                              $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
                    $this->redirect(array('index', 'id' => $model->id));
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
            $this->loadModel($id, 'HomensHora')->delete();

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
        $model = new HomensHora('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['HomensHora'])) {
            $exercicio = $_GET['HomensHora']['valor_exercicio'];
            if ($exercicio!="" && (!is_numeric($exercicio) || strlen($exercicio)!=4)) {
                $model->addError("valor_exercicio", "Exerc�cio incorreto.");
                $this->render('_search', array(
                    'model' => $model,
                    'dados' => $dados,
                    'titulo' => $this->titulo,
                ));
                exit;
            }
            
            $model->attributes = $_GET['HomensHora'];
            $dados = $model->search();
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
                'model' => $this->loadModel($id, 'HomensHora'),
        ));
    }
}