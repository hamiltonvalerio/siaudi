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
?>
        <?php if (!is_null($dados)): ?><div class="tabelaListagemItensWrapper">
        <div class="tabelaListagemItens">
            <?php
            $this->widget('zii.widgets.grid.CGridView', array(
                'id' => 'capitulo-grid',
                'dataProvider' => $dados,
                'summaryCssClass' => 'sumario',
                'enableSorting'=>false,                
                'columns' => array(
                    array(
                        'name' => 'relatorio_fk',
                        'value' => 'Capitulo::model()->CapituloRelatorio($data->id)',
                    ),
                    array(
                        'name' => 'especieauditoriaFK',
                        'value' => 'EspecieAuditoria::model()->EspecieAuditoria_Relatorio($data->relatorio_fk)',
                    ),
                      array(
                      'name'=>'unidade_administrativa_fk',
                      'value'=>'RelatorioSureg::model()->sureg_por_relatorio($data->relatorio_fk)',
                      ),                         
                    
                    'numero_capitulo',
                    'nome_capitulo',
                    /*                    
                    'descricao_capitulo',
                    'data_gravacao',
                      'numero_capitulo_decimal',
                     */
                    array(
                        'class' => 'application.components.MyButtonColumn',
                        'deleteConfirmation' => "js:'Deseja remover o Capitulo '+$(this).parent().parent().children(':first-child').text()+'?'",
                        'updateButtonUrl' => 'Yii::app()->createUrl("Capitulo/admin",  array("id" => $data->id) )',
                        'template' => '{buttomCapitulo} {view} {update} {delete}',                        
                        'buttons' => array(
                            'buttomCapitulo' => array(
                                'label' => 'Mostrar Itens cadastrados para este Cap�tulo',
                                'url' => 'Yii::app()->createUrl("/item/index?yt0=Consultar&Item%5Bcapitulo_fk%5D=".$data->id."&Item%5Bnumero_capitulo%5D=".$data->numero_capitulo."&Item%5Brelatorio_fk%5D=".$data->relatorio_fk)',
                                'imageUrl' => Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/subitens.png",

                                                             
                            ),
                        )                        
                      
                    )
                )
                    )
            );
            ?>
        </div>
<?php endif; ?>
</div>