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
<?php echo $this->renderPartial('/layouts/_dialogo_view'); ?>

<?php
$this->renderPartial('_search', array(
    'model' => $model,
));


$baseUrl = Yii::app()->baseUrl; 
$cs = Yii::app()->getClientScript();    
$cs->registerScriptFile($baseUrl.'/js/RelatorioAcessoItem.js');   
$login = Yii::app()->user->login;
?>

<?php if (sizeof($dados)):?>

        <?php
        $form = $this->beginWidget('GxActiveForm', array(
            'action' => Yii::app()->createUrl($this->route),
            'method' => 'post',
            'id' => 'acesso_item',
        ));
        ?>

    <div class="formulario" Style="width: 95%;">
        <input type="hidden" id="relatorio_acesso_item_id" name='relatorio_acesso_item_id' value=''>
        <div class="tabelaListagemItensWrapper">
            <div align="center" >
                <?php
                //echo "<b>Relat�rio de Auditoria N�: " . $dados[0]['numero_relatorio'] . " de " . MyFormatter::converteData($dados[0]['data_relatorio']) . "</b>";
                ?>
            </div>
            <div class="formulario" Style="width: 95%;">
                <div class="tabelaListagemItens">
                    <table border="0" cellpadding="0" cellpadding="30" align="center">
                        <tr>
                            <th align="center" bgcolor="#FFFFFF" rowspan="1">N� do Relat�rio</th>
                            <th align="center" bgcolor="#FFFFFF" rowspan="1">Cap�tulo</th>
                            <th align="center" bgcolor="#FFFFFF" rowspan="1">Item</th>
                            <th align="center" bgcolor="#FFFFFF" rowspan="1">Login</th>
                            <th align="center" bgcolor="#FFFFFF" rowspan="1" width="80">Unidade Regional</th>
                            <th align="center" bgcolor="#FFFFFF" rowspan="1" width="80">Revogar Acesso</th>
                        </tr>
                        <? foreach ($dados as $vetor): ?>
                            <?php
                                $capitulo = Capitulo::model()->findByPk($vetor['capitulo_fk']);
                                $item = Item::model()->findByPk($vetor['item_fk']);
                                $sureg = UnidadeAdministrativa::model()->findByPk($vetor['unidade_administrativa_fk']);
                                // data do relat�rio - $data_relatorio=MyFormatter::converteData($vetor['data_relatorio']);
                                $data_relatorio = explode("-",$vetor['data_relatorio']);
                                $data_relatorio = $data_relatorio[0];
                            ?>
                            <tr>
                                <td align='center'><?= $vetor['numero_relatorio'] . "/" . $data_relatorio; ?></td>
                                <td align='left'><?= $capitulo['numero_capitulo'] . " - " . $capitulo['nome_capitulo']; ?></td>
                                <td align='left'><?= $item['numero_item'] . " - " . $item['nome_item']; ?></td>
                                <td align='center'><?= $vetor['nome_login']; ?></td>
                                <td align='center'><?= $sureg['sigla']; ?> </td>
                                <?php if ($login == $vetor['nome_login_cliente']): ?>
                                    <td aling='center' height="20" ><center>
                                        <a class="imitacaoBotao" href="<?= 'javascript:valida(' . $vetor['id'] . ", '" . $vetor['nome_login_cliente']  . "', '". Yii::app()->user->login ."');" ?> ">Revogar</a>
                            </center></td>
                                <?php else: ?>
                                    <td></td>
                                <?php endif; ?>
                            </tr>
                        <? endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
<?php elseif($_GET != null): ?>
<div style="height: 5px;"></div>
<div class="formulario" Style="width: 70%;">
<fieldset class="visivel">
    <legend class="legendaDiscreta"></legend>   
    <div class='row'>
        <div class='label' Style="width: 100%;">N�o foram encontrados registros para o(s) par�metro(s) informado(s).</div>
    </div>
</fieldset>    
</div>
<?php endif; ?>
        