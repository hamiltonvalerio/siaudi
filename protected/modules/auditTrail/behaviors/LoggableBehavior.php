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
class LoggableBehavior extends CActiveRecordBehavior
{
	private $_oldattributes = array();

	public function afterSave($event)
	{
		try {
			$username = Yii::app()->user->Name;
			$userid = Yii::app()->user->id;
		} catch(Exception $e) { //If we have no user object, this must be a command line program
			$username = "NO_USER";
			$userid = null;
		}
		
		if(empty($username)) {
			$username = "NO_USER";
		}
		
		if(empty($userid)) {
			$userid = null;
		}
	
		$newattributes = $this->Owner->getAttributes();
		$oldattributes = $this->getOldAttributes();
		
		if (!$this->Owner->isNewRecord) {
			// compare old and new
			foreach ($newattributes as $name => $value) {
				if (!empty($oldattributes)) {
					$old = $oldattributes[$name];
				} else {
					$old = '';
				}

				if ($value != $old) {
					$log=new AuditTrail();
					$log->old_value = $old;
					$log->new_value = $value;
					$log->action = 'CHANGE';
					$log->model = get_class($this->Owner);
					$log->model_id = $this->Owner->getPrimaryKey();
					$log->field = $name;
					$log->stamp = date('Y-m-d H:i:s');
					$log->user_id = $userid;
					
					$log->save();
				}
			}
		} else {
			$log=new AuditTrail();
			$log->old_value = '';
			$log->new_value = '';
			$log->action=		'CREATE';
			$log->model=		get_class($this->Owner);
			$log->model_id=		 $this->Owner->getPrimaryKey();
			$log->field=		'N/A';
			$log->stamp= date('Y-m-d H:i:s');
			$log->user_id=		 $userid;
			
			$log->save();
			
			
			foreach ($newattributes as $name => $value) {
				$log=new AuditTrail();
				$log->old_value = '';
				$log->new_value = $value;
				$log->action=		'SET';
				$log->model=		get_class($this->Owner);
				$log->model_id=		 $this->Owner->getPrimaryKey();
				$log->field=		$name;
				$log->stamp= date('Y-m-d H:i:s');
				$log->user_id=		 $userid;
				$log->save();
			}
			
			
			
		}
		return parent::afterSave($event);
	}

	public function afterDelete($event)
	{
	
		try {
			$username = Yii::app()->user->Name;
			$userid = Yii::app()->user->id;
		} catch(Exception $e) {
			$username = "NO_USER";
			$userid = null;
		}

		if(empty($username)) {
			$username = "NO_USER";
		}
		
		if(empty($userid)) {
			$userid = null;
		}
		
		$log=new AuditTrail();
		$log->old_value = '';
		$log->new_value = '';
		$log->action=		'DELETE';
		$log->model=		get_class($this->Owner);
		$log->model_id=		 $this->Owner->getPrimaryKey();
		$log->field=		'N/A';
		$log->stamp= date('Y-m-d H:i:s');
		$log->user_id=		 $userid;
		$log->save();
		return parent::afterDelete($event);
	}

	public function afterFind($event)
	{
		// Save old values
		$this->setOldAttributes($this->Owner->getAttributes());
		
		return parent::afterFind($event);
	}

	public function getOldAttributes()
	{
		return $this->_oldattributes;
	}

	public function setOldAttributes($value)
	{
		$this->_oldattributes=$value;
	}
}
?>