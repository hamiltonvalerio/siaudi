<?php
/********************************************************************************
*  Copyright 2015 Conab - Companhia Nacional de Abastecimento                   *
*                                                                               *
*  Este arquivo é parte do Sistema SIAUDI.                                      *
*                                                                               *
*  SIAUDI  é um software livre; você pode redistribui-lo e/ou                   *
*  modificá-lo sob os termos da Licença Pública Geral GNU conforme              *
*  publicada pela Free Software Foundation; tanto a versão 2 da                 *
*  Licença, como (a seu critério) qualquer versão posterior.                    *
*                                                                               *
*  SIAUDI é distribuído na expectativa de que seja útil,                        *
*  porém, SEM NENHUMA GARANTIA; nem mesmo a garantia implícita                  *
*  de COMERCIABILIDADE OU ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA.                *
*  Consulte a Licença Pública Geral do GNU para mais detalhes em português:     *
*  http://creativecommons.org/licenses/GPL/2.0/legalcode.pt                     *
*                                                                               *
*  Você deve ter recebido uma cópia da Licença Pública Geral do GNU             *
*  junto com este programa; se não, escreva para a Free Software                *
*  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA    *
*                                                                               *
*  Sistema   : SIAUDI - Sistema de Auditoria Interna                            *
*  Data      : 05/2015                                                          *
*                                                                               *
********************************************************************************/
?>
<div id="wrapperMenuAuxiliar">
<div id="acessoSistemas">
        <img id="imgE" src="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/img/c_e_sistemas.jpg"
             width="20" height="20" />
        <!--label>Outros Sistemas:</label -->
        <?php //echo CHtml::dropdownlist('sistemas', 'nome', CHtml::listData($this->sistemas, 'url', 'nome'), array('empty' => 'Selecione', 'onchange' => 'location=this.options[this.selectedIndex].value;')); ?>
        <img id="imgD" src="<?php echo Yii::app()->request->baseUrl; ?>/themes/<?php echo Yii::app()->params['tema'] ?>/img/c_d_sistemas.jpg" width="19" height="20" />
    </div>
    <div id="sair">
        <label id="lblUsuarioLogado"><?php echo Yii::app()->user->id_und_adm . ' -- ' . Yii::app()->user->login ?> </label><a href="<?php echo Yii::app()->request->baseUrl;?>/site/logout">[Sair]</a>
    </div>
    <div id="menuAuxiliar">
        <div style="float:left;margin:0px;"> &nbsp;
            <spam id="cronometro_div">Sua sessão expira em: 
            <strong><span id="cronometro" style="color:#000000;" ></span></strong>
            </spam> &nbsp;  &nbsp; 
       </div>
        <ul>
            <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/site/alterarMinhaSenhaAjax/<?php echo Yii::app()->user->id; ?>?cenario=p">Alterar Senha</a></li>
            <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/site/contatoAjax">Contato</a></li>
            <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/anexos/siaudi_manual.pdf" target="blank">Manual do Usuário</a></li>
        </ul>
    </div>
</div>
    <script>jQuery("#cronometro_div").cronometroSessao({"minutos":00,"segundos":<?php echo Yii::app()->params['session_time'];?>});</script>    