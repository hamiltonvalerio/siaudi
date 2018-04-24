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
    $('#div_unidade_destino_externa').hide();

    if($('#Tramite_destino').val() == 0){
        $('#div_unidade_destino_interno').show();
        $('#div_unidade_destino_externa').val('').hide();
        
        $('#Tramite_unidade_destino_externa').val('');
       
    }else{
        $('#div_unidade_destino_externa').show();
        $('#div_unidade_destino_interno').val('').hide();
        
        $('#Tramite_unidade_destino_interno').val('');
    }
    
    $('#Tramite_destino').change(function(){
   
        if($(this).val() == 0){
            $('#div_unidade_destino_interno').show();
            $('#div_unidade_destino_externa').hide();
            
            $('#Tramite_unidade_destino_externa').val('');
            $('#Tramite_unidade_destino').val('');
        }else{
            $('#div_unidade_destino_externa').show();
            $('#div_unidade_destino_interno').val('').hide();
            
            $('#Tramite_unidade_destino_interno').val('');
            $('#Tramite_unidade_destino').val('');
        }
    })
});