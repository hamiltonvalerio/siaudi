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
    });

function carregaView(url) {

    var arrayUrl=url.split('/');
    var id = arrayUrl[arrayUrl.length-1];
 
    $.get('view/'+id, function(data) {
        $('#visualizar_reg').html(data);
        $('#dialog_visualizar').dialog('open');
    });
}; 
 
function viewDelete(url, grid_name) {

    var arrayUrl=url.split('/');
    var id = arrayUrl[arrayUrl.length-1];
    
    $('.ui-button:contains("Excluir")')
        .css("border", "1px solid #CD0A0A")
        .css('background','#FEF1EC').children().css("color", "#CD0A0A")
    
    $.get('view/'+id, function(data) {
        $('#excluir_reg').html(data);
        $('#dialog_excluir').dialog('open');
        $("#id_excluir").val(id)
        $("#ctrl_excluir").val(url)
        $("#id_grid_excluir").val(grid_name.parents('.grid-view').attr('id'))
    });
}; 
