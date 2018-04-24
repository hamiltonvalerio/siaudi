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

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel {

    public $usuario_login;
    public $usuario_senha;
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that usuario_login and senha are required,
     * and senha needs to be authenticated.
     */
    public function rules() {
        return array(
            array('usuario_login, usuario_senha', 'required', 'on' => 'cenario_login'),
            array('password', 'authenticate', 'on' => 'cenario_login'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'usuario_login' => 'Usu�rio',
            'usuario_senha' => 'Senha',
        );
    }

    /**
     * Valida o acesso de um novo cadastro
     */
    public function validarAcesso() {
        if (!$this->getErrors()) {
            try {
                // verifica se o codigo informado existe na base
                $usuario = VwUsuario::model()->find('login=:login', array(':login' => $this->usuario_login));
                if (!$usuario) {
                    $this->addError('novo_usuario_login', 'Usu�rio n�o cadastrado no sistema. Entre em contato com a �rea repons�vel.');
                    return false;
                }
                // verifica se a senha possui ao menos 8 caracteres
                if (strlen(trim($this->usuario_senha)) < 8) {
                    $this->addError('novo_usuario_senha', 'A senha deve conter ao menos 8 caracteres.');
                    return false;
                }
            } catch (Exception $exc) {
                $exc->getMessage();
                $this->addError('usuario_login', $exc);
                return false;
            }

            return true;
        }
    }

    /**
     * Authenticates the senha.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {
//            $this->_identity = new UserIdentity($this->usuario_login, $this->usuario_senha);
//            if (!$this->_identity->authenticate()){
//                $this->addError('usuario_senha', 'Usu�rio ou Senha Inv�lidos.');
//            }
        }
    }

    /**
     * Logs in the user using the given usuario_login and senha in the model.
     * @return boolean whether login is successful
     */
    public function login() {
        $this->_identity = new UserIdentity($this->usuario_login, $this->usuario_senha);
        if (!$this->_identity->authenticate()) {
            $this->addError('usuario_senha', 'Usu�rio ou Senha Inv�lidos.');
            return false;
        }else{
        //if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            Yii::app()->session['identity'] = serialize($this->_identity);
            Yii::app()->user->login($this->_identity);
            return true;
        }
    }

}
