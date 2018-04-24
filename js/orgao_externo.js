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
    $(document).ready(function(){
   //$("#InteressadoExterno_fones").mask("(99) 9999-9999");
    $("#UnidadeExterna_cep").mask("99.999-999");
    
    
    if($("#UnidadeExterna_cod_orgao").val()==''){
        $("input, select").attr("disabled", "disabled");
        $("#UnidadeExterna_cod_orgao").removeAttr("disabled");
        $("#btnVerificar").removeAttr("disabled");
    }
    
});


function setDados(dados) {

    var newOptions = dados.municipios
    var selectedOption = dados.municipio_fk;
    var options;
    var select = $('#UnidadeExterna_municipio_fk');

    if(select.prop) {
        options = select.prop('options');
    }
    else {
        options = select.attr('options');
    }

    $('option', select).remove();

    $.each(newOptions, function(val, text) {
        options[options.length] = new Option(text, val);
    });
    select.val(selectedOption);


    $('#UnidadeExterna_bairro').val(dados.municipios);
    $('#UnidadeExterna_nome').val(dados.nome);
    $('#UnidadeExterna_sigla').val(dados.sigla);
    $('#UnidadeExterna_cod_orgao').val(dados.cod_orgao);
    $('#UnidadeExterna_endereco').val(dados.endereco);
    $('#UnidadeExterna_complemento').val(dados.complemento);
    $('#UnidadeExterna_numero').val(dados.numero);
    $('#UnidadeExterna_fones').val(dados.fones);
    $('#UnidadeExterna_fax').val(dados.fax);
    $('#UnidadeExterna_ddd').val(dados.ddd);
    $('#UnidadeExterna_email').val(dados.email);
    $('#UnidadeExterna_bairro').val(dados.bairro);
    $('#UnidadeExterna_cep').val(dados.cep);
    $('#uf').val(dados.uf); 



}