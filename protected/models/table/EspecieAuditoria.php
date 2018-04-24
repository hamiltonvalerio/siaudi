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

Yii::import('application.models.table._base.BaseEspecieAuditoria');

class EspecieAuditoria extends BaseEspecieAuditoria
{

    public $especie_auditoria; 

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();

        $attribute_custom = array(
            'id' => Yii::t('app', 'ID'),
            'nome_auditoria' => Yii::t('app', 'Nome'),
            'sigla_auditoria' => Yii::t('app', 'Sigla'),
            'especie_auditoria' => Yii::t('app', 'Esp�cie de Auditoria'),
        );
        return array_merge($attribute_default, $attribute_custom);
    }
    
    
    // recebe ID do Relat�rio e retorna a esp�cie da auditoria
    public function EspecieAuditoria_Relatorio($id_relatorio) {
        $relatorio = Relatorio::model()->find('id='.$id_relatorio);
        $especie_auditoria=$this->find('id='.$relatorio->especie_auditoria_fk);
        return ($especie_auditoria->nome_auditoria);
    }
    
    // recebe ID do capitulo e retorna a esp�cie da auditoria
    public function EspecieAuditoria_Capitulo($id_capitulo) {
        $capitulo = Capitulo::model()->find('id='.$id_capitulo); 
        $relatorio = Relatorio::model()->find('id='.$capitulo->relatorio_fk);
        $especie_auditoria=$this->find('id='.$relatorio->especie_auditoria_fk);
        return ($especie_auditoria->nome_auditoria);
    }
            
        
}