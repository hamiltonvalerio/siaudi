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

class SubriscoController extends GxController {

    public   $titulo = 'Tabela de Sub-riscos';

    
    public function init() {
        if (!Yii::app()->user->verificaPermissao("Subrisco", "admin")) {
            $this->redirect(array('site/acessoNegado')); 
            exit;
        }
        
        parent::init();
        $this->defaultAction = 'index';

        $this->menu_acao = array(
        	array('label' => 'Consultar', 'url' => array('Subrisco/index')),
            array('label' => 'Incluir', 'url' => array('Subrisco/admin')),
        );
    }          

    public function actionAdmin($id = 0) {
            $model_subrisco = new Subcriterio;

        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id , 'Subrisco');
        } else {
        	if (isset($_GET['consultar'])){
            	$this->subtitulo = 'Consultar';
        	} else {
        		$this->subtitulo = 'Inserir';
        	}
            $model = new Subrisco;
        }
        
        /*
        if (isset($_POST['Subrisco'])) {
            $model->attributes = $_POST['Subrisco'];
          
          		if ($model->save()) {
                              $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
                    $this->redirect(array('index', 'id' => $model->id));
            }
        }
        */  
            if($_POST['Nota_Acao_Criterio']){
                $salvar = $model->salvar_tabela_risco($_POST['Nota_Acao_Criterio']);
                $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
                $this->redirect(array('index'));                
            }


            if (isset($_POST['Subcriterio'])) {
                $criterio= $_POST['Subcriterio']['criterio_fk'];
                 if ($criterio){
                 $this->redirect(array('admin', 'criterio' => $criterio));
                }
            }

            if (!$_POST['Nota_Acao_Criterio'] && isset($_POST["Subcriterio"]["criterio_fk"]) && $_POST["Subcriterio"]["criterio_fk"]==0){
                 //$model->addError("valor_exercicio,", "Informe o exerc�cio e o crit�rio");
                $model->addError("","Informe o crit�rio de acordo com o exerc�cio");
            }

            if (isset($_GET['criterio'])) {
                $criterio = $_GET['criterio']; 
                $model_criterio_dados = Subcriterio::model()->findAllByAttributes(array('criterio_fk'=>$criterio), array('order'=>'id'));
                $model_processo_dados = Processo::carrega_tabela_subrisco($criterio);
            }
        

        $this->render('admin', array(
            'model_criterio_dados' => $model_criterio_dados,
            'model_processo_dados' => $model_processo_dados,             
            'model' => $model,
            'model_subrisco' => $model_subrisco,
            'titulo' => $this->titulo,
        ));
    }

    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            $this->loadModel($id, 'Subrisco')->delete();

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
        $model_subrisco = new Subcriterio;        
        
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Subrisco('search');

        

        $criterio = $_GET['Subrisco']['criterio_fk'];
          if (isset($criterio)){
                if ($criterio=="" || $criterio==0){
                       $model->addError("valor_exercicio","Informe o exerc�cio e o crit�rio principal.");
                }else {             
                  $this->redirect(array('admin', 'criterio' => $criterio, 'consultar'=>'1' ));            
                }
          }
/*          
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Subrisco'])) {
            $model->attributes = $_GET['Subrisco'];
            $dados = $model->search();
        }
*/
        $this->render('index', array(
            'model_subrisco' => $model_subrisco,            
            'model' => $model,
            'dados' => $dados,
            'titulo' => $this->titulo,
        ));
    }

    public function actionView($id) {
        $this->layout = false;
        $this->render('view', array(
                'model' => $this->loadModel($id, 'Subrisco'),
        ));
    }
   
    // recebe o ano do exerc�cio e carrega seus crit�rios
    public function actionCarregaCriterioAjax() {
          $ano_exercicio = $_POST['Subrisco']['valor_exercicio'];
          if ($ano_exercicio!=""){
            // Consulta a model
            $data = Subrisco::model()->RecuperaCriterioPorExercicio($ano_exercicio);
           
            $data=CHtml::listData($data,'id','nome_criterio');

            if (sizeof($data)>0) { 
                echo CHtml::tag('option',array('value'=>''),CHtml::encode('Selecione'),true); 
            }
            else { 
                echo CHtml::tag('option',array('value'=>''),CHtml::encode('Sem crit�rios'),true);
            }
            
            foreach($data as $value=>$name)
            {
                echo CHtml::tag('option',
                array('value'=>$value),CHtml::encode($name),true);
            }
          }
    } 
}