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
'nome_login',
'nome_usuario',
array(
			'name' => 'perfilFk',
			'type' => 'raw',
			'value' => $model->perfilFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->perfilFk)), array('perfil/view', 'id' => GxActiveRecord::extractPkValue($model->perfilFk, true))) : null,
			),
array(
			'name' => 'nucleoFk',
			'type' => 'raw',
			'value' => $model->nucleoFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->nucleoFk)), array('nucleo/view', 'id' => GxActiveRecord::extractPkValue($model->nucleoFk, true))) : null,
			),
array(
			'name' => 'unidadeAdministrativaFk',
			'type' => 'raw',
			'value' => $model->unidadeAdministrativaFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->unidadeAdministrativaFk)), array('unidadeAdministrativa/view', 'id' => GxActiveRecord::extractPkValue($model->unidadeAdministrativaFk, true))) : null,
			),
array(
			'name' => 'cargoFk',
			'type' => 'raw',
			'value' => $model->cargoFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->cargoFk)), array('cargo/view', 'id' => GxActiveRecord::extractPkValue($model->cargoFk, true))) : null,
			),			
array(
			'name' => 'substitutoFk',
			'type' => 'raw',
			'value' => $model->substitutoFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->substitutoFk)), array('usuario/view', 'id' => GxActiveRecord::extractPkValue($model->substitutoFk, true))) : null,
			),
array(
			'name' => 'funcaoFk',
			'type' => 'raw',
			'value' => $model->funcaoFk !== null ? GxHtml::link(GxHtml::encode(GxHtml::valueEx($model->funcaoFk)), array('funcao/view', 'id' => GxActiveRecord::extractPkValue($model->funcaoFk, true))) : null,
			),
	),
)); ?>

