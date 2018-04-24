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
        function valida() {
        var form = document.forms["Resposta"];
        var valor = form.Resposta_relatorio_fk.options[form.Resposta_relatorio_fk.selectedIndex].value;
        if (valor>0){
            form.submit();
        }
    }
    
    
    
    function validaFollowUp() {
        var form = document.forms["resposta-form"];
        
        if (document.getElementById("Resposta_tipo_status_fk")) {
            var valor = form.Resposta_tipo_status_fk.options[form.Resposta_tipo_status_fk.selectedIndex].value;
            var descricao = form.Resposta_tipo_status_fk.options[form.Resposta_tipo_status_fk.selectedIndex].text;

              // Confirma envio para followUp baixado ou solucionado
              if (valor==3 || valor==4 ){
                    if(confirm("Confirma o envio do Follow Up com o status de " + descricao  + " ? ")){
                         form.submit();
                    }           
            } else {
                form.submit();
            }
        } else {
            form.submit();
        } 
        
    }    