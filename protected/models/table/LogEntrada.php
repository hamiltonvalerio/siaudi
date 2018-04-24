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

Yii::import('application.models.table._base.BaseLogEntrada');

class LogEntrada extends BaseLogEntrada
{
    public $data_entrada2;
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        // insere o usu�rio, ip, data e hora na tabela de logs.
        public function SalvaLog($id_relatorio=null,$id_item=null){
            $schema = Yii::app()->params['schema'];
            $login = Yii::app()->user->login;
            $ip = $_SERVER["REMOTE_ADDR"];
            $relatorio_fk = ($id_relatorio)? $id_relatorio:"null";
            $item_fk = ($id_item)? $id_item:"null";
            
            $sql = "INSERT INTO ".$schema.".tb_log_entrada(nome_login, data_entrada, valor_ip, relatorio_fk, item_fk)
                    VALUES ('".$login."', date_trunc('second', TIMESTAMP 'now'),'".$ip."',".$relatorio_fk.",".$item_fk.");" ;
            $command = Yii::app()->db->createCommand($sql);
            $result = $command->query();          
        }
}