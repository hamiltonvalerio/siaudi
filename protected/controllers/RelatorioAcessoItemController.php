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

class RelatorioAcessoItemController extends GxController {

    public $titulo = 'Permiss�o de Acesso ao Item';

    public function init() {
        if (!Yii::app()->user->verificaPermissao("RelatorioAcessoItem", "admin")) {
            $this->redirect(array('site/acessoNegado'));
            exit;
        }

        parent::init();
        $this->defaultAction = 'index';
    }

    public function actionAdmin() {
        $this->subtitulo = 'Inserir';
        $model = new RelatorioAcessoItem;
        
        if ($_POST['yt0'] == 'Confirmar') {
            if ($this->validaInsert($model)) {
                if (isset($_POST['RelatorioAcessoItem'])) {
                    $model->InserirRegistros($_POST);
                }
            }
        }

        $this->render('admin', array(
            'model' => $model,
            'titulo' => $this->titulo,
        ));
    }

    public function validaInsert($model) {
        $bolNaoEncontrouErro = true;
        if (!$_POST['relatorio_fk']) {
            $bolNaoEncontrouErro = false;
            $model->addError('relatorio_fk', 'O relat�rio deve ser informado.');
        }
        if ((!isset($_POST['RelatorioAcessoItem']['item_fk'])) && (!$_POST['RelatorioAcessoItem']['liberar_todos_itens'])) {
            $bolNaoEncontrouErro = false;
            $model->addError('item_fk', 'Deve ser selecionado pelo menos um item.');
        }
        if (!$_POST['RelatorioAcessoItem']['auditor_fk']) {
            $bolNaoEncontrouErro = false;
            $model->addError('nome_login', 'O auditor deve ser informado.');
        }
        return $bolNaoEncontrouErro;
    }

    public function validaConsulta($model) {
        $bolNaoEncontrouErro = true;
        if ($_GET != null) {
            if (!(($_GET['RelatorioAcessoItem']['id_login']) || ($_GET['RelatorioAcessoItem']['unidade_administrativa_fk']))) {
                $bolNaoEncontrouErro = false;
                $model->addError('id_login', 'Pelo menos um par�metro deve ser informado.');
            }
        }
        return $bolNaoEncontrouErro;
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new RelatorioAcessoItem('search');

        if ($this->validaConsulta($model)) {
            $id = $_POST[relatorio_acesso_item_id];
            if ($id) {
                $this->loadModel($id, 'RelatorioAcessoItem')->delete();
            } elseif (($_GET['RelatorioAcessoItem']['id_login']) || ($_GET['RelatorioAcessoItem']['unidade_administrativa_fk'])) {
                $model->unsetAttributes();  // clear any default values
                if (isset($_GET['RelatorioAcessoItem'])) {
                    $model->attributes = $_GET['RelatorioAcessoItem'];
                    $dados = $model->BuscaRegistro($_GET['RelatorioAcessoItem']['id_login'], $_GET['RelatorioAcessoItem']['unidade_administrativa_fk']);
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
            'model' => $this->loadModel($id, 'RelatorioAcessoItem'),
        ));
    }

}