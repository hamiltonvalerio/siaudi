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

class ItemController extends GxController {

    public $titulo = 'Gerenciar Item';

    public function init() {
        if ((!Yii::app()->user->verificaPermissao("Item", "admin")) && (!Yii::app()->request->isAjaxRequest)) {
            $this->redirect(array('site/acessoNegado'));
            exit;
        }

        parent::init();
        $this->defaultAction = 'index';

        $this->menu_acao = array(
        	array('label' => 'Consultar', 'url' => array('item/index')),
            array('label' => 'Incluir', 'url' => array('item/admin')),
        );
    }

    public function actionAdmin($id = 0) {

        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'Item');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new Item;
            $model->data_gravacao = date("Y-m-d");
        }

        if (isset($_POST['Item'])) {

            $model->attributes = $_POST['Item'];
            $model->capitulo_fk = $_POST['capitulo_fk'];
            $model->valor_reais = str_replace(array('.', ','), array('', '.'), $model->valor_reais);
            $model->valor_reais = $model->valor_nao_se_aplica ? null : $model->valor_reais;

            if ($model->capitulo_fk=="") {
                $model->addError("capitulo_fk", "� obrigat�rio o preenchimento do campo Cap�tulo.");        
            } else {

                if ($model->save()) {
                    if ($_POST['inserir_item']) {
                        $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                        $baseUrl = Yii::app()->baseUrl;
                        header("Location: " . $baseUrl . "/item/admin?capitulo_fk=" . $model->capitulo_fk);
                        exit;
                    }

                    if ($_POST['inserir_recomendacao']) {
                        $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                        $baseUrl = Yii::app()->baseUrl;
                        header("Location: " . $baseUrl . "/recomendacao/admin?item_fk=" . $model->id);
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
        	try{
	            // verifica se relat�rio foi homologado
	            // e neste caso impede a exclus�o
	            $item = Item::model()->findByAttributes(array('id' => $id));
	            $capitulo = Capitulo::model()->findByAttributes(array('id' => $item->capitulo_fk));
	            $relatorio = Relatorio::model()->findByAttributes(array('id' => $capitulo->relatorio_fk));
	            if ($relatorio->data_relatorio) {
	                echo "<script language='Javascript'>alert('N�o � poss�vel excluir este item, pois seu \\nrelat�rio j� foi homologado.');</script>";
	            } else {
	                $this->loadModel($id, 'Item')->delete();
	
	                if (Yii::app()->getRequest()->getIsAjaxRequest()) {
	                    $this->setFlashSuccesso('excluir');
	                    echo $this->getMsgSucessoHtml();
	                } else {
	                    $this->setFlashError('excluir');
	                    $this->redirect(array('admin'));
	                }
	            }
        	}catch(Exception $e){
        		if ($e->errorInfo[0] == 23503){
                    $this->setFlash('erro', 'Item n�o pode ser exclu�do, pois o mesmo possui recomenda��o vinculada a ele.');
                	echo  $this->getMsgErroHtml();
        		}
        	}
        }
        else
            throw new CHttpException(400, Yii::t('app', 'Sua requisi��o � inv�lida.'));
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Item('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Item'])) {
            $model->attributes = $_GET['Item'];
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
            'model' => $this->loadModel($id, 'Item'),
        ));
    }

    // recebe o cap�tulo do relat�rio e carrega seus itens
    public function actionCarregaItemAjax() {
        $data = Item::model()->findAll('capitulo_fk=:capitulo_fk ORDER BY nome_item', array(':capitulo_fk' => (int) $_POST['capitulo_fk']));

        $data = CHtml::listData($data, 'id', 'nome_item');

        if (sizeof($data) > 0) {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Selecione'), true);
        } else {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Sem itens'), true);
        }
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }

    // recebe o item do relat�rio e carrega as Unidades Regionais
    public function actionCarregaSuregAjax() {
        $data = UnidadeAdministrativa::model()->sureg_por_item($_POST['item_fk']);

        if ($data[0] != "vazio") {
            $data = CHtml::listData($data, 'id', 'sigla');
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Selecione'), true);
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        } else {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Sem Unidades Regionais'), true);
        }
    }

    // recebe CarregaItemAgrupadoPorCapituloAjax o ID do relat�rio e carrega seus itens agrupados por capitulo
    public function actionCarregaItemAgrupadoPorCapituloAjax() {
        $dados = Array();
        
        $capitulos = Capitulo::model()->findAll('relatorio_fk=:relatorio_fk ORDER BY numero_capitulo_decimal', array(':relatorio_fk' => (int) $_POST['relatorio_fk']));
        if (sizeof($capitulos)) {
            foreach ($capitulos as $vetor_capitulo) {
                $itens = Item::model()->findAll('capitulo_fk=:capitulo_fk order by numero_item', array(':capitulo_fk' => (int) $vetor_capitulo['id']));
                if (sizeof($itens)) {
                    $arr_item = Array();
                    foreach ($itens as $vetor_item) {
                        $arr_item[$vetor_item['id']] = $vetor_item['numero_item'] . ' - ' . $vetor_item['nome_item'];
                    }
                    $dados[$vetor_capitulo['numero_capitulo'] . ' - ' . $vetor_capitulo['nome_capitulo']] = $arr_item;
//                     $dados = $arr_item;
                }
            }
            $auditor_fk = $_REQUEST['RelatorioAcessoItem']['auditor_fk'];
            if (sizeof($auditor_fk) == 1){
            	$auditor = Usuario::model()->findByPk($auditor_fk[0]);
            	$dados_auditor = RelatorioAcessoItem::model()->findAll("nome_login='".$auditor['nome_login']."'");
            	$item_selecionado = array();
            	foreach($dados_auditor as $vetor){
            		$item_selecionado[$vetor->item_fk] = array("selected" => "selected");
            	}
            	echo CHtml::dropDownList('id', 'nome_item', $dados, array('options' => $item_selecionado));
            } else {
//             	$teste = array('86'=>array('selected'=>'selected'));
//             	array('options' => )
            	echo CHtml::dropDownList('id', 'nome_item', $dados);
            }
        }
    }

}