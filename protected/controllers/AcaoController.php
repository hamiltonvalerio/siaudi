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

class AcaoController extends GxController {

    public $titulo = 'Gerenciar A��o de Auditoria';

    public function init() {
        if (!Yii::app()->user->verificaPermissao("Acao", "admin")) {
            $this->redirect(array('site/acessoNegado'));
            exit;
        }

        parent::init();
        $this->defaultAction = 'index';

        $this->menu_acao = array(
        	array('label' => 'Consultar', 'url' => array('Acao/index')),
            array('label' => 'Incluir', 'url' => array('Acao/admin')),
        );
    }

    public function actionAdmin($id = 0) {
        $model_acaomes = new AcaoMes();
        $model_acao_sureg = new AcaoSureg();
        $model_processo = new Processo();
        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'Acao');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new Acao;
        }

        if (isset($_POST['Acao'])) {
            $model->attributes = $_POST['Acao'];
            $model->processo_fk = ($_POST['processo_fk'])? $_POST['processo_fk']:null;

            $acao_sureg = $_POST['AcaoSureg']['acao_sureg'];
            
            if ($model->validate() && (sizeof($acao_sureg) != 0)){
                if ($model->save()) {
                    $model_acaomes->salvar($model->id, $_POST['Acao']['acao_mes']);
                    $model_acao_sureg->salvar($model->id, $_POST['AcaoSureg']['acao_sureg']);
                    $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                    $this->redirect(array('index', 'id' => $model->id));
                }
            }

            if (sizeof($acao_sureg) == 0) {
                $model_acao_sureg->addError("acao_sureg", "� obrigat�rio o preenchimento do campo Unidade Regional.");
            }
            
            
        }
        $acao_mes = AcaoMes::model()->findAllByAttributes(array('acao_fk' => $id));
        $acao_sureg = AcaoSureg::model()->findAllByAttributes(array('acao_fk' => $id));
        $this->render('admin', array(
            'model' => $model,
            'model_acaomes' => $model_acaomes,
            'model_acao_sureg' => $model_acao_sureg,
            'model_processo' => $model_processo,
            'acao_mes' => $acao_mes,
            'acao_sureg' => $acao_sureg,
            'titulo' => $this->titulo,
        ));
    }

    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            $model_AS = AcaoSureg::model()->deleteAll("acao_fk=" . $id);
            $model_AM = AcaoMes::model()->deleteAll("acao_fk=" . $id);
            $this->loadModel($id, 'Acao')->delete();

            if (Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->setFlashSuccesso('excluir');
                echo $this->getMsgSucessoHtml();
            } else {
                $this->setFlashError('excluir');
                $this->redirect(array('admin'));
            }
            // apaga as combo de a��es por m�s e risco pr�-identificados
            $acao_mes = AcaoMes::model()->deleteAll("acao_fk=" . $id);
            $acao_sureg = AcaoSureg::model()->deleteAll("acao_fk=" . $id);
        }
        else
            throw new CHttpException(400, Yii::t('app', 'Sua requisi��o � inv�lida.'));
    }

    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Acao('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Acao'])) {
            $valor_exercicio = $_GET["Acao"]["valor_exercicio"];
            if ($valor_exercicio != "" && (!is_numeric($valor_exercicio) || strlen($valor_exercicio) != 4)) {
                $model->addError("valor_exercicio", "Exerc�cio incorreto.");
            } else {
                $model->attributes = $_GET['Acao'];
                $dados = $model->search();
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
            'model' => $this->loadModel($id, 'Acao'),
        ));
    }

    public function actionCarregaProcessoAjax() {
        $data = Processo::model()->findAll('valor_exercicio=:valor_exercicio ORDER BY nome_processo', array(':valor_exercicio' => (int) $_POST['Acao']['valor_exercicio']));

        $data = CHtml::listData($data, 'id', 'nome_processo');

        if (sizeof($data) > 0) {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Selecione'), true);
        } else {
            echo CHtml::tag('option', array('value' => ''), CHtml::encode('Nenhum processo cadastrado em ' . $_POST['Acao']['valor_exercicio']), true);
        }
        foreach ($data as $value => $name) {
            echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
        }
    }
    
    public function actionCarregaAcaoPorExercicioAjax(){
    	$valor_exercicio = "";
    	
    	if (isset($_POST['RiscoPre']['valor_exercicio'])){
    		$valor_exercicio = $_POST['RiscoPre']['valor_exercicio'];
    		if ($valor_exercicio){
    			$limite_inferior =Yii::app()->params['limite_inferior_exercicio'];
    			if($valor_exercicio < $limite_inferior){
    				echo CHtml::tag('option', array('value' => ''), CHtml::encode("Exerc�cio inferior ao limite suportado."));
    				exit;
    			}
    		} else {
    			echo CHtml::tag('option', array('value' => ''), CHtml::encode("Exerc�cio deve ser informado."));
    			exit;    			
    		}
    	} else {
    		echo CHtml::tag('option', array('value' => ''), CHtml::encode("Exerc�cio deve ser informado."));
    		exit;
    	}
    	
    	$data = Acao::model()->findAll('valor_exercicio=:valor_exercicio ORDER BY nome_acao', array(':valor_exercicio' => (int) $_POST['RiscoPre']['valor_exercicio']));
    	
    	$data = CHtml::listData($data, 'id', 'nome_acao');
    	
    	if (!sizeof($data) > 0) {
    		echo CHtml::tag('option', array('value' => ''), CHtml::encode('Nenhuma a��o cadastrada em ' . $_POST['RiscoPre']['valor_exercicio']), true);
    	}
    	foreach ($data as $value => $name) {
    		echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
    	}    	
    }

}