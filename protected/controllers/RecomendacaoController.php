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

class RecomendacaoController extends GxController {

    public $titulo = 'Recomenda��o';

    public function init() {
        if (!Yii::app()->user->verificaPermissao("Recomendacao", "admin") && (!Yii::app()->request->isAjaxRequest)) {
            $this->redirect(array('site/acessoNegado'));
            exit;
        }

        parent::init();
        $this->defaultAction = 'index';

        $this->menu_acao = array(
            array('label' => 'Consultar', 'url' => array('recomendacao/index')),
            array('label' => 'Incluir', 'url' => array('recomendacao/admin')),
        );
    }

    public function actionAdmin($id = 0) {

        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'Recomendacao');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new Recomendacao;
            $model->data_gravacao = date("Y-m-d");
        }

        if (isset($_POST['Recomendacao'])) {
            $model->attributes = $_POST['Recomendacao'];
            $model->capitulo_fk = $_POST['capitulo_fk'];
            $model->item_fk = $_POST['item_fk'];
            $model->unidade_administrativa_fk = $_POST['unidade_administrativa_fk'];
            $model->recomendacao_subcategoria_fk = $_POST['recomendacao_subcategoria_fk'];

            $tipo_recomendacao = RecomendacaoTipo::model()->find("nome_tipo ilike '%recomenda��o%'")->id;
            $tipo_sugestao = RecomendacaoTipo::model()->find("nome_tipo ilike '%sugest�o%'")->id;

            // Se recomenda��o for sugest�o, ent�o n�o tem gravidade, nem categoria
            if ($model->recomendacao_tipo_fk == $tipo_sugestao) {
                $model->recomendacao_gravidade_fk = null;
                $model->recomendacao_categoria_fk = null;
                $model->recomendacao_subcategoria_fk = null;
            }
            $gravar=1;
            
            // verifica se preencheu Relat�rio e Cap�tulo, mas n�o preencheu Item
            if ($_POST["relatorio_fk"] && $_POST["capitulo_fk"] && $_POST["item_fk"] == "") {
                $model->addError("item_fk", "� obrigat�rio o preenchimento do campo Item.");
                $model->addError("unidade_administrativa_fk", "� obrigat�rio o preenchimento do campo Unidade Auditada.");
                $gravar=0;
            } 
            
            if ($model->recomendacao_tipo_fk==$tipo_recomendacao && !$model->recomendacao_gravidade_fk ) {
                $model->addError("recomendacao_gravidade_fk", "� obrigat�rio o preenchimento do campo Gravidade.");
                $gravar=0;
            } 
            
            if ($model->recomendacao_tipo_fk==$tipo_recomendacao && !$model->recomendacao_categoria_fk ) {
                $model->addError("recomendacao_categoria_fk", "� obrigat�rio o preenchimento do campo Categoria.");
                $gravar=0;
            }             

            if ($model->recomendacao_tipo_fk==$tipo_recomendacao && !$model->recomendacao_subcategoria_fk ) {
                $model->addError("recomendacao_subcategoria_fk", "� obrigat�rio o preenchimento do campo Subcategoria.");
                $gravar=0;
            }                         
            
            // S� grava depois de criticar os campos obrigat�rios
                if($gravar==1) {
                if ($model->save()) {
                    if ($_POST['Recomendacao']['bolRecomendacaoPadrao']) {
                        $model_recomendacao_padrao = new RecomendacaoPadrao;
                        $model_recomendacao_padrao->recomendacao = $_POST['Recomendacao']['descricao_recomendacao'];
                        $model_recomendacao_padrao->save();
                    }
                    if ($_POST['inserir_recomendacao']) {
                        $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                        $baseUrl = Yii::app()->baseUrl;
                        header("Location: " . $baseUrl . "/recomendacao/admin?item_fk=" . $model->item_fk);
                        exit;
                    }
                    $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                    $this->redirect(array('index', 'id' => $model->id));
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
            // verifica se relat�rio foi homologado
            // e neste caso impede a exclus�o
            $recomendacao = Recomendacao::model()->findByAttributes(array('id' => $id));
            $item = Item::model()->findByAttributes(array('id' => $recomendacao->item_fk));
            $capitulo = Capitulo::model()->findByAttributes(array('id' => $item->capitulo_fk));
            $relatorio = Relatorio::model()->findByAttributes(array('id' => $capitulo->relatorio_fk));
            if ($relatorio->data_relatorio) {
                echo "<script language='Javascript'>alert('N�o � poss�vel excluir esta recomenda��o, pois seu \\nrelat�rio j� foi homologado.');</script>";
            } else {
                try {
                    $this->loadModel($id, 'Recomendacao')->delete();

                    if (Yii::app()->getRequest()->getIsAjaxRequest()) {
                        $this->setFlashSuccesso('excluir');
                        echo $this->getMsgSucessoHtml();
                    } else {
                        $this->setFlashError('excluir');
                        $this->redirect(array('admin'));
                    }
                } catch (Exception $e) {
                    if ($e->errorInfo[0] == 23503) {
                        $this->setFlash('erro', 'Recomenda��o n�o pode ser exclu�da, pois a mesma est� associada a um item.');
                        echo $this->getMsgErroHtml();
                    }
                }
            }
        }
        else
            throw new CHttpException(400, Yii::t('app', 'Sua requisi��o � inv�lida.'));
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Recomendacao('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Recomendacao'])) {
            $model->attributes = $_GET['Recomendacao'];
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
            'model' => $this->loadModel($id, 'Recomendacao'),
        ));
    }

    public function actionImportacaoAjax() {
        $this->layout = false;
        $model = new Recomendacao();
        $this->setFlash('orientacao', "Pressione Ctrl+F para pesquisar.");
        $this->render('importacao', array(
            'model' => $model,
        ));
    }

}