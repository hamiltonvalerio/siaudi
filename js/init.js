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
    $('.ui-dialog-titlebar-close').attr({
        alt: 'Fechar janela',
        title: 'Fechar janela'
    });

    $('#popupBox').ajaxStart(function(){  
        //Quando a requisi��o come�ar, Exibe a DIV 
               
        $(this).show();
        $('#popupBoxFundo').show();
    });  
            
    $('#popupBox').ajaxStop(function(){  
        //Quando a requisi��o parar, Esconde a DIV  
        $(this).hide();  
        $('#popupBoxFundo').hide();
    });  
    
});




Array.prototype.last = function() {return this[this.length-1];}


function clear_form_elements(ele) {

    $(ele).find(':input').each(function() {
        switch(this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
        }
    });

}

function readonly_form_elements(ele,flag) {

    $(ele).find(':input').each(function() {
        switch(this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
            case 'checkbox':
            case 'radio':
                if(flag)
                    $(this).attr("readonly","readonly");
                else
                    $(this).removeAttr("readonly");
                break;
        }
    });

}

function remove_class_error(ele) {

    $(ele).find('.error').each(function() {
        $(this).removeClass("error");
    });

}

function add_class_error(ele, erros, classe) {

    $(ele).find('input , label').each(function(i, element) {
        
        var _element = $(this);
        var _for = $(this).attr('for');
        
        $.each(erros, function(nome, msg) {
            
            if( _for == classe+'_'+nome)
               _element.addClass("error");
                    
            switch(_element.attr('type') ) {
                case 'password':
                case 'select-multiple':
                case 'select-one':
                case 'text':
                case 'textarea':
                case 'checkbox':
                case 'radio':
                    if( _element.attr('id') == classe+'_'+nome)
                        _element.addClass("error");
                    break;
            }
        });
    });

}



