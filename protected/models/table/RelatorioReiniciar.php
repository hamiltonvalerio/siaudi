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

Yii::import('application.models.table._base.BaseRelatorioReiniciar');

class RelatorioReiniciar extends BaseRelatorioReiniciar
{
    public static function model($className=__CLASS__) {
            return parent::model($className);
    }

    public function afterFind() {
    parent::afterFind();
    if (isset($this->data))  $this->data = MyFormatter::converteData($this->data);        
    }            
    
    // insere na tabela  relatorio_reiniciar um registro
    // TRUE para que a contagem possa ser reiniciada na pr�xima
    // homologa��o
    public  function ReiniciarContagem(){
    $login = Yii::app()->user->login;
    $schema = Yii::app()->params['schema'];
    $data = date("Y-m-d");
    $sql = "INSERT into ". $schema . ".tb_relatorio_reiniciar
            VALUES(true,'".$data."','".$login."')" ;
    $command = Yii::app()->db->createCommand($sql);
    $result = $command->query();  
    }
    
    // desabilita o rein�cio da contagem (marca
    // todos os 'st_reiniciar' para FALSE
    public  function DesabilitarReiniciarContagem(){
    $schema = Yii::app()->params['schema'];
    $sql = "UPDATE ". $schema . ".tb_relatorio_reiniciar SET st_reiniciar=false" ;
    $command = Yii::app()->db->createCommand($sql);
    $result = $command->query();  
    }    
    
    //Verifica se a contagem de item precisa ser reiniciada
    // (retorna as tuplas com TRUE da tabela tb_relatorio_reiniciar)
    public  function VerificaReiniciarContagem(){
    $login = Yii::app()->user->login;
    $schema = Yii::app()->params['schema'];
    $data = date("Y-m-d");
    $sql = "SELECT * from ". $schema . ".tb_relatorio_reiniciar where st_reiniciar=true" ;
    $command = Yii::app()->db->createCommand($sql);
    $result = $command->query();  
    return ($result->readAll());
    }    
}