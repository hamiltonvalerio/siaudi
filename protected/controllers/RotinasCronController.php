<?php
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
?>
<?php

class RotinasCronController extends GxController {

	
	public function accessRules(){
		return array( array('allow',  // allow all users to perform 'index' and 'view' actions
                                'actions'=>array('index'),
                                'users'=>array('*'),
                         ));
	}
	

	
	
	
    // Rotinas  para colocar no Cron
    public function actionIndex() {
    	$this->layout = false;
        // rotina para verificar prazo dos 5 dias �teis
        // para manifesta��o dos relat�rios finalizados
        // (encerra somente os relat�rios onde N�O HOUVE,
        //  manifesta��o contr�ria)
        $rotina = Relatorio::model()->FechaPrazoRelatorioFinalizado();    
        
        // rotina para verificar se existem crit�rios de avalia��o
        // no exerc�cio atual. Caso mude o ano e n�o haja crit�rios
        // de avalia��o cadastrados, as Unidades Regionais n�o poder�o realizar
        // a avalia��o dos auditores no momento da manifesta��o do relat�rio.
        $rotina2 = AvaliacaoCriterio::model()->VerificaAvaliacaoCriterio();  
        
        //rotina para atualizar os feriados que se repetem todo o ano.
        //Esta rotina dever� ser executada no dia 01/11 e no dia 31/12 garatindo os feriados
        //do pr�ximo ano, pois existem processos que podem depender destas datas.
        if (((date("d") == 1) && (date("m") == 11)) || ((date("d") == 31) && (date("m") == 12))) {
            $rotina3 = Feriado::model()->AtualizaFeriadosAutomaticamente();
        }
        
        //rotina para alertar o auditado que o relat�rio homologado est� sem resposta por um per�odo de 20 dias �teis ou 60 dias corridos.
        // $rotina4 = Relatorio::model()->VerificaManifestacaoAuditado();
//         
        //rotina para alertar o auditor que o relat�rio homologado est� sem resposta por um per�odo de 10 dias �teis.
        // $rotina5 = Relatorio::model()->VerificaManifestacaoAuditor();
        
        //rotina para atualizar os processos que se repetem todo o ano.
        //Esta rotina dever� ser executada no dia 01/11 e no dia 31/12 garatindo os feriados
        //do pr�ximo ano, pois existem processos que podem depender destas datas.
        if ((date("d") == 31) && (date("m") == 12)) {
        	$rotina6 = Processo::model()->AtualizaProcessosAutomaticamente();
        }
    }


}