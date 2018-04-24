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
$dir = "../..";

// Verifica se usu�rio tem permiss�o para acessar o relat�rio
// de acordo com os perfis
$login = Yii::app()->user->login;
$perfil = strtolower(Yii::app()->user->role);
$perfil = str_replace("siaudi2", "siaudi", $perfil);

// regras de acesso para auditados
if ($perfil == "siaudi_cliente") {
    // verifica se cliente est� autorizado a acessar o relat�rio    
    $RelatorioAcesso = RelatorioAcesso::model()->findAllbyAttributes(array('relatorio_fk' => $model->id));
    foreach ($RelatorioAcesso as $vetor) {
        if ($vetor->nome_login == $login) {
            $autorizado = 1;
        }
    }
    if ($autorizado) { 
        // Verifica se existe alguma avalia��o de auditor
        // a ser feita antes de realizar a manifesta��o
        $RelatorioAcesso = RelatorioAcesso::model()->findByAttributes(array('relatorio_fk' => $model->id, 'nome_login' => $login));
        $sureg = $RelatorioAcesso->unidade_administrativa_fk;
        $avaliacao_auditor = Avaliacao::model()->VerificaAvaliacao($model->id, $sureg);
   

        // vari�vel $avaliacao_auditor cont�m o ID do auditor a 
        // ser avaliado ou vazio caso todas as avalia��es j� tenham
        // sido realizadas
        if ($avaliacao_auditor) {
            $confirma = ($_GET["confirma"]) ? "&confirma=1" : "";
            header("Location:../../Avaliacao/admin/?relatorio={$model->id}&sureg={$sureg}&auditor={$avaliacao_auditor}{$confirma}&visualizar_pdf=1");
            exit;
        } else {
            if ($_GET["confirma"] == 1) {
                $this->setFlashSuccesso(($id > 0 ? 'alterar' : 'inserir'));
            }
        }
    }
}

//regras de acesso para auditores
if ($perfil == "siaudi_auditor") {
    // criar regras
    $autorizado = 1;
}

//regras de acesso para gerentes, e outros perfis com acesso
// completo �s visualiza��es do relat�rio
if ($perfil == "siaudi_gerente" || $perfil == "siaudi_cgu" || $perfil == "siaudi_gabin" ||
          ((string) strpos($perfil, "siaudi_diretor") === (string) 0) ||
        $perfil == "siaudi_cliente_item" || $perfil == "siaudi_gerente_nucleo") {
    // criar regras
    $autorizado = 1;
}


//Verifica se o relat�rio que foi passado foi emitido pelo n�cleo. 
//O perfil siaudi_gerente_nucleo deve acessar somente os relat�rios emitidos pelo n�cleo.
//Valida��o referente a altera��o do valor do relat�rio na QueryString. 

if ($perfil == "siaudi_gerente_nucleo") {
    $relatorio = Relatorio::model()->findAll("id=" . $model->id . " and nucleo is true");
    if (!sizeof($relatorio)) {
        $autorizado = 0;
    }
}

//Valida��o caso haja altera��o da QueryString       
if ($perfil == 'siaudi_cliente_item') {
    if (!Resposta::model()->validaAcessoAoRelatorio($model->id, $login)) {
        $autorizado = 0;
    } //o usu�rio logado n�o tem nenhum item liberado para o relat�rio informado. 
}





// Verifica se o relat�rio ser� aberto em PDF.
$autorizar_pdf = Relatorio::model()->Relatorio_Autorizar_PDF($model->data_relatorio);

if ($autorizar_pdf) {
    header("Location: ../ExportarPDFAjax/" . $model->id);
}

if (!$autorizado) {
    echo "<br><br><center><font size=2 face=Verdana><b>Acesso negado.</b></font></center>";
} else {
    ?>
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" lang="pt-br">
        <head>
            <title>SIAUDI - Relat�rio de Auditoria</title>
            <!-- link rel="SHORTCUT ICON" href="<?php echo $dir; ?>/images/Logo.ico"/ -->
            <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
            <meta name="language" content="pt-BR" />    
            <link href="<?php echo $dir; ?>/css/RelatorioSaida.css" rel="stylesheet" type="text/css"></link>
            <script language="Javascript" src="<?php echo $dir; ?>/js/RelatorioSaida.js"></script>
        </head>

        <body <? /* onkeydown='return BloquearTecla(event)' onkeypress='return BloquearTecla(event)' onselectstart="return false" */ ?>>

            <table border="0" width="100%">
                <tr>
                    <td><div align="left"><img name="logo" src="<?php echo $dir; ?>/images/logo2.jpg"></div></td>
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
            </table>


            <table border="0" width="100%" cellpadding="2" cellspacing="2">
                <tr>
                    <td class="labelRelatorio"><div align="center">RELAT�RIO DE AUDITORIA ACOMPANHAMENTO DA GEST�O N�: <?php echo $model->numero_relatorio; ?></div></td>
                </tr>
                <tr>
                    <td class="labelRelatorio"><div align="center">DATA: <?php echo $model->data_relatorio; ?></div></td>
                </tr>
                <tr>
                    <td height="20"></td>
                </tr>
                <tr>
                    <td class="labelRelatorio">Do(s) Auditor(es):</td>
                </tr>
                <tr>
                    <td class="labelRelatorio2">

                        <?php
                        $Relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $model->id));
                        foreach ($Relatorio_auditor as $vetor) {
                            $Auditor = Usuario::model()->nome_usuario_completo($vetor->usuario_fk);
                            echo "<img src='" . $dir . "/img/spacer.gif' height='1' width='40'>" . strtoupper($Auditor[nome_usuario]) . "<br>";
                        }
                        ?>
                    </td>                                         
                </tr>
                <tr>
                    <td height="10"></td>
                </tr>
                <tr>
                    <td class="labelRelatorio">Ao Gerente da Auditoria:</td>
                </tr>
                <tr>
                    <td class="labelRelatorio2">
                        <?php
                        $Relatorio_gerente = RelatorioGerente::model()->findAllByAttributes(array('relatorio_fk' => $model->id));
                        foreach ($Relatorio_gerente as $vetor) {
                            $Gerente = Usuario::model()->nome_usuario_completo($vetor->usuario_fk);
                            echo "<img src='" . $dir . "/img/spacer.gif' height='1' width='40'>" . strtoupper($Gerente[nome_usuario]) . "<br>";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td height="30"></td>
                </tr>
                <tr>
                    <td class="labelRelatorio">Senhor Gerente,</td>
                </tr>
                <tr>
                    <td class="labelRelatorio2"><div align="justify"><?php echo $model->descricao_introducao; ?></div></td>
                </tr>

                <tr><td height="20"></td></tr>

                <?php
                // Chama Cap�tulos - Itens e Recomenda��es 

                $Capitulos = Capitulo::model()->findAll(array('order' => 'numero_capitulo_decimal', 'condition' => 'relatorio_fk=' . $model->id));
                if (is_array($Capitulos) && sizeof($Capitulos) > 0) {
                    foreach ($Capitulos as $vetor_capitulos) {
                        echo"<tr><td class='labelRelatorio'>" . $vetor_capitulos->numero_capitulo . " - " . $vetor_capitulos->nome_capitulo . "</td></tr>
                     <tr><td height='10'></td></tr>
                     <tr><td class='labelRelatorio2'><div align='justify'>" . $vetor_capitulos->descricao_capitulo . "</div></td></tr>
                     <tr><td height='20'></td></tr>";

                        $Itens = Item::model()->findAll(array('order' => 'numero_item, id', 'condition' => 'capitulo_fk=' . $vetor_capitulos->id));
                        if (is_array($Itens) && sizeof($Itens) > 0) {
                            foreach ($Itens as $vetor_itens) {
                                echo"<tr><td class='labelRelatorio'>" . $vetor_itens->numero_item . " - " . $vetor_itens->nome_item . "</td></tr>
                             <tr><td height='10'></td></tr>
                             <tr><td class='labelRelatorio2'><div align='justify'>" . $vetor_itens->descricao_item . "</div></td></tr>
                             <tr><td height='20'></td></tr>";

                                $Recomendacoes = Recomendacao::model()->findAll(array('order' => 'id', 'condition' => 'item_fk=' . $vetor_itens->id));
                                if (is_array($Recomendacoes) && sizeof($Recomendacoes) > 0) {
                                    $cont_recomendacao = 1;
                                    foreach ($Recomendacoes as $vetor_recomendacoes) {
                                        // se � uma recomenda��o, ent�o imprime n�mero sequencial (ex:1.1)
                                        if ($vetor_recomendacoes->recomendacao_tipo_fk == RecomendacaoTipo::model()->find("nome_tipo ilike '%recomenda��o%'")->id) {
                                            $numero_recomendacao = $vetor_itens->numero_item . "." . $cont_recomendacao;
                                            $cont_recomendacao++;
                                        } else {
                                            $numero_recomendacao = "";
                                        }
                                        // retira <p> e </p> do in�cio e do final da descricao da recomenda��o
                                        $texto_rec = $vetor_recomendacoes->descricao_recomendacao;
                                        if (substr($texto_rec, 0, 3) == "<p>") {
                                            $texto_rec = substr($texto_rec, 3, strlen($texto_rec) - 7);
                                            $rec_final = substr($texto_rec, strlen($texto_rec) - 2, strlen($texto_rec));
                                            if ($rec_final == "</") {
                                                $texto_rec = substr($texto_rec, 0, strlen($texto_rec) - 2);
                                            }
                                        }

                                        if (RecomendacaoTipo::model()->find("id = ".$vetor_recomendacoes->recomendacao_tipo_fk." AND nome_tipo ilike '%recomenda��o%'")) {
                                            $gravidade_recomendacao = RecomendacaoGravidade::model()->findByPk($vetor_recomendacoes->recomendacao_gravidade_fk);
                                            $texto_rec = $texto_rec . " (Gravidade: " . $gravidade_recomendacao[nome_gravidade] . ".)";
                                        }

                                        echo"<tr><td class='labelRecomendacao'>" . $numero_recomendacao . " " . $texto_rec . "</td></tr>
                                     <tr><td height='10'></td></tr>";
                                    }
                                }
                            }
                        }
                    }
                }
                ?>
                <tr><td height="20"></td></tr>

                <tr>
                    <td class="labelRelatorio2">
                        <?php
                        $Relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $model->id));
                        foreach ($Relatorio_auditor as $vetor) {
                            $Auditor = Usuario::model()->nome_usuario_completo($vetor->usuario_fk);
                            echo "<div align='center'>______________________________________________________<br>"
                            . strtoupper($Auditor[nome_usuario]) . "<br><br><br><br>";
                        }
                        ?>
                    </td>                                         
                </tr>        

                <?php
                // se relat�rio foi pr�-finalizado ent�o mostra despacho
                if (($model->nucleo == 't') && (($model->data_pre_finalizado) && ($model->login_pre_finaliza))) {
                    echo "  <tr><td height='20'></td></tr>
                    <tr><td class='labelRelatorio'>� Ger�ncia de Auditoria, em " . $model->data_pre_finalizado . "</td></tr>
                    <tr>
                    <td class='labelRelatorio2'><br>";
                    $Despacho = RelatorioDespacho::model()->findByAttributes(array('id' => 1));
                    echo $Despacho->descricao_pre_finalizado;

                    $AuditorChefe = Relatorio::model()->RelatorioAuditorChefe($model->id, 'pre_finaliza');
                    echo "<div align='center'><br><br>______________________________________________________<br>
                        N�CLEO REGIONAL DE AUDITORIA <br> "
                    . strtoupper($AuditorChefe[nome]) . "<br>";
                    $cargo = "COORDENADOR";//($AuditorChefe[funcao] != "") ? "COORDENADOR" : "COORDENADOR SUBSTITUTO(A)";
                    echo "<span class='labelSugestao'>" . $cargo . "</span><br><br><br>
            </td>
            </tr>";
                }
                
                
                // se relat�rio foi finalizado ent�o mostra despacho
                if ($model->data_finalizado) {
                    echo "  <tr><td height='20'></td></tr>
                    <tr><td class='labelRelatorio'>� Auditoria Interna, em " . $model->data_finalizado . "</td></tr>
                    <tr>
                    <td class='labelRelatorio2'><br>";
                    $Despacho = RelatorioDespacho::model()->findByAttributes(array('id' => 1));
                    echo $Despacho->descricao_finalizado;

                    $AuditorChefe = Relatorio::model()->RelatorioAuditorChefe($model->id, 'finaliza');
                    echo "<div align='center'><br><br>______________________________________________________<br>"
                    . strtoupper($AuditorChefe[nome]) . "<br>";
                    $cargo = "GERENTE DE AUDITORIA";//($AuditorChefe[funcao] != "") ? "GERENTE DE AUDITORIA" : "GERENTE DE AUDITORIA SUBSTITUTO(A)";
                    echo "<span class='labelSugestao'>" . $cargo . "</span><br><br><br>
            </td>
            </tr>";
                }

                // se relat�rio foi homologado ent�o mostra despacho
                if ($model->data_relatorio) {
                    echo "  <tr><td height='20'></td></tr>
                    <tr><td class='labelRelatorio'>Em " . $model->data_relatorio . "</td></tr>
                    <tr>
                    <td class='labelRelatorio2'><br>";
                    $Despacho = RelatorioDespacho::model()->findByAttributes(array('id' => 1));
                    echo $Despacho->descricao_homologado;

                    $AuditorChefe = Relatorio::model()->RelatorioAuditorChefe($model->id, 'homologa');
                    echo "<div align='center'><br><br>______________________________________________________<br>"
                    . strtoupper($AuditorChefe[nome]) . "<br>";
                    $cargo = "CHEFE";//($AuditorChefe[funcao] != "") ? "CHEFE" : "CHEFE SUBSTITUTO(A)";
                    echo "<span class='labelSugestao'>AUDITORIA INTERNA <BR>" . $cargo . "</span><br><br><br>
            </td>
            </tr>";
                }
                ?>
            </table>    

        </body>
    </html>
<?php } ?>