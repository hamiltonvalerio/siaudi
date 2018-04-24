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

class RelatorioPreFinalizaController extends GxController {

    public $titulo = 'Pr� Finalizar Relat�rio';

    public function init() {
        parent::init();
        $this->defaultAction = 'index';
    }

    public function actionIndex() {
        $id = $_POST['Relatorio']['id'];
        if ($id) {

            $model = $this->loadModel($id, 'Relatorio');
            $model->login_pre_finaliza = Yii::app()->user->login;
            $model->data_pre_finalizado = date("Y-m-d");
                       
            try{
            if ($model->save(false)) {
                
                $model->pre_finalizar_relatorio($id);
                $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                $this->redirect(array('index', 'id' => $model->id));
            }} catch (Exception $e){
                print_r($e->getMessage());
            }
        }

        $model = new Relatorio;
        $this->render('index', array(
            'titulo' => $this->titulo,
            'model' => $model
        ));
    }

}