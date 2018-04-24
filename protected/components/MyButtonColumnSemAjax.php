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

<?php

// unset array[x] - brise element polja

class MyButtonColumnSemAjax extends CButtonColumn {

    /**
     * Initializes the column.
     * This method registers necessary client script for the button column.
     * @param CGridView the grid view instance
     */
    public function init() {
       $this->afterDelete = 'function(link,success,data){ if(success) $("#mensagens").html(data); }';

        parent::init();

        $this->grid->ajaxUpdate = true;
        $this->grid->itemsCssClass = 'tabelaListagemItensYii';
        if (!Yii::app()->user->verificaPermissao($this->grid->id, "admin"))
            $this->buttons['update']['visible'] = false;

        if (!Yii::app()->user->verificaPermissao($this->grid->id, "delete"))
            $this->buttons['delete']['visible'] = false;

        $this->buttons['view']['imageUrl'] = Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . '/img/lupa.png';
        $this->buttons['update']['imageUrl'] = Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . '/img/alterar.gif';
        $this->buttons['delete']['imageUrl'] = Yii::app()->request->baseUrl . "/themes/" . Yii::app()->params["tema"] . '/img/excluir.gif';

    }

    protected function renderButton($id, $button, $row, $data) {

        if (!isset($button['visible']) || $button['visible'] === null) {
            parent::renderButton($id, $button, $row, $data);
        } else {
            if (is_string($button['visible']))
                $button['visible'] = $this->evaluateExpression($button['visible'], array('data' => $data, 'row' => $row));

            if ($button['visible'])
                parent::renderButton($id, $button, $row, $data);
        }
    }

}