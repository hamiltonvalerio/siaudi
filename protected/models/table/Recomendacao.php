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

Yii::import('application.models.table._base.BaseRecomendacao');

class Recomendacao extends BaseRecomendacao
{
    public $relatorio_fk, $capitulo_fk, $unidade_administrativa_fk, 
            $especie_auditoria_fk, $categoria_fk, $diretoria_fk, $numero_capitulo,
            $numero_item, $numero_recomendacao, $qt_cliente, $qt_auditor, 
            $ultimo_st, $numero_relatorio, $bolRecomendacaoPadrao;
    
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
        
    public function attributeLabels() {
        $attribute_default = parent::attributeLabels();
        $attribute_custom = array(
            'id' => Yii::t('app', 'ID da Recomenda��o'),
            'numero_recomendacao' => Yii::t('app', 'N�mero'),
            'relatorio_fk' =>  Yii::t('app', 'ID do Relat�rio'),
            'capitulo_fk' => Yii::t('app', 'Cap�tulo'),
            'item_fk' => null,
            'recomendacao_tipo_fk' => null,
            'recomendacao_gravidade_fk' => null,
            'recomendacao_categoria_fk' => null,
            'recomendacao_subcategoria_fk' => null,
            'descricao_recomendacao' => Yii::t('app', 'Recomenda��o'),
            'data_gravacao' => Yii::t('app', 'Data de Grava��o'),
            'recomendacaoSubcategoriaFk' => null,
            'recomendacaoCategoriaFk' => null,
            'recomendacaoGravidadeFk' => null,
            'recomendacaoTipoFk' => null,
            'itemFk' => null,
            'capituloFk' => null,
            'relatorioFk' => Yii::t('app', 'ID do Relat�rio'),
            'unidade_administrativa_fk' => Yii::t('app', 'Unidade Auditada'),
            'especie_auditoria_fk' => Yii::t('app', 'Esp�cie de Auditoria'),
            'categoria_fk' => Yii::t('app', 'Categoria'),
            'diretoria_fk' => Yii::t('app', 'Diretoria'),
            'numero_capitulo' => Yii::t('app', 'N� Cap�tulo'),
            'numero_item' => Yii::t('app', 'N� Item'),
            'numero_recomendacao' => Yii::t('app', 'N� Recomenda��o'),
            'qt_cliente' => Yii::t('app', 'Qtde. Resp. Cliente'),
            'qt_auditor' => Yii::t('app', 'Qtde. Resp. Auditor'),            
            'ultimo_st' => Yii::t('app', '�ltimo Status'),
            'numero_relatorio' => Yii::t('app', 'N� Relat�rio'),            
            
        );
        return array_merge($attribute_default, $attribute_custom);
    }
    
    
    
	public function afterFind() {
        parent::afterFind();
	if (isset($this->data_gravacao))   $this->data_gravacao   = MyFormatter::converteData($this->data_gravacao);
    }      
             
        // pega o cap�tulo da recomenda��o
        public function RecomendacaoCapitulo($id_recomendacao,$link=null){
            $recomendacao = Recomendacao::model()->findbyPk($id_recomendacao);            
            $item = Item::model()->findByPk($recomendacao->item_fk);
            $capitulo = Capitulo::model()->findByAttributes(array('id'=>$item->capitulo_fk));
                if($link){ 
                    $retorno="<a href='../capitulo/" . $capitulo->id . "'>" .$capitulo->numero_capitulo . "</a>";
                } else { 
                    $retorno=$capitulo->numero_capitulo;
                }
            return $retorno; 
        }
        

        // pega o relat�rio da recomenda��o
        public function RecomendacaoRelatorio($id_recomendacao,$link=null){ 
            $recomendacao = Recomendacao::model()->findbyPk($id_recomendacao);
            $item = Item::model()->findByPk($recomendacao->item_fk);
            $capitulo = Capitulo::model()->findByAttributes(array('id'=>$item->capitulo_fk));
            $relatorio = Relatorio::model()->findByAttributes(array('id'=>$capitulo->relatorio_fk));                        
            $especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id'=>$relatorio->especie_auditoria_fk));
            $relatorio_nome = $relatorio->id ." - ". $especie_auditoria->sigla_auditoria;
                if($link){ 
                    $retorno="<a href='../relatorio/" . $relatorio->id . "'>" .$relatorio_nome. "</a>";
                } else { 
                    $retorno=$relatorio_nome;
                }
            return $retorno; 
        }        
    
        
	public function search() {
                $schema = Yii::app()->params['schema'];            
		$criteria = new CDbCriteria;
                $criteria->select = 'relatorio.*,capitulo.*,item.*,recomendacao.*, recomendacao.id as id';
                $criteria->alias = 'recomendacao';
                $join='LEFT JOIN '.$schema.'.tb_item item ON (item.id=recomendacao.item_fk)
                       LEFT JOIN '.$schema.'.tb_capitulo capitulo ON (capitulo.id=item.capitulo_fk)
                       LEFT JOIN '.$schema.'.tb_relatorio relatorio ON (relatorio.id=capitulo.relatorio_fk)';
                if ($this->diretoria_fk){
                    $join.='LEFT JOIN '.$schema.'.tb_relatorio_diretoria RD ON (RD.relatorio_fk=relatorio.id)';
                    $criteria->addcondition('RD.diretoria_fk='. $this->diretoria_fk); 
                    $criteria->distinct='manifestacao.id';
                }
                
                $criteria->join=$join;
		$criteria->compare('relatorio.id', $this->relatorio_fk);
		$criteria->compare('relatorio.especie_auditoria_fk', $this->especie_auditoria_fk);
                $criteria->compare('recomendacao.unidade_administrativa_fk', $this->unidade_administrativa_fk);  
                $criteria->compare('recomendacao.item_fk', $this->item_fk);  
                $criteria->compare('recomendacao.id', $this->id);  
                if($this->numero_capitulo) {
                        $criteria->addCondition('capitulo.numero_capitulo=\''.strtoupper($this->numero_capitulo)."'");
                }
		$criteria->compare('relatorio.categoria_fk', $this->categoria_fk);
                $criteria->addCondition('relatorio.data_relatorio IS NULL');                
                $criteria->order = 'relatorio.id DESC, capitulo.numero_capitulo_decimal ASC';

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria )
		);
        }          
        
		public function RetornaNumeroRecomendacao($numero_item, $numero_recomendacao){
			if (($numero_item) || ($numero_recomendacao)){
				return $numero_item . "." . $numero_recomendacao;
			} else {
				return "";
			}
		}
        
        
        // Retorna o n�mero de recomenda��es de um relat�rio espec�fico.
        // par�metro de entrada: ID do relat�rio
        public function RecomendacaoporRelatorio($id_relatorio){
            $schema = Yii::app()->params['schema'];                        
            $sql = "SELECT recomendacao.id
                        FROM ". $schema . ".tb_relatorio relatorio INNER JOIN
                             ". $schema . ".tb_capitulo capitulo ON capitulo.relatorio_fk=relatorio.id INNER JOIN
                             ". $schema . ".tb_item item ON item.capitulo_fk=capitulo.id INNER JOIN
                             ". $schema . ".tb_recomendacao recomendacao ON recomendacao.item_fk=item.id
                        WHERE  (recomendacao.recomendacao_tipo_fk=(SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomenda��o%' LIMIT 1)  /* Recomenda��o */ and
                                relatorio.id=".$id_relatorio.")";
            $command = Yii::app()->db->createCommand($sql);            
            $result = $command->query();
            return ($result->readAll());
        }             
        
}