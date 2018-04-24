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
    function envia_recomendacao() {
    $('#inserir_recomendacao').val("1");
    $('#recomendacao-form').submit();
}

function importacao(url) {
    window.open(url, '_blank', 'width=700,height=500,scrollbars=yes,resizable=yes,location=no,toolbar=no,status=no');
}


$(function() {
    $("#Recomendacao_numero_capitulo").keypress(verificaNumeroRomano);
});        


$(window).load(function(){
$('#recomendacao-form').change(function() {
    valor_rec  = $('#Recomendacao_recomendacao_tipo_fk option:selected' ).text();
    if (valor_rec=="Recomenda��o") {
        $('#detalhamento_recomendacao').show();
    } else {
        $('#detalhamento_recomendacao').hide();
    }
});    
});