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

class RelatorioRegularizaController extends GxController {

    public   $titulo = 'Regularizar Relat�rio';
    
    public function init() {
        if (!Yii::app()->user->verificaPermissao("RelatorioRegulariza", "admin")) {
            $this->redirect(array('site/acessoNegado')); 
            exit;
        }                
        
        parent::init();
        $this->defaultAction = 'index';
    }             

    public function actionIndex() {
    	$model = new Relatorio;
        $id = $_POST['Relatorio']['id'];
        if($id){        
            $model = $this->loadModel($id , 'Relatorio');
            $data = explode("/", $_POST['Relatorio']['data_regulariza']);
	        $dia = intval($data[0]);
	        $mes = intval($data[1]);
	        $ano = intval($data[2]);
        	$data_valida = checkdate($mes, $dia, $ano); //ordem dos par�metros: mes, dia e ano.
        	$limite_inferior =Yii::app()->params['limite_inferior_exercicio'];
        	$data_atual = date("Ymd");
	        $data_post = $data[2].$data[1].$data[0];
			if (!$data_valida) {
				$model->addError("data_regulariza", "Data inv�lida");
			}else if ($ano  < $limite_inferior  ){
	        	$model->addError("data_regulariza", "Exerc�cio inferior ao limite");
			} else if ($data_post > $data_atual) {
				$model->addError("data_regulariza", "Data de regulariza��o n�o pode ser superior a data atual");
			} else {
				$model->data_regulariza = date('Y-m-d', mktime(0, 0, 0, $mes, $dia, $ano));
				if ($model->save(false)) {
                    $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );
                    $this->redirect(array('index', 'id' => $model->id));
            	}
			}
        }

            
        $this->render('index', array(
            'titulo' => $this->titulo,
            'model' => $model
        ));
    }
}