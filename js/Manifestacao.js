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
        var form = document.forms["Manifestacao"];
        var valor = form.relatorio_fk.options[form.relatorio_fk.selectedIndex].value;
        if (valor>0){
            top.location.href = 'Manifestacao/ManifestacaoRelatorioAjax/'+valor;
        }
    }
    

    function valida_responder_manifestacao() {
        var form = document.forms["Manifestacao"];
        var valor = form.relatorio_fk.options[form.relatorio_fk.selectedIndex].value;
        if (valor>0){
            top.location.href = 'ResponderManifestacaoAjax/'+valor;
        }
    }
    
    
    
    function valida_envio() {
        var form = document.forms["manifestacao-form"];
        var valor_resposta=0;
        var envio=1;
	var i 
   	for (i=0;i<form.resposta.length;i++){ 
      	 if (form.resposta[i].checked){
             valor_resposta = form.resposta[i].value;             
             break; 
        }
   	} 
   	
        var valor_justificativa = form.Manifestacao_descricao_manifestacao.value; 
        if (valor_resposta==0){
            alert("Informe a resposta.");
            envio=0;
        }
        if (valor_resposta==2 && !valor_justificativa){
            alert("Informe a justificativa.");
            envio=0;            
        }
        if(envio){
           document.getElementById('botao_submit').innerHTML='Aguarde...';
           form.submit();
        }
    }    


    function valida_envio_resposta() {
        var envio=1;
        var form = document.forms["manifestacao-form"];
        var valor_justificativa = form.Manifestacao_descricao_resposta.value; 
        if (!valor_justificativa){
            alert("Informe a Resposta � Manifesta��o.");
            envio=0;            
        }
        if(envio){
           document.getElementById('botao_submit').innerHTML='Aguarde...';            
           form.submit();
        }
    }   


$(window).load(function(){
$('#manifestacao-form').change(function() {
    if ($('#resposta_nao').attr('checked')) {
        $('#descricao_manifestacao').show();
    } else {
        $('#descricao_manifestacao').hide();
    }
});    
});//]]>      