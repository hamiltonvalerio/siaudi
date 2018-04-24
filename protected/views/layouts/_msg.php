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
<!-- mensagems-->
<div id="mensagens">
    <?php if (Yii::app()->user->hasFlash('successo')): ?>
        <div class="sucesso">
            <p><?php echo Yii::app()->user->getFlash('successo'); ?></p>
        </div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('erro')): ?>
        <div class="erro">
            <p><?php echo Yii::app()->user->getFlash('erro'); ?></p>
        </div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('orientacao')): ?>
        <div class="orientacao">
            <p><?php echo Yii::app()->user->getFlash('orientacao'); ?></p>
        </div>
    <?php endif; ?>

    <?php if (Yii::app()->user->hasFlash('aviso')): ?>
        <div class="alerta">
            <p><?php echo Yii::app()->user->getFlash('aviso'); ?></p>
        </div>
    <?php endif; ?>

    <?php
    Yii::app()->clientScript->registerScript(
            'escondeMsg',
            '$(".sucesso").animate({opacity: 1.0}, 3000).fadeOut("slow");
             $(".alerta").animate({opacity: 1.0}, 5000).fadeOut("slow");'
            , CClientScript::POS_READY
    );
    ?>
</div>