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
<?php  echo $this->renderPartial('/layouts/_dialogo_view'); 

?>

<?php $this->renderPartial('_search', array(
    'model' => $model,
));
?>
<?php if ( !is_null($dados) ): ?><div class="tabelaListagemItensWrapper">
    <div class="tabelaListagemItens">
    <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'recomendacao-grid',
            'dataProvider' => $dados,
            'summaryCssClass' => 'sumario',
            'enableSorting'=>false,        
            'columns' => array(
		array(
				'name'=>'relatorio_fk',
				'value'=>'Recomendacao::model()->RecomendacaoRelatorio($data->id)',
				),
		array(
        		'name'=>'numero_recomendacao',
            	'value'=>'Recomendacao::model()->RetornaNumeroRecomendacao($data->itemFk->numero_item, $data->numero_recomendacao)',
				'type' => 'raw',
				'htmlOptions' => array(
						'style' => 'text-align: center;',
				),				
		),            		     		
		array(
				'name'=>'capitulo_fk',
				'value'=>'Recomendacao::model()->RecomendacaoCapitulo($data->id)',				
		),
		array(
				'name'=>'item_fk',
				'value'=>'GxHtml::valueEx($data->itemFk)',
				'filter'=>GxHtml::listDataEx(Item::model()->findAllAttributes(null, true)),
				),
		array(
				'name'=>'unidade_administrativa_fk',
				'value'=>'UnidadeAdministrativa::model()->sureg_por_id($data->unidade_administrativa_fk,1)',
				),                

		
		array(
				'name'=>'recomendacao_tipo_fk',
				'value'=>'GxHtml::valueEx($data->recomendacaoTipoFk)',
				'filter'=>GxHtml::listDataEx(RecomendacaoTipo::model()->findAllAttributes(null, true)),
				),
		array(
				'name'=>'recomendacao_categoria_fk',
				'value'=>'GxHtml::valueEx($data->recomendacaoCategoriaFk)',
				'filter'=>GxHtml::listDataEx(RecomendacaoCategoria::model()->findAllAttributes(null, true)),
				),
		array(
				'name'=>'recomendacao_subcategoria_fk',
				'value'=>'GxHtml::valueEx($data->recomendacaoSubcategoriaFk)',
				'filter'=>GxHtml::listDataEx(RecomendacaoSubcategoria::model()->findAllAttributes(null, true)),
				),                
		array(
				'name'=>'recomendacao_gravidade_fk',
				'value'=>'GxHtml::valueEx($data->recomendacaoGravidadeFk)',
				'filter'=>GxHtml::listDataEx(RecomendacaoGravidade::model()->findAllAttributes(null, true)),
				),                
/*                


		'descricao_recomendacao',
		'data_gravacao',
		*/
            array(
                'class' => 'application.components.MyButtonColumn',
                'deleteConfirmation' => "js:'Deseja remover o Recomendacao '+$(this).parent().parent().children(':first-child').text()+'?'",
                'updateButtonUrl' => 'Yii::app()->createUrl("Recomendacao/admin",  array("id" => $data->id) )',
             )
            )
          )
        );
    ?>
    </div>
<?php endif;?>
</div>