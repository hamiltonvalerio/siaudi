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

Yii::import('application.models.table._base.BaseRelatorioSureg');

class RelatorioSureg extends BaseRelatorioSureg
{
    public $unidade_administrativa_fk; 
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
       
    // Exclui as Unidades Regionais salvas do relat�rio
    // e inclui as Unidades Regionais marcadas no select m�ltiplo
    public function salvar($id, $suregs, $sureg_secundaria=false) {
    	$sureg_secundaria = $sureg_secundaria ? "true" : "false";
        $this->deleteAll("relatorio_fk=" . $id . " and sureg_secundaria='" . $sureg_secundaria  . "'");
        if (is_array($suregs)) {
            foreach ($suregs as $vetor) {
                $values .= " (" . $id . "," . $vetor . "," . $sureg_secundaria. "),";
            }

            $values = substr($values, 0, -1);
            $schema = Yii::app()->params['schema'];
            $sql = 'INSERT INTO ' . $schema . '.tb_relatorio_sureg (relatorio_fk, unidade_administrativa_fk, sureg_secundaria) VALUES ' . $values;
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();
        }
    }         
        
    
    // recebe o ID do relat�rio e retorna os nomes das Unidades Regionais
    // associadas. Se link=1 ent�o passar o link da view da Unidade Regional
    public function sureg_por_relatorio($id_relatorio,$link=null){
        $baseUrl = "..";
       //$baseUrl = Yii::app()->baseUrl;
       //$baseUrl2 = "http://".$_SERVER["HTTP_HOST"] . Yii::app()->baseUrl;
       $relatorio_sureg = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk'=>trim($id_relatorio)));
       $retorno="";
       if (sizeof($relatorio_sureg)>0){
            foreach ($relatorio_sureg as $vetor){
                $sureg = UnidadeAdministrativa::model()->findByAttributes(array('id'=>trim($vetor->unidade_administrativa_fk)));
                if($link==1){
                    $retorno.="<a href='{$baseUrl}/UnidadeAdministrativa/view/{$sureg->id}'>{$sureg->sigla}</a> / ";
                } else {
                    $retorno.="{$sureg->sigla}, ";
                }
            } 
       }
       $retorno = substr($retorno,0,-2);
       return $retorno;

    }        
}