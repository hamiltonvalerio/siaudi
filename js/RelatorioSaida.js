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
    // ************************************************
    // Fun��o para desabilitar o bot�o direito do mouse
    // ************************************************
    var message="";

    function clickIE() {
            if (document.all) {
                    (message);
                    return false;
            }
    }

    function clickNS(e) {
            if (document.layers||(document.getElementById&&!document.all)) {
                    if (e.which==2||e.which==3) {
                            (message);
                            return false;
                    }
            }
    }

    if (document.layers) {
            document.captureEvents(Event.MOUSEDOWN);
            document.onmousedown=clickNS;
    }else{
            document.onmouseup=clickNS;document.oncontextmenu=clickIE;
    }

    document.oncontextmenu=new Function("return false");

    //FUN��O PARA BLOQUEAR A TECLA CTRL DO TECLADO
    function BloquearTecla(e){
            if (document.all) // Internet Explorer
                    var tecla = event.keyCode;
            else if (document.layers) // Nestcape
                    var tecla = e.which;
            else if (document.getElementById) //FireFox
                    var tecla = e.which;

            if (e.ctrlKey){ 
              return false;
            }
    }    