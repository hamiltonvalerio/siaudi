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
<?php $baseUrl = Yii::app()->baseUrl; ?>
<style type="text/css">
<!--
	table.page_header {width: 100%; border: none; background-color: white; border-bottom: solid 1mm #FFFFFF; padding: 2mm }
	table.page_footer {width: 100%; border: none; background-color: white; border-top: solid 1mm #COCOCO; padding: 2mm}
	h1 {color: #000033}
	h2 {color: #000055}
	h3 {color: #000077}
	div.standard{ padding-left: 5mm;}
        .page { font-size: 15px; 
                  width:100%; 
                  margin-top: 5px;
                  font-weight: bold; 
                  }        
        .nivel0 { font-size: 20px; 
                  color:#0E2F62; 
                  background-color: #CCCCCC; 
                  width:100%; 
                  margin-top: 20px;
                  font-weight: bold; 
                  }
        .nivel1 { font-size: 15px; 
                  color:#0E2F62; 
                  width:100%; 
                  margin-top: 20px;
                  font-weight: bold;                                   
                  }                  
        p, P {text-indent: 50px;}
	.th {background-color: #DBDBDB; padding:5px; font-size: 13px;  }
        .even {background-color: #E8E8E8; padding:3px; font-size: 12px;   }                   
        .odd {background-color: #FFFFFF; padding:3px; font-size: 12px;   }                           
        .risco_irrelevante {
            background-color: #86B686;
            width:100px;
            height:16px;  
            font-size: 12px;
        }

        .risco_baixo {
            background-color: #B7D4B7;
            width:100px;
            height:16px; 
            font-size: 12px;
        }

        .risco_medio {
            background-color: #FFEDA5;
            width:100px;
            height:16px;
            font-size: 12px;
        }

        .risco_alto {
            background-color: #FFD089;
            width:100px;
            height:16px;
            font-size: 12px;
        }

        .risco_critico {
            background-color: #CB7C7C;
            width:100px;
            height:16px;
            font-size: 12px;
        }          
-->


</style>
<page backtop="25mm" backbottom="30mm" backleft="10mm" backright="10mm" style="font-size: 12pt">
	<page_header>
		<table class="page_header">
			<tr>
				<td style="width:100%; text-align: center">
                                   	<img src="<?php echo $baseUrl;?>/images/logo.jpg">
				</td>
			</tr>
		</table>
	</page_header>
	<page_footer>
		<table class="page_footer">
			<tr>
				<td style="width: 100%; text-align: center">
                                    <i>Plano Anual de Atividades da Auditoria Interna - PAINT - exerc�cio 
                                        <?php echo $_GET["exercicio"]; ?></i> <br>
                                        <span class="page">[[page_cu]]</span>
				</td>
			</tr>
		</table>
	</page_footer>

</page>

<?php 
    echo "<page pageset=\"old\">"; 
    foreach ($dados as $vetor){ 
        $titulo = str_replace(array("<p>","</p>"),"",$vetor->nome_titulo); 
        $texto = str_replace("../js",$baseUrl."/js", $vetor->descricao_texto); 
        
        // retirando espa�amento desnecess�rio
        /*
        $texto = str_replace(array("<p style=\"text-align: justify;\">&nbsp;</p>",
                                   "<p style=\"text-align: left;\">&nbsp;</p>",
                                   "<p style=\"text-align: right;\">&nbsp;</p>",
                                   "<p style=\"text-align: center;\">&nbsp;</p>"), "", $texto);
        */
        // colocando espa�amento na primeira linha do par�grafo
        $texto = str_replace("<p ","&nbsp; &nbsp; &nbsp; &nbsp; <p ", $texto); 
        
        //trocando orienta��o da p�gina: retrato / paisagem
        $texto = str_replace("<p><span style=\"background-color: #a0a0a0;\">((Inverter_orientacao_da_pagina))</span>&nbsp;</p>","</page><page pageset=\"old\" orientation=\"l\">", $texto);
        $texto = str_replace("andale mono,","", $texto);         
        

        // substituindo atributos
        $atributo_risco = file_get_contents(Yii::app()->getBaseUrl(true) .  "/risco/GerarPDFAjax?exercicio=".$_GET["exercicio"]);
        $texto = str_replace("<span style=\"background-color: #a0a0a0;\">((Tabela_de_Risco))</span>",$atributo_risco, $texto);
        

        
        $numeracao= $vetor->numero_pdf;     
        $nivel = substr_count($numeracao, "."); 

            echo    "
                    <bookmark title='{$numeracao} - {$titulo}' level='{$nivel}' ></bookmark>
                    <div class='nivel{$nivel}'> {$numeracao} - {$titulo} </div><br>
                    {$texto} 
                    ";

    }
    echo "</page>";
 
 exit; ?>
 