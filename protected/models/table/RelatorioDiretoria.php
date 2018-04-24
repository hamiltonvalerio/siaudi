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

Yii::import('application.models.table._base.BaseRelatorioDiretoria');

class RelatorioDiretoria extends BaseRelatorioDiretoria
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
       
    // Exclui as diretorias salvos do relat�rio
    // e inclui as diretorias marcadas no select m�ltiplo
    public function salvar($id, $diretorias) {
        $this->deleteAll("relatorio_fk=" . $id);
        if (is_array($diretorias)) {
            foreach ($diretorias as $vetor) {
                $values .= " (" . $id . "," . $vetor . "),";
            }

            $values = substr($values, 0, -1);
            $schema = Yii::app()->params['schema'];
            $sql = 'INSERT INTO ' . $schema . '.tb_relatorio_diretoria (relatorio_fk, diretoria_fk) VALUES ' . $values;
            $command = Yii::app()->db->createCommand($sql);
            $command->execute();
        }
    }        
    

    // recebe o ID do relat�rio e retorna os nomes das diretorias
    // associadas. Se link=1 ent�o passar o link da view da diretoria
    public function diretoria_por_relatorio($id_relatorio,$link=null){
       $baseUrl = "..";        
       //$baseUrl = Yii::app()->baseUrl;
       //$baseUrl2 = "http://".$_SERVER["HTTP_HOST"] . Yii::app()->baseUrl;
       $relatorio_diretoria = RelatorioDiretoria::model()->findAllByAttributes(array('relatorio_fk'=>trim($id_relatorio)));
       $retorno="";
       if (sizeof($relatorio_diretoria)>0){
            foreach ($relatorio_diretoria as $vetor){
                $diretoria = UnidadeAdministrativa::model()->findByAttributes(array('id'=>trim($vetor->diretoria_fk)));
                if($link==1){
                    $retorno.="<a href='{$baseUrl}/UnidadeAdministrativa/view/{$diretoria->id}'>{$diretoria->sigla}</a> / ";
                } else {
                    $retorno.="{$diretoria->sigla}, ";
                }
            } 
       }
       $retorno = substr($retorno,0,-2);
       return $retorno;
    }        
}