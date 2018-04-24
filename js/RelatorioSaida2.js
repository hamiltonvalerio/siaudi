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
        var form = document.forms["relatorioSaida"];
        var valor = form.Relatorio_id.options[form.Relatorio_id.selectedIndex].value;
        if (valor>0){
            window.open('RelatorioSaida/RelatorioSaidaAjax/'+valor,'Relatorio_de_Auditoria','width=700,height=500,scrollbars=yes,resizable=yes,location=no,toolbar=no,status=no');
        }
    }
    
    function auditor_abre_div() {
        var form = document.forms["relatorioSaida"];
        var divAno = document.getElementById('ano');
        var divUnidadeAdministrativa = document.getElementById('unidade_administrativa');
        var valor = form.Relatorio_tipo_relatorio.options[form.Relatorio_tipo_relatorio.selectedIndex].value;

        // abre combo ANO para relat�rios homologados
        if (valor==2){ 
            divAno.style.display='block';
        } else {
            divAno.style.display='none';
        }
        
        if ((valor != '') && (divUnidadeAdministrativa != null)) {
            divUnidadeAdministrativa.style.display = "block";
        } else if(divUnidadeAdministrativa != null) {
            divUnidadeAdministrativa.style.display = "none";
        }
    }    
     