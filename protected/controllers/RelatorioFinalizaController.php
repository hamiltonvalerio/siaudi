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

class RelatorioFinalizaController extends GxController {

    public   $titulo = 'Finalizar Relat�rio';
    
    public function init() {
        if (!Yii::app()->user->verificaPermissao("RelatorioFinaliza", "admin")) {
            $this->redirect(array('site/acessoNegado')); 
            exit;
        }                
        
        parent::init();
        $this->defaultAction = 'index';
    }             

    public function actionIndex() {
        $id = $_POST['Relatorio']['id'];   
        $prazo_manifestacao = $_POST['Relatorio']['prazo_manifestacao'];

        if($id){        
            $model = $this->loadModel($id , 'Relatorio');            
            $model->login_finaliza = Yii::app()->user->login;
            $model->data_finalizado = date("Y-m-d");
            
            /* se prazo para manifesta��o=0 , ent�o
             * bloqueia manifesta��o e vai direto para  homologa��o
             */         
            if ($prazo_manifestacao==0) {  $model->st_libera_homologa = 1; }

            if ($model->save(false)) { 
                    $model->finalizar_relatorio($id,$prazo_manifestacao);
                    $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
                    $this->redirect(array('index', 'id' => $model->id));
            }        

        }

        $model = new Relatorio;    
        $this->render('index', array(
            'titulo' => $this->titulo,
            'model' => $model
        ));
    }
}