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
    function valida(id, nome_login_cliente, nome_login_autenticado) {
    if (nome_login_cliente == nome_login_autenticado) {
        if (confirm("Deseja realmente revogar o acesso deste item? ")) {
            $('#relatorio_acesso_item_id').val(id);
            document.forms["acesso_item"].submit();
        }
    } else
    {
        alert("Somente o usu�rio que concedeu o acesso pode revog�-lo.")
    }
}

$(document).ajaxComplete(function() {
    $("#item_fk").multiselect('refresh');
    var item = document.getElementById("item_fk");
    if (item.length == 0) {
        alert("Relat�rio n�o possui nenhum item cadastrado!");
        window.location.reload();
    }
    radiobtn = document.getElementById("RelatorioAcessoItem_liberar_todos_itens_1");
    radiobtn.checked = true;
    habilitarComponentes();
});

$(function() {
    $(document).ready(function() {
        desabilitaComponentes();
    });
});

function desabilitaComponentes() {
    $("#item_fk").multiselect("checkAll");
    $("#item_fk").multiselect('refresh');
    $("#item_fk").multiselect('disable');
    $("#relatorio_fk").multiselect('disable');
}

function habilitarComponentes() {
    $("#item_fk").multiselect('enable');
//    $("#item_fk").multiselect("uncheckAll");
    $("#item_fk").multiselect('refresh');
}

$(function() {
    $('#RelatorioAcessoItem_liberar_todos_itens_0').click(function() {
    	if (!this.disabled){
    		desabilitaComponentes();
    	}
    });
});

$(function() {
    $('#RelatorioAcessoItem_liberar_todos_itens_1').click(function() {
    	if (!this.disabled){
    		habilitarComponentes();
    	}
    });
});