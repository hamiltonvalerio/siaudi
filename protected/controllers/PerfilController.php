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

class PerfilController extends GxController {

    public $titulo = 'Gerenciar Perfil';

    public function init() {
        parent::init();
        $this->defaultAction = 'index';

        if (!Yii::app()->user->verificaPermissao("Perfil", "admin")) {
            $this->menu_acao = array(
                array('label' => 'Consultar', 'url' => array('Perfil/index')),
            );                        
        }else {
            $this->menu_acao = array(
            	array('label' => 'Consultar', 'url' => array('Perfil/index')),
                array('label' => 'Incluir', 'url' => array('Perfil/admin')),
            );         
        }
    }

    public function actionAdmin($id = 0) {

        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'Perfil');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new Perfil;
        }

        if (isset($_POST['Perfil'])) {
            $model->attributes = $_POST['Perfil'];

            if ($model->save()) {
                $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                $this->redirect(array('index?' . $_SERVER['QUERY_STRING']));
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
            $this->loadModel($id, 'Perfil')->delete();

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
        $model = new Perfil('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Perfil'])) {
            $model->attributes = $_GET['Perfil'];
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
            'model' => $this->loadModel($id, 'Perfil'),
        ));
    }

}