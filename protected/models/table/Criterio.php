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

Yii::import('application.models.table._base.BaseCriterio');

class Criterio extends BaseCriterio {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'id' => Yii::t('app', 'ID'),
            'valor_exercicio' => Yii::t('app', 'Exerc�cio'),
            'nome_criterio' => Yii::t('app', 'Crit�rio'),
            'tipo_criterio_fk' => null,
            'valor_peso' => Yii::t('app', 'Peso'),
            'descricao_criterio' => Yii::t('app', 'Descri��o'),
            'tipoCriterioFk' => null,
        );
        return array_merge($attribute_default, $attribute_custom);
    }

    // carrega o tipo de crit�rio (relev�ncia estrat�gica, materialidade, etc)
    // de acordo com o crit�rio
    public function carrega_criterios_subrisco($criterio) {
        $schema = Yii::app()->params['schema'];
        $sql = "SELECT t.nome_criterio, c.valor_exercicio FROM " . $schema . ".tb_tipo_criterio t LEFT JOIN " .
                $schema . ".tb_criterio c ON (t.id=c.tipo_criterio_fk)
                        WHERE ( c.id=" . $criterio . ") order by t.id ASC";
        $command = Yii::app()->db->createCommand($sql);
        $result = $command->query();
        return ($result->read());
    }

}