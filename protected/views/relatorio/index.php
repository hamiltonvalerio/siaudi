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
                'id' => 'relatorio-grid',
                'dataProvider' => $dados,
                'summaryCssClass' => 'sumario',
                'enableSorting'=>false,
                'columns' => array(
                    'id',
                  //  'numero_relatorio',
                  
                    array(
                        'name' => 'especie_auditoria_fk',
                        'value' => 'GxHtml::valueEx($data->especieAuditoriaFk)',
                        'filter' => GxHtml::listDataEx(EspecieAuditoria::model()->findAllAttributes(null, true)),
                    ),	
                    array(
                        'name' => 'categoria_fk',
                        'value' => '$data->categoriaFk',
                       // 'filter' => GxHtml::listDataEx(Categoria::model()->findAllAttributes(null, true)),
                    ),
                      array(
                      'name'=>'diretoria_fk',
                      'value'=>'RelatorioDiretoria::model()->diretoria_por_relatorio($data->id)',
                      ),   
                      array(
                      'name'=>'unidade_administrativa_fk',
                      'value'=>'RelatorioSureg::model()->sureg_por_relatorio($data->id)',
                      ),
					array(
                        'class' => 'application.components.MyButtonColumn',
                        'deleteConfirmation' => "js:'Deseja remover o Relatorio '+$(this).parent().parent().children(':first-child').text()+'?'",
                        'updateButtonUrl' => 'Yii::app()->createUrl("Relatorio/admin",  array("id" => $data->id) )',
                        'template' => '{buttomCapitulo} {view} {update} {delete}',                        
                        'buttons' => array(
                            'buttomCapitulo' => array(
                                'label' => 'Mostrar Cap�tulos cadastrados para este Relat�rio',
                                'url' => 'Yii::app()->createUrl("/capitulo/index?yt0=Consultar&Capitulo%5Brelatorio_fk%5D=".$data->id)',
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