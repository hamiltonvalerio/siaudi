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
            'id' => 'usuario-grid',
            'dataProvider' => $dados,
            'summaryCssClass' => 'sumario',
            'columns' => array(
		'nome_usuario',                
    		'nome_login',

		array(
				'name'=>'perfil_fk',
				'value'=>'GxHtml::valueEx($data->perfilFk)',
				'filter'=>GxHtml::listDataEx(Perfil::model()->findAllAttributes(null, true)),
				),
		array(
				'name'=>'nucleo_fk',
				'value'=>'GxHtml::valueEx($data->nucleoFk)',
				'filter'=>GxHtml::listDataEx(Nucleo::model()->findAllAttributes(null, true)),
				),
            array(
                'class' => 'application.components.MyButtonColumn',
                'template' => '{view}  {update}  {alteraSenha}  {delete}',
                'deleteConfirmation' => "js:'Deseja remover o Usu�rio '+$(this).parent().parent().children(':first-child').text()+'?'",
            	'updateButtonUrl' => 'Yii::app()->createUrl("Usuario/admin/".$data->id."?".$_SERVER["QUERY_STRING"])',
                'buttons' => array
                    (
                    'alteraSenha' => array
                        (
                        'label' => 'Alterar senha',
                        'options' => array('class' => 'add-item'),
                        'imageUrl' => Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . "/img/alterar_senha.png",
                        'url' => 'Yii::app()->createUrl("Usuario/admin",  array("id" => $data->id, "cenario" => "p") )',
                        //'visible' => '((condi��o)) ? true : false',
                    ),
                ),
             )
            )
          )
        );
    ?>
    </div>
<?php endif;?>
</div>