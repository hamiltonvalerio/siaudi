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
// carrega tela de consulta conforme perfil do usu�rio 
//$arquivo = "_search_auditor";
$perfil = strtolower(Yii::app()->user->role);
$perfil = str_replace("siaudi2","siaudi",$perfil);
$arquivo = "_search_cliente_item"; // arquivo padr�o; 
if ($perfil=="siaudi_cliente") { $arquivo = "_search_cliente"; }
if ($perfil=="siaudi_auditor") {$arquivo = "_search_auditor"; }
if ($perfil=="siaudi_gerente" || $perfil=="siaudi_chefe_auditoria" || $perfil == 'siaudi_gerente_nucleo') { $arquivo = "_search_gerente"; }


 
$this->renderPartial($arquivo, array('model' => $model,));
?>