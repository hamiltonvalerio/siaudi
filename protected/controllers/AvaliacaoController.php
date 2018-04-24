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

class AvaliacaoController extends GxController {

    public   $titulo = 'Avalia��o do Auditor';

    public function actionAdmin($id = 0) {

        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id , 'Avaliacao');
        } else {
            $this->subtitulo = 'Inserir';
            $model = new Avaliacao;
        }
        
        if (isset($_POST['Avaliacao'])) {
            $model->relatorio_fk = $_GET['relatorio'];
            $model->unidade_administrativa_fk = $_GET['sureg'];
            $model->usuario_fk = $_GET['auditor'];
            $model->data = date("Y-m-d");
            $model->nome_login = Yii::app()->user->login;

             // salva avalia��o   
             if ($model->save()) {

                  // salva notas
                  $notas = Avaliacao::model()->SalvaNotas($model->id,$_POST['Nota_Criterio']);
                  //salva observa��o
                  $observacao = Avaliacao::model()->SalvaObservacao($model->id,$_POST['Avaliacao']['observacao']);
                  
                  $this->setFlashSuccesso( ($id > 0 ? 'alterar' : 'inserir') );                  
                  
                    /* se avalia��o veio da visualiza��o do relat�rio 
                     * em PDF, ent�o retorna para o relat�rio. Caso contr�rio,
                     * retorna para manifesta��o (caminho feliz da avalia��o - que
                     * deve ser feita antes da manifesta��o do auditado).
                     */
                    if($_POST['visualizar_pdf']){
                        $this->redirect('../../RelatorioSaida/RelatorioSaidaAjax/'.$model->relatorio_fk."?confirma=1&visualizar_pdf=1");
                    }else{
                        $this->redirect('../../Manifestacao/ManifestacaoRelatorioAjax/'.$model->relatorio_fk."?confirma=1");
                    }
                   exit;
            }
        }

        $this->render('admin', array(
            'model' => $model,
            'titulo' => $this->titulo,
        ));
    }

    /*
    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {
            $this->loadModel($id, 'Avaliacao')->delete();

             if (Yii::app()->getRequest()->getIsAjaxRequest()) {
                $this->setFlashSuccesso('excluir');
                echo  $this->getMsgSucessoHtml();
            } else {
                $this->setFlashError('excluir');
                $this->redirect(array('admin'));
            }
            
        } else
            throw new CHttpException(400, Yii::t('app', 'Sua requisi��o � inv�lida.'));
    }*/

    /*
    public function actionIndex() {
        $this->subtitulo = 'Consultar';
        $dados = null;
        $model = new Avaliacao('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Avaliacao'])) {
            $model->attributes = $_GET['Avaliacao'];
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
                'model' => $this->loadModel($id, 'Avaliacao'),
        ));
    }
      */
     
}