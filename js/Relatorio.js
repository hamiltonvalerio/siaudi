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
        function valida_envio() {
        var form = document.forms["prorrogar_prazo"];
        var envio=1;   	
        var combo = form.Relatorio_id.value; 
        var prazo = form.Relatorio_dias_uteis.value;         
        if (combo==0){
            alert("Informe o relat�rio.");
            envio=0;
        }
        if (prazo==0){
            alert("Informe o prazo a prorrogar.");
            envio=0;            
        }
        
        if (!isNumeric(prazo) && prazo){
            alert("Prazo deve conter somente n�meros.");
            envio=0;            
        }        
        if(envio){
           form.submit();
        }
    }  
    
    
    function isNumeric(str){
      var er = /^[0-9]+$/;
      return (er.test(str));
    }    
    
    
    function valida_homologacao() {
        if (!$('#Relatorio_id').val() || $('#Relatorio_id').val()==0) {
            alert ("Selecione um relat�rio.");
        }
        else {
            if(confirm("Deseja realmente homologar o Relat�rio? ")){
                 document.forms["homologar"].submit();
            } 
        }
    }
    
    function valida_reiniciarContagem() {
            if(confirm("Confirma o rein�cio da contagem dos itens \npara o PR�XIMO relat�rio homologado? ")){
                document.forms["reiniciar_contagem"].submit();
            } 

    }
            

    function envia_relatorio(){
        $('#inserir_capitulo').val("1"); 
        $('#relatorio-form').submit();
        $('#inserir_capitulo').val("0");
    }
    
    
    function salvar() {
        document.getElementById('yt1').click(); 
    }
    
    function obtemDadosModal() {   
        var nome_risco = document.getElementById("RiscoPos_nome_risco").value;
        var descricao_impacto = document.getElementById("RiscoPos_descricao_impacto").value;
        var descricao_mitigacao = document.getElementById("RiscoPos_descricao_mitigacao").value;
        var dados = new Array();
        
        dados = {'nome_risco':nome_risco,'descricao_impacto':descricao_impacto, 'descricao_mitigacao': descricao_mitigacao};
                
        return dados;
    }
    
    function atualizaComboRiscoPos(data){
        var s= document.getElementById('relatorio_riscopos');
        s.innerHTML += data;
        $("#relatorio_riscopos").multiselect('refresh');
        alert("Risco adicionado com sucesso!");
    }
    
    function validaDadosModal() {
        //implementar o tooltip
        //procurar como fazer a quebra de linha.
        
        retorno = true;
        msgInconsistencia = 'Por favor, corrija os seguintes erros: <br>';
        tips = $( ".validateTips" );
        
        e_nome_risco = $( "#RiscoPos_nome_risco");
        e_descricao_impacto = $( "#RiscoPos_descricao_impacto");
        e_descricao_mitigacao = $( "#RiscoPos_descricao_mitigacao");
                
        e_nome_risco.removeClass( "ui-state-error" );
        e_descricao_impacto.removeClass( "ui-state-error" );
        e_descricao_mitigacao.removeClass( "ui-state-error" );
        
        if (e_nome_risco.val() === '') { 
            e_nome_risco.addClass( "ui-state-error" );
            msgInconsistencia += "<li>� obrigat�rio o preenchimento do campo Risco. </li>";
            retorno = false;
        }
        /*
        if (e_descricao_impacto.val() === '') { 
            e_descricao_impacto.addClass( "ui-state-error" );
            msgInconsistencia += "<li> � obrigat�rio o preenchimento do campo Impacto. </li>";
            retorno = false;
        }
        
        if (e_descricao_mitigacao.val() === '') { 
            e_descricao_mitigacao.addClass( "ui-state-error" );
            msgInconsistencia += "<li> � obrigat�rio o preenchimento do campo Mitiga��o. </li>";
            retorno = false;
        }
        */
        if (!retorno){
            tips
            .html(msgInconsistencia)
//            .cssHTML("color: #B43419; background-color: #FDF5F3;")
            .addClass( "ui-state-highlight" );
        }
        
        return retorno;
        
    }