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

class UsuarioController extends GxController {

    public $titulo = 'Gerenciar Usu�rios';
    
    public function init() {
        parent::init();
        
        if (!Yii::app()->user->verificaPermissao("usuario", "admin")) {
            $this->redirect(array('site/acessoNegado')); 
            exit;
        }
        
        $this->defaultAction = 'index';

        $this->menu_acao = array(
        	array('label' => 'Consultar', 'url' => array('Usuario/index')),
            array('label' => 'Incluir', 'url' => array('Usuario/admin')),
        );
    }

    public function actionAdmin($id = 0, $cenario = '') {
        if (!empty($id)) {
            $this->subtitulo = 'Atualizar';
            $model = $this->loadModel($id, 'Usuario');
            if($cenario == 'p'){
                $model->scenario = 'alteraSenha';
                $model->senha = '';
            }
            $model->afterFind = false;
        } else {
            $this->subtitulo = 'Inserir';
            $model = new Usuario('inclusao');
//            $model->isNewRecord = 1;
        }
        
//        if ($msgInconsistencia == '') {
        if (isset($_POST['Usuario'])) {
            // var_dump($_POST['Usuario']); exit();
            if (!($this->VerificaSeLoginExiste($id))) {
                $model->attributes = $_POST['Usuario'];
                if (empty($model->substituto_fk)) {
                    $model->substituto_fk = null;
                }
                // var_dump($model->attributes); exit();
                if ($id) {
                    if ($cenario = 'p') {
                        $model->senha = md5($model->senha);
                    }
                    if (isset($_POST['Usuario']['bolDeAcordo'])) {
                        if ($_POST['Usuario']['bolDeAcordo']) {
                            $perfil = Perfil::model()->findByPk($_POST['Usuario']['perfil_fk']);
                            if (strtoupper($perfil->nome_interno) == 'SIAUDI_CLIENTE') {
                                $model->SubstituiUsuarioCargo($_POST['Usuario']['nome_login'], $_POST['Usuario']['unidade_administrativa_fk'], $_POST['Usuario']['cargo_fk']);
                            } else {
                                $model->RevogaPermissaoRelatorioAcesso($_POST['Usuario']['nome_login'], $_POST['Usuario']['unidade_administrativa_fk']);
                            }
                        }
                    }
                } else {
                    $model->senha = md5($model->senha);
                }
                                
                if ($model->save()) {
                    $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
                    $this->redirect(array('index?' . $_SERVER['QUERY_STRING']));
                } 
                
            } else {
                $model->addError("nome_login", "O login informado j� est� cadastrado. Favor informar outro login.");
            }
        }
//        } else {
//            $model->addError('', "Aten��o, os dados n�o foram gravados devido a restri��o abaixo.");
//        }

        $this->render('admin', array(
            'model' => $model,
            'titulo' => $this->titulo,
            'msgInconsistencia' => $msgInconsistencia
        ));
    }

    public function actionDelete($id) {
        $this->layout = false;
        if (Yii::app()->getRequest()->getIsPostRequest()) {

            $model = new Usuario;
            $usuario = Usuario::model()->findByPk($id);
            $model->RevogaPermissaoRelatorioAcesso($usario->nome_login, $usuario->unidade_administrativa_fk);
            
            $this->loadModel($id, 'Usuario')->delete();

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
        $model = new Usuario('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Usuario'])) {

            $model->attributes = $_GET['Usuario'];
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
            'model' => $this->loadModel($id, 'Usuario'),
        ));
    }

    private function VerificaSeLoginExiste($id) {
        $login_informado = $_POST['Usuario']['nome_login'];
        $dados_login_informado = Usuario::model()->findAllByAttributes(array('nome_login' => $login_informado));
        $retorno = sizeof($dados_login_informado);

        if ($id) {
            $dados_login_cadastrado = Usuario::model()->findByPk($id);
            if (sizeof($dados_login_cadastrado)) {
                if (strtolower($dados_login_cadastrado->nome_login) == strtolower($login_informado))
                    $retorno = false;
            }
        }
        return $retorno;
    }
    /**
     * Verificar se o login informado j� est� cadastrado
     * Se for siaudi_cliente revoga os acessos do antigo siaudi_cliente
     * e d� acesso ao novo a esses relat�rios
     * @return string
     */
    private function ValidaUsuarioJaCadastrado() {
        $msg = '';
        $msgPerfil = '';
        $msgFuncao = '';
        $id = $_POST['id'];

        if (!empty($id)) {
            //dados do usu�rio antes da altera��o
            $usuario_cadastro_base = Usuario::model()->findByPk($id);
            $unidade_administrativa_cadastro_base = UnidadeAdministrativa::model()->findByPk($usuario_cadastro_base->unidade_administrativa_fk);

            //dados do usu�rio informado da tela
            $unidade_administrativa_fk_cadastro = $_POST['unidade_administrativa_fk'];
            $perfil_fk_cadastro = $_POST['id_perfil_selecionado'];
            $nome_usuario = $_POST['nome_usuario'];

            //verificar se houve altera��o das informa��es que possam gerar revoga��o de acesso
            //Primeira verifica��o: verifica se o usu�rio perdeu o perfil de cliente
            $perfil = Perfil::model()->findByPk($usuario_cadastro_base->perfil_fk);
            if (($usuario_cadastro_base->perfil_fk != $perfil_fk_cadastro) && (trim(strtolower($perfil->nome_interno)) == "siaudi_cliente")) {

                $msgPerfil = $this->ObtemMsgRelatorioAcesso($_POST['nome_login'], $_POST['unidade_administrativa_fk']);

                if ($msgPerfil) {
                    $msgPerfil = "<br>Aten��o, o usu�rio " . trim($_POST['nome_usuario']) . " da unidade " . trim($unidade_administrativa_cadastro_base->sigla) . " ter� seu acesso revogado, como auditado, aos relat�rios abaixo: <br><br>" . $msgPerfil;
                }
            } //fim verifica��o do perfil
            //Segunda verifica��o: verifica se o usu�rio passou a ser SIAUDI_CLIENTE
            $perfil_tela = Perfil::model()->findByPk($perfil_fk_cadastro);
            //PORTAL_SPB
            if ((($usuario_cadastro_base->perfil_fk != $perfil_fk_cadastro) && (trim(strtolower($perfil_tela->nome_interno)) == "siaudi_cliente"))  ||
                    (($usuario_cadastro_base->unidade_administrativa_fk != $unidade_administrativa_fk_cadastro) && (trim(strtolower($perfil_tela->nome_interno)) == "siaudi_cliente"))) {

                //Verifica se existe SIAUDI_CLIENTE na Unidade Administrativa informada 
                $usuario_atual = Usuario::model()->findByAttributes(array('perfil_fk' => $perfil_tela->id,
                    'unidade_administrativa_fk' => $unidade_administrativa_fk_cadastro));


                //caso exista, informamos para o operador que se ele continuar com a opera��o o siaudi_cliente/substituto atual ser� susbstituido 
                //pelo usu�rio que ele esta informando e o mesmo receber� as permiss�es aos relat�rios que o atual siaudi_cliente/substituto tem acesso.
                if (sizeof($usuario_atual)) {
                    //Obtem mensagem com os relat�rios que o atual siaudi_cliente/substituto possui acesso j� formatado.
                    $msgFuncao = $this->ObtemMsgRelatorioAcesso($usuario_atual->nome_login, $usuario_atual->unidade_administrativa_fk);

                    //caso o siaudi_cliente/substituto tenha acesso, complementamos a mensagem de retorno.
                    if ($msgFuncao) {
                        $unidade_administrativa = UnidadeAdministrativa::model()->findByPk($usuario_atual->unidade_administrativa_fk);
                        $msgFuncao = "Aten��o, o usu�rio ". trim($usuario_atual->nome_usuario) . ", da unidade " . trim($unidade_administrativa->sigla) . ", ter� seu acesso revogado aos relat�rios abaixo, sendo transferidos para o usu�rio " . trim($nome_usuario) . ":<br><br>" . $msgFuncao;
                        $msgFuncao .= '<br><br><strong>O usu�rio '.trim($nome_usuario).' ter� acesso somente a estes relat�rios descritos, tendo o acesso revogado a relat�rios anteriores.</strong><br><br>';
                    }
                }
            } //fim verifica��o da fun��o/unidade_administrativa
            
            //PORTAL_SPB
            if ((($usuario_cadastro_base->perfil_fk != $perfil_fk_cadastro) && (trim(strtolower($perfil->nome_interno)) == "siaudi_cliente")) ||
                    (($usuario_cadastro_base->unidade_administrativa_fk != $unidade_administrativa_fk_cadastro) && (trim(strtolower($perfil->nome_interno)) == "siaudi_cliente"))) {

                if ($usuario_cadastro_base->unidade_administrativa_fk != $unidade_administrativa_fk_cadastro) {
                    $msgPerfil = $this->ObtemMsgRelatorioAcesso($_POST['nome_login'], $usuario_cadastro_base->unidade_administrativa_fk);
                } else {
                    $msgPerfil = $this->ObtemMsgRelatorioAcesso($_POST['nome_login'], $_POST['unidade_administrativa_fk']);
                }

                if ($msgPerfil) {
                    $msgPerfil = "<br>Aten��o, o usu�rio " . trim($_POST['nome_usuario']) . " da unidade " . trim($unidade_administrativa_cadastro_base->sigla) . " ter� seu acesso revogado, como auditado, aos relat�rios abaixo: <br><br>" . $msgPerfil;
                }
            } //fim verifica��o do perfil            
        } //fim verifica��o id

        $msg = $msgPerfil . $msgFuncao;
        return $msg;
    }

    public function actionVerificaRelatorioAcessoAjax() {
        $msg = '';
        $bol_novo_registro = $_POST['novo_registro'];
        if ($bol_novo_registro) {
            $msg = $this->ValidaNovoUsuario();
        } else {
            $msg = $this->ValidaUsuarioJaCadastrado();
        }
        echo $msg;
    }

    private function ValidaNovoUsuario() {
        $msg = '';
        $nome_usuario = $_POST['nome_usuario'];
        $unidade_administrativa_fk = $_POST['unidade_administrativa_fk'];

        //PORTAL_SPB
        $perfil_fk = $_POST['perfil_fk'];

        //Verifica se existe siaudi_cliente ou substituto 
        $usuario_atual = Usuario::model()->findByAttributes(array('perfil_fk' => $perfil_fk,
            'unidade_administrativa_fk' => $unidade_administrativa_fk));

        //caso exista, informamos para o operador que se ele continuar com a opera��o o siaudi_cliente/substituto atual ser� susbstituido 
        //pelo usu�rio que ele esta informando e o mesmo receber� as permiss�es aos relat�rios que o atual siaudi_cliente/substituto tem acesso.
        if (sizeof($usuario_atual)) {
            //Obtem mensagem com os relat�rios que o atual siaudi_cliente/substituto possui acesso j� formatado.
            $msg = $this->ObtemMsgRelatorioAcesso($usuario_atual->nome_login, $usuario_atual->unidade_administrativa_fk);

            //caso o usu�rio tenha acesso, complementamos a mensagem de retorno.
            if ($msg) {
                $unidade_administrativa = UnidadeAdministrativa::model()->findByPk($usuario_atual->unidade_administrativa_fk);
                $msg = "Aten��o, o usu�rio ". trim($usuario_atual->nome_usuario) . ", da unidade " . trim($unidade_administrativa->sigla) . ", ter� seu acesso revogado aos relat�rios abaixo, sendo transferidos para o usu�rio " . trim($nome_usuario) . ":<br><br>" . $msg;
                $msg .= '<br><br><strong>O usu�rio '.trim($nome_usuario).' ter� acesso somente a estes relat�rios descritos, tendo o acesso revogado a relat�rios anteriores.</strong><br><br>';
            }
        }

        return $msg;
    }

    private function ObtemMsgRelatorioAcesso($nome_login, $unidade_administrativa_fk) {
        $msgHomologados = '';
        $msgNaoHomologados = '';
        //obtem os relat�rios que o usu�rio possui acesso.
        $dados_relatorio_acesso = RelatorioAcesso::model()->findAllByAttributes(array('nome_login' => $nome_login,
            'unidade_administrativa_fk' => $unidade_administrativa_fk), array('order' => 'relatorio_fk'));

        //monta mensagem com os relat�rios
        if (sizeof($dados_relatorio_acesso)) {
            foreach ($dados_relatorio_acesso as $vetor) {
                $dados_relatorio = Relatorio::model()->findByPk($vetor['relatorio_fk']);
                if ($dados_relatorio->data_relatorio) {
                    $msgHomologados .= "Relat�rio " . $dados_relatorio->numero_relatorio . ", de " . $dados_relatorio->data_relatorio . "<br>";
                } else {
                    $msgNaoHomologados .= "Relat�rio ID " . $dados_relatorio->id . "<br>";
                }
            }
        }
        return $msgNaoHomologados . $msgHomologados;
    }
}