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

Yii::import('application.models.table._base.BaseRelatorioSetor');

class RelatorioSetor extends BaseRelatorioSetor {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function salvar($id, $setores) {
        if (is_array($setores)) {
            foreach ($setores as $vetor) {
                $values .= " (" . $id . "," . $vetor . "),";
            }

            $values = substr($values, 0, -1);
            $schema = Yii::app()->params['schema'];
            $sql = 'INSERT INTO ' . $schema . '.tb_relatorio_setor (relatorio_fk, unidade_administrativa_fk) VALUES ' . $values;
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();
        }
    }

    public function buscar($id) {
        $schema = Yii::app()->params['schema'];
        $sql = 'select unidade_administrativa.id as unidade_administrativa_fk, unidade_administrativa.sigla
                  from ' . $schema . '.tb_relatorio_setor relatorio_setor
                 inner join ' . $schema . '.tb_unidade_administrativa unidade_administrativa on 
                       unidade_administrativa.id = relatorio_setor.unidade_administrativa_fk 
                 where relatorio_setor.relatorio_fk = ' . $id . '
                 order by unidade_administrativa.sigla';
        if ($id > 0) {
            $command = Yii::app()->db->createCommand($sql);
            $result = $command->query();
            $setores = $result->readAll();
        }
        return $setores;
    }

}