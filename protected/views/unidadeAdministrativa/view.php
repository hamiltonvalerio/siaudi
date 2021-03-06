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

<?php $this->widget('zii.widgets.CDetailView', array(
	'data' => $model,
	'attributes' => array(
'nome',
'sigla',
'uf_fk',
array(
			'name' => 'subordinanteFk',
			'type' => 'raw',
			'value' => $model->subordinanteFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->subordinanteFk)), array('unidadeAdministrativa/view', 'id' => GxActiveRecord::extractPkValue($model->subordinanteFk, true))) : null,
			),
                        'data_extincao',            
	),
)); ?>

<?php
//	echo GxHtml::openTag('ul');
//	foreach($model->unidadeAdministrativas as $relatedModel) {
//		echo GxHtml::openTag('li');
//		echo GxHtml::link(GxHtml::encode(GxHtml::valueEx($relatedModel)), array('unidadeAdministrativa/view', 'id' => GxActiveRecord::extractPkValue($relatedModel, true)));
//		echo GxHtml::closeTag('li');
//	}
//	echo GxHtml::closeTag('ul');
?>