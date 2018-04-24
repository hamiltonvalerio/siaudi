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
<?php  echo $this->renderPartial('/layouts/_dialogo_view'); ?>

<?php $this->renderPartial('_search', array(
    'model' => $model,
));
?>
<?php if ( !is_null($dados) ): ?><div class="tabelaListagemItensWrapper">
    <div class="tabelaListagemItens">
    <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'item-grid',
            'dataProvider' => $dados,
            'summaryCssClass' => 'sumario',
            'enableSorting'=>false,
            'columns' => array(
    		//'id',
                      array(
                      'name'=>'relatorio_fk',
                      'value'=>'Item::model()->ItemRelatorio($data->id)',
                      ),                  
                      array(
                      'name'=>'unidade_administrativa_fk',
                      'value'=>'RelatorioSureg::model()->sureg_por_relatorio(Item::model()->ItemRelatorioID($data->id))',
                      ),  
                
		array(
				'name'=>'capitulo_fk',
				'value'=>'Capitulo::model()->findByAttributes(array("id"=>$data->capitulo_fk))',
				),                                
                
		array(
				'name'=>'nome_capitulo',
				'value'=>'Capitulo::model()->capitulo_titulo($data->capitulo_fk)',
				),
                

		'nome_item',
                'valor_reais',
		array(
			'name'=>'objeto_fk',
			'value'=>'GxHtml::valueEx($data->objetoFk)',
			'filter'=>GxHtml::listDataEx(Objeto::model()->findAllAttributes(null, true)),
		     ),
                

		/*
                'data_gravacao', 
                'descricao_item', 
		
		*/
            array(
                'class' => 'application.components.MyButtonColumn',
                'deleteConfirmation' => "js:'Deseja remover o Item '+$(this).parent().parent().children(':first-child').text()+'?'",
                'updateButtonUrl' => 'Yii::app()->createUrl("Item/admin",  array("id" => $data->id) )',
                'template' => '{buttomCapitulo} {view} {update} {delete}',                        
                'buttons' => array(
                    'buttomCapitulo' => array(
                        'label' => 'Mostrar Recomenda��es cadastradas para este Item',
                        'url' => 'Yii::app()->createUrl("/recomendacao/index?yt0=Consultar&Recomendacao%5Bitem_fk%5D=".$data->id."&Recomendacao%5Brelatorio_fk%5D=".Item::model()->ItemRelatorioID($data->id)."")."&Recomendacao%5Bnumero_capitulo%5D=".strtolower(Capitulo::model()->findByAttributes(array("id"=>$data->capitulo_fk)))',
                        'imageUrl' => Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/subitens.png",
                    ),
                )                   
             )
            )
          )
        );
    ?>
    </div>
<?php endif;?>
</div>
