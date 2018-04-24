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

Yii::import('application.models.table._base.BaseRelatorio');

class Relatorio extends BaseRelatorio {

	public $unidade_administrativa_fk, $diretoria_fk, $gerente_fk, $tipo_relatorio,
	$dias_uteis, $ano, $prazo_manifestacao, $relatorio_riscopos,
	$periodo_inicio, $periodo_fim, $filtro_acesso, $sureg_secundaria;

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function attributeLabels() {
		$attribute_default = parent::attributeLabels();

		$attribute_custom = array(
            'id' => Yii::t('app', 'ID do Relat�rio'),
            'relatorio_fk' => Yii::t('app', 'N� Relat�rio'),
            'numero_relatorio' => Yii::t('app', 'N�mero'),
            'data_relatorio' => Yii::t('app', 'Data de Homologa��o'),
            'especie_auditoria_fk' => null,
            'descricao_introducao' => Yii::t('app', 'Mensagem de Introdu��o'),
            'categoria_fk' => null,
            'data_pre_finalizado' => Yii::t('app', 'Data de Pr�-Finaliza��o'),
            'data_finalizado' => Yii::t('app', 'Data de Finaliza��o'),
            'valor_prazo' => Yii::t('app', 'Prazo'),
            'st_libera_homologa' => Yii::t('app', 'Libera Homologa��o'),
            'data_gravacao' => Yii::t('app', 'Data de Grava��o'),
            'data_regulariza' => Yii::t('app', 'Data de Regulariza��o'),
            'login_relatorio' => Yii::t('app', 'Login Cria��o'),
            'login_finaliza' => Yii::t('app', 'Login Finaliza��o'),
            'login_pre_finaliza' => Yii::t('app', 'Login Pr�-Finaliza'),
            'login_homologa' => Yii::t('app', 'Login Homologa��o'),
            'diretoria_fk' => Yii::t('app', 'Diretoria/Presid�ncia'),
            'unidade_administrativa_fk' => Yii::t('app', 'Unidade Auditada'),
            'auditor_fk' => Yii::t('app', 'Do(s) Auditor(es)'),
            'gerente_fk' => Yii::t('app', 'Ao Gerente de Auditoria'),
            'tipo_relatorio' => Yii::t('app', 'Tipo de relat�rio'),
            'ano' => Yii::t('app', 'Ano'),
            'prazo_manifestacao' => Yii::t('app', 'Prazo para manifesta��o'),
            'relatorio_riscopos' => Yii::t('app', 'Riscos P�s Identificados'),
            'nucleo' => Yii::t('app', 'Emitido pelo N�cleo'),
            'area' => Yii::t('app', '�rea(s)'),
            'setor' => Yii::t('app', 'Setor(es)'),
            'plan_especifico_fk' =>Yii::t('app', 'Planejamento Espec�fico'),
            'sureg_secundaria' => Yii::t('app', 'Unidade Secund�ria (relacionada)'),
            'planEspecificoFk' => null,
            'especieAuditoriaFk' => null,
            'categoriaFk' => null,
				'objetoFk' =>Yii::t('app', 'Objeto'),
		);
		return array_merge($attribute_default, $attribute_custom);
	}

	public function afterFind() {
		parent::afterFind();
		if (isset($this->data_relatorio))
		$this->data_relatorio = MyFormatter::converteData($this->data_relatorio);
		if (isset($this->data_pre_finalizado))
		$this->data_pre_finalizado = MyFormatter::converteData($this->data_pre_finalizado);
		if (isset($this->data_finalizado))
		$this->data_finalizado = MyFormatter::converteData($this->data_finalizado);
		if (isset($this->data_gravacao))
		$this->data_gravacao = MyFormatter::converteData($this->data_gravacao);
		if (isset($this->data_regulariza))
		$this->data_regulariza = MyFormatter::converteData($this->data_regulariza);
		if (isset($this->nucleo) && ($this->nucleo == ''))
		$this->nucleo = 0;
	}

	// pega todos os relat�rios discriminados (ou um relat�rio espec�fico),
	// por esp�cie de auditoria para carregar a combo
	// dos cap�tulos, itens e recomenda��es.
	// A vari�vel par�metros serve para filtrar relat�rios n�o finalizados, exemplo data_finalizado=null
        // @homologados (boolean) - se verdadeiro, ent�o apresenta a label com n� do relat�rio e data
	public function relatorio_por_especie($id_relatorio = null, $parametros = null, $homologados=null) {
		$schema = Yii::app()->params['schema'];
		$sql_id_relatorio = ($id_relatorio) ? " WHERE t.id=" . $id_relatorio : null;
		$sql_parametros = ($parametros) ? " WHERE t." . $parametros : null;
		$sql = "SELECT t.id, t.numero_relatorio, t.data_relatorio, e.sigla_auditoria, e.nome_auditoria
                        FROM " . $schema . ".tb_relatorio t LEFT JOIN  " . $schema . ".tb_especie_auditoria e
                        ON t.especie_auditoria_fk = e.id {$sql_id_relatorio} {$sql_parametros} ORDER BY t.data_relatorio DESC, t.id ASC";

		$command = Yii::app()->db->createCommand($sql);
		$result = $command->query();
		$relatorios = $result->readAll();

		if ($id_relatorio) {
			return $relatorios[0]->nome_auditoria;
		}

		if (is_array($relatorios)) {
			if (sizeof($relatorios)){
				foreach ($relatorios as $vetor) {
                                        if($homologados){
					$vetor_relatorios[] = array("id" => $vetor['id'],
                                                                    "numero_relatorio" => $vetor['numero_relatorio'] . " de " . MyFormatter::converteData($vetor['data_relatorio']));
                                        }else {
					$vetor_relatorios[] = array("id" => $vetor['id'],
                                                                    "sigla_auditoria" => $vetor['id'] . " - " . $vetor['sigla_auditoria']);
                                        }
				}
			}
		}

		$vetor_completo = $vetor_relatorios;

		if (is_array($vetor_completo) != true) {
			$vetor_completo = array(null);
		}
		return ($vetor_completo);
	}

	// Faz a mesma coisa do m�todo relatorio_por_especie, por�m,
	//  selecione somente os n�o homologados para a combo dos cadastros
	// de relat�rio, cap�tulos e itens
	public function relatorio_por_especie_combo($id_relatorio = null, $parametros = null) {
		$schema = Yii::app()->params['schema'];
		$sql_id_relatorio = ($id_relatorio) ? " AND t.id=" . $id_relatorio : null;
		$sql_parametros = ($parametros) ? " AND t." . $parametros : null;
		$sql = "SELECT t.id, e.sigla_auditoria, e.nome_auditoria
                        FROM " . $schema . ".tb_relatorio t LEFT JOIN  " . $schema . ".tb_especie_auditoria e
                        ON t.especie_auditoria_fk = e.id WHERE data_relatorio is null {$sql_id_relatorio} {$sql_parametros} ORDER BY t.id DESC";
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->query();
		$relatorios = $result->readAll();

		if ($id_relatorio) {
			return $relatorios[0]->nome_auditoria;
		}

		if (is_array($relatorios)) {
			if (sizeof($relatorios)){
				foreach ($relatorios as $vetor) {
					$vetor_relatorios[] = array("id" => $vetor['id'],
	                    "sigla_auditoria" => $vetor['id'] . " - " . $vetor['sigla_auditoria']);
				}
			}
		}
		$vetor_completo = $vetor_relatorios;

		if (is_array($vetor_completo) != true) {
			$vetor_completo = array(null);
		}
		return ($vetor_completo);
	}

	/* Finaliza��o do relat�rio feita em 2 etapas:
	 * -------------
	 * 1 - Liberar acesso dos auditados a este relat�rio
	 * 2 - Pegar e-mail de todos envolvidos (auditores, auditados, suregs e ger�ncia) e enviar e-mail
	 * -------------
	 * @id (int): id do relat�rio
	 * @manifestacao (int): caso seja 1, ent�o libera para manifesta��o.
	 *                      caso seja 0, ent�o bloqueia manifesta��o e vai direto para homologa��o.
	 *                      Neste m�todo, a vari�vel $manifestacao tamb�m repassa o seu valor
	 *                      para o m�todo que ir� enviar o e-mail, pois o sistema somente envia
	 *                      e-mail sobre a manifesta��o, caso haja o prazo de 5 dias �teis (na
	 *                      finaliza��o sem prazo, o sistema n�o envia e-mail).
	 */

	public function finalizar_relatorio($id, $manifestacao) {
		$schema = Yii::app()->params['schema'];
		// -----------------------------------------------------
		// passo 1 - liberar acesso dos auditados ao relat�rio
		// -----------------------------------------------------
		// Pega siaudi_cliente das Unidades Regionais do relat�rio
                
		$relatorioUnidadeAdm = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $id));
                //PORTAL_SPB identificar quem � o respons�vel pela unidade: � aquele que tiver perfil siaudi_cliente (151)
		if (sizeof($relatorioUnidadeAdm)){
			foreach ($relatorioUnidadeAdm as $vetor) {
				$UnidadeAdministrativa = UnidadeAdministrativa::model()->findbyAttributes(array('id' => $vetor->unidade_administrativa_fk));
				// Pesquisa quem � o respons�vel pela unidade auditada
					$sql_cliente = "SELECT vusuario.nome_login, vusuario.substituto_fk, vusuario.email
	                                    FROM " . $schema . ".tb_usuario vusuario 
	                                    WHERE (vusuario.unidade_administrativa_fk = '" . $vetor->unidade_administrativa_fk . "')
	                                    AND (vusuario.perfil_fk = (SELECT id FROM " . $schema . ".tb_perfil WHERE nome_interno = 'SIAUDI_CLIENTE'))";
                                       
					$command = Yii::app()->db->createCommand($sql_cliente);
					$result = $command->query();
					$resultado = $result->readAll();
                                        //PORTAL_SPB alterada a forma de gerar e-mail
					if (sizeof($resultado)){
						foreach ($resultado as $vetor_cliente) {
							$login = $vetor_cliente[nome_login];
                                                        $email = $vetor_cliente[email];
							$relatorio_acesso1 = RelatorioAcesso::model()->inserir($id, $login, $vetor->unidade_administrativa_fk);
							$vetor_email[] = $email;
							// verifica se o usu�rio est� cadastrado no corporativo
							//$relatorio_acesso2 = RelatorioAcesso::model()->VerificaAuditadoCorporativo($login);
							//busca substituto
							if ($vetor_cliente[substituto_fk]) {
								$login_substituto = Usuario::model()->findByPk($vetor_cliente[substituto_fk]);
								$relatorio_acesso1 = RelatorioAcesso::model()->inserir($id, $login_substituto->nome_login, $vetor->unidade_administrativa_fk);
								$vetor_email[] = $login_substituto->email;
								//$relatorio_acesso2 = RelatorioAcesso::model()->VerificaAuditadoCorporativo($login_substituto->nome_login);
							}
						}
					}
	
			}
		}

		// ---------------------------------------------
		// passo 2 - Pegar e-mail dos envolvidos
		// ---------------------------------------------
		// pega os auditores do relat�rio
		$relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $id));
		if (sizeof($relatorio_auditor)){
			foreach ($relatorio_auditor as $vetor) {
				$auditor = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
				$vetor_email[] = $auditor->email;
			}
		}

		// pega os gerentes do relat�rio
		$relatorio_gerente = RelatorioGerente::model()->findAllByAttributes(array('relatorio_fk' => $id));
		if (sizeof($relatorio_gerente)){
			foreach ($relatorio_gerente as $vetor) {
				$gerente = usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
				$vetor_email[] = $gerente->email;
			}
		}
                

		// Se manifesta��o for autorizada, ent�o pega e-mails das
		//  unidades administrativas (auditados) do relat�rio
//		$relatorioUnidadeAdm = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $id));
//		if ($manifestacao == 1) {
//			if (sizeof($relatorioUnidadeAdm)){
//				foreach ($relatorioUnidadeAdm as $vetor) {
//					$unidade_administrativa = UnidadeAdministrativa::model()->findByAttributes(array('id' => $vetor->unidade_administrativa_fk));
//					// limpa caracterre �
//					$unidade_sigla = str_replace("�", "", $unidade_administrativa->sigla);
//					// considere somente valores at� a barra. Exemplo: CPL / AM => CPL
//					$barra = strpos($unidade_sigla, "/");
//					if ($barra) {
//						$unidade_sigla = substr($unidade_sigla, 0, $barra);
//					}
//					$unidade_sigla = trim($unidade_sigla);
//                			$vetor_email[] = strtolower($unidade_sigla); // no final, vai ficar sigla"@dominio";
//				}
//			}
//		}
		$this->finalizar_relatorio_email($id, $vetor_email, $manifestacao);
}

/* Envia e-mails, ap�s finaliza��o do relat�rio.
 * @id_relatorio (int): ID do relat�rio
 * @e-mails (array): logins para enviar os e-mails
 * @manifestacao (int): Se 1 ent�o envia e-mail informando que pode se manifestar.
 *                      Se 0, ent�o n�o envia e-mails.
 */

public function finalizar_relatorio_email($id_relatorio, $emails, $manifestacao=null) {
	// pega o t�tulo do relat�rio
	$model_relatorio = Relatorio::model()->findByAttributes(array('id' => $id_relatorio));
	$especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id' => $model_relatorio->especie_auditoria_fk));
	$titulo_relatorio = $id_relatorio . " - " . $especie_auditoria->sigla_auditoria;
	// configura par�metros para enviar o e-mail
	$headers = "Reply-To: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "Return-Path: SIAUDI<".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "From: Auditoria <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
	$assunto = 'SIAUDI - Relat�rio de Auditoria Finalizado';

	// formata texto html
        //PORTAL_SPB
	$mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
	$mensagem .= "Consoante ao Manual de Auditoria Interna, disponibilizamos a vers�o preliminar do <strong>Relat�rio de Auditoria Id Relat�rio: " . $titulo_relatorio . "</strong>, para aprecia��o do respons�vel pela unidade auditada, <strong>no prazo improrrog�vel de 05(cinco) dias �teis</strong>.<br><br>";
	$mensagem .= "Este procedimento ajuda a assegurar de que n�o se verificam mal entendidos ou  incompreens�es acerca dos fatos relatados.<br><br>";
	$mensagem .= "Ap�s a homologa��o do Relat�rio, abrir-se-� prazo de 20 (vinte) dias �teis para que se promovam as devidas respostas �s eventuais recomenda��es de auditoria.<br><br>";
	$mensagem .= "Acesse o Relat�rio de Auditoria via SIAUDI.";
	$mensagem .= "</font></body></html>";

	// Somente envia e-mails �s partes envolvidas,
	// caso haja prazo para manifesta��o. Havendo finaliza��o
	// sem prazo, o sistema n�o envia e-mails.
        //PORTAL_SPB alterada a forma de gerar e-mail
	if ($manifestacao) {
		// envia e-mails
		if (sizeof($emails)){
			foreach ($emails as $vetor) {
				// verifica se o e-mail para este relat�rio j� foi enviado
				// para evitar mais de 1 envio do mesmo relat�rio para o mesmo
				// destinar�rio
				if (!$check_email[$vetor]) {
					$check_email[$vetor] = 1;
					$destinatario = $vetor;// . Yii::app()->params['dominioEmail'];
					$ok = $this->Envia_email($destinatario, $assunto, $mensagem, $headers);
				}
			}
		}
	}
}

/* Pr�-Finaliza��o do relat�rio:
 * -------------
 * 1 - Pegar e-mail do gerente envolvido e enviar e-mail
 * -------------
 * @id (int): id do relat�rio
 */

public function pre_finalizar_relatorio($id) {
	// pega os gerentes do relat�rio
	$relatorio_gerente = RelatorioGerente::model()->findAllByAttributes(array('relatorio_fk' => $id));
        //PORTAL_SPB alterada a forma de gerar e-mail
	if (sizeof($relatorio_gerente)){
		foreach ($relatorio_gerente as $vetor) {
			$gerente = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
			$vetor_email[] = $gerente->email;
		}
	}
	// pega o e-mail do chefe de auditoria
        //PORTAL_SPB obter o usu�rio que � chefe de auditoria
	$chefe_auditoria = Usuario::model()->findAllByAttributes(array(), $condition = "perfil_fk = :perfil_fk", $param = array(':perfil_fk' => 150));

	//adicionando o chefe de auditoria na lista de e-mails
	//$vetor_email[] = $chefe_auditoria[0]['nome_login'];

	//retirando os e-mails duplicados, caso exista
	$vetor_email = array_unique($vetor_email);

	$this->pre_finalizar_relatorio_email($id, $vetor_email);
}

/* Envia e-mails, ap�s pr�-finaliza��o do relat�rio gerado pelo n�cleo.
 * @id_relatorio (int): ID do relat�rio
 * @e-mails (array): logins para enviar os e-mails
 */

public function pre_finalizar_relatorio_email($id_relatorio, $emails) {

	// pega o t�tulo do relat�rio
	$model_relatorio = Relatorio::model()->findByAttributes(array('id' => $id_relatorio));
	$especie_auditoria = EspecieAuditoria::model()->findByAttributes(array('id' => $model_relatorio->especie_auditoria_fk));
	$titulo_relatorio = $id_relatorio . " - " . $especie_auditoria->sigla_auditoria;
	// configura par�metros para enviar o e-mail
	$headers = "Reply-To: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "Return-Path: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "From: Unidade de Auditoria Interna <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
	$assunto = 'SIAUDI - Relat�rio de Auditoria Pr�-finalizado';

	// formata texto html
	$mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
	$mensagem .= "O Relat�rio de Auditoria Id Relat�rio: <strong>" . $titulo_relatorio . "</strong>, foi pr�-finalizado pelo n�cleo de auditoria.<br><br>";
	$mensagem .= "Acesse o Relat�rio de Auditoria via SIAUDI.";
	$mensagem .= "</font></body></html>";

	// envia e-mails
        //PORTAL_SPB alterada a forma de gerar e-mail
	if (sizeof($emails)){
		foreach ($emails as $vetor) {
			// verifica se o e-mail para este relat�rio j� foi enviado
			// para evitar mais de 1 envio do mesmo relat�rio para o mesmo
			// destinar�rio
			if (!$check_email[$vetor]) {
				$check_email[$vetor] = 1;
				$destinatario = $vetor;// . Yii::app()->params['dominioEmail'];
				$ok = $this->Envia_email($destinatario, $assunto, $mensagem, $headers);
			}
		}
	}
}

public function search() {
	$schema = Yii::app()->params['schema'];
	$criteria = new CDbCriteria;
	$criteria->alias = 'relatorio';
	$criteria->addCondition('data_relatorio IS NULL');

	if ($this->diretoria_fk || $this->unidade_administrativa_fk) {
		$criteria->join = "LEFT JOIN {$schema}.tb_relatorio_diretoria RD ON RD.relatorio_fk=relatorio.id
                                 LEFT JOIN {$schema}.tb_relatorio_sureg RS ON RS.relatorio_fk=relatorio.id";
		if ($this->diretoria_fk) {
			$criteria->addcondition('RD.diretoria_fk=' . $this->diretoria_fk);
		}
		if ($this->unidade_administrativa_fk) {
			$criteria->addcondition('RS.unidade_administrativa_fk=' . $this->unidade_administrativa_fk);
		}
	}
	$criteria->compare('id', $this->id);
	$criteria->compare('numero_relatorio', $this->numero_relatorio);
	$criteria->compare('especie_auditoria_fk', $this->especie_auditoria_fk);
	$criteria->compare('descricao_introducao', $this->descricao_introducao, true);
	$criteria->compare('categoria_fk', $this->categoria_fk);
	$criteria->distinct = true;
	return new CActiveDataProvider($this, array(
            'criteria' => $criteria)
	);
}

// Verifica se todos os relat�rios finalizados e n�o liberados para homologa��o
// ainda est�o no prazo para manifesta��o
// (encerra somente os relat�rios onde N�O HOUVE,
//  manifesta��o contr�ria)
public function FechaPrazoRelatorioFinalizado() {
	$Relatorios_finalizados = Relatorio::model()->findAll('data_relatorio IS NULL and st_libera_homologa IS NULL and data_finalizado IS NOT NULL');
	if (sizeof($Relatorios_finalizados)){
		foreach ($Relatorios_finalizados as $vetor) {
			// verifica se o prazo para manifesta��o j� expirou
			$data_final = Feriado::model()->DiasUteis($vetor->data_finalizado, 5);
			$data_final = explode("/", $data_final);
			$data_final = $data_final[2] . $data_final[1] . $data_final[0];
			$hoje = date("Ymd");
	
			// verifica se houve manifesta��o contr�ria
			$Manifestacao = Manifestacao::model()->findAllByAttributes(array('relatorio_fk' => $vetor->id, 'status_manifestacao' => 0));
	
			// se n�o houve manifesta��o contr�ria e prazo expirou, ent�o
			// altera st_libera_homolga da tb_relatorio
			if (sizeof($Manifestacao) == 0 && $hoje > $data_final) {
				$libera = $this->LiberaHomologa($vetor->id);
				$enviar_email = Manifestacao::model()->VerificaManifestacao($vetor->id, 'tacita');
			}
		}
	}
}

// Verifica se o auditor possui mais de 10 dias ut�is sem
// se manisfestar ap�s a reposta do auditado.
public function VerificaManifestacaoAuditor() {
	$schema = Yii::app()->params['schema'];
	$mensagem = "";
	$vetor_email = array();
	$sql = "select relatorio.id as relatorio_fk, relatorio.numero_relatorio, relatorio.data_relatorio, resposta.data_resposta,
                       resposta.descricao_resposta, recomendacao.unidade_administrativa_fk, item.numero_item
                  from " . $schema . ".tb_relatorio as relatorio
                 inner join " . $schema . ".tb_capitulo as capitulo on 
                       capitulo.relatorio_fk = relatorio.id 
                 inner join " . $schema . ".tb_item as item on 
                       item.capitulo_fk = capitulo.id
                 inner join " . $schema . ".tb_recomendacao as recomendacao on 
                       recomendacao.item_fk = item.id 
                 inner join " . $schema . ".tb_resposta as resposta on 
                       resposta.recomendacao_fk = recomendacao.id
                 inner join " . $schema . ".tb_unidade_administrativa as sureg on 
                       sureg.id = recomendacao.unidade_administrativa_fk
                 where relatorio.numero_relatorio is not null 
                   and relatorio.data_relatorio is not null 
                   and recomendacao.recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomenda��o%')
                   and resposta.id = (select max(id) 
                                        from " . $schema . ".tb_resposta as aux 
                                        where aux.recomendacao_fk = resposta.recomendacao_fk)
                   and not exists (select 1
                                     from " . $schema . ".tb_usuario as auditor 
                                    where auditor.nome_login = resposta.id_usuario_log)";
	$command = Yii::app()->db->createCommand($sql);
	$dados = $command->query();
	$relatorios = $dados->readAll();
	if (sizeof($relatorios)) {
		// configura par�metros para enviar o e-mail
		$headers = "Reply-To: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "Return-Path: SIAUDI<".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "From: Unidade de Auditoria Interna <".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
		$assunto = 'SIAUDI - Relat�rio sem Manifesta��o - Auditor';
		
		if (sizeof($relatorios)){
			foreach ($relatorios as $vetor) {
				$data_inicio = date("d/m/Y", strtotime(is_null($vetor['data_resposta']) ? $vetor['data_relatorio'] : $vetor['data_resposta']));
				//primeira verifica��o: verifica se passou 10 dias �teis
				$data_final = Feriado::model()->DiasUteis($data_inicio, 10);
				$data_final = explode("/", $data_final);
				$data_final = $data_final[2] . $data_final[1] . $data_final[0];
				$hoje = date("Ymd");
				if ($data_final == $hoje) {
					$mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
					$mensagem .= "Alertamos que o auditado se manifestou, h� dez dia �teis, sobre a recomenda��o exarada no item ".$vetor['numero_item']." do Relat�rio n.� ". $vetor['numero_relatorio'].".
	                                  Solicitamos a imediata avalia��o do citado item";
					$mensagem .= "</font></body></html>";
				}
                                //PORTAL_SPB alterada a forma de gerar e-mail
				if ($mensagem) {
					$dados_auditor = RelatorioAuditor::model()->findAll("relatorio_fk =" . $vetor['relatorio_fk']);
					if (sizeof($dados_auditor)){
						foreach($dados_auditor as $vetor_auditor){
							$auditor = Usuario::model()->findByPk($vetor_auditor['usuario_fk']);
							$vetor_email[] = $auditor->email;
						}
					}
	
					if (sizeof($vetor_email)) {
						foreach ($vetor_email as $destinatario) {
							//$destinatario .= Yii::app()->params['dominioEmail'];
							$ok = $this->Envia_email($destinatario, $assunto, $mensagem, $headers);
						}
					}
				}
			}
		}
	}
}

// Verifica se o auditado possui mais de 20 dias ut�is ou 60 dias corridos sem
// se manisfestar ap�s a homologa��o do relat�rio.
public function VerificaManifestacaoAuditado() {
	$schema = Yii::app()->params['schema'];
	$mensagem = "";
	$sql = "select relatorio.id as relatorio_fk, relatorio.numero_relatorio, relatorio.data_relatorio, recomendacao.unidade_administrativa_fk,
                       resposta.data_resposta, resposta.descricao_resposta, item.numero_item, recomendacao.numero_recomendacao,
                       (select aux.data_resposta
                          from " . $schema . ".tb_resposta as aux
                         inner join " . $schema . ".tb_usuario as auditor on
                               auditor.nome_login = aux.id_usuario_log
                         where aux.tipo_status_fk in (SELECT id FROM " . $schema . ".tb_tipo_status WHERE descricao_status IN ('Pendente', 'Em Implementa��o'))
                           and aux.recomendacao_fk = resposta.recomendacao_fk
                         order by resposta.id desc
                         LIMIT 1) as data_resposta_auditor         
                    from " . $schema . ".tb_relatorio as relatorio
                   inner join " . $schema . ".tb_capitulo as capitulo on 
                         capitulo.relatorio_fk = relatorio.id 
                   inner join " . $schema . ".tb_item as item on 
                         item.capitulo_fk = capitulo.id
                   inner join " . $schema . ".tb_recomendacao as recomendacao on 
                         recomendacao.item_fk = item.id 
                   left join " . $schema . ".tb_resposta as resposta on 
                         resposta.recomendacao_fk = recomendacao.id
                   inner join " . $schema . ".tb_unidade_administrativa as sureg on 
                         sureg.id = recomendacao.unidade_administrativa_fk
                   where relatorio.numero_relatorio is not null 
                     and relatorio.data_relatorio is not null 
                     and recomendacao.recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomenda��o%')
                     and (resposta.id = (select max(id) 
                                          from " . $schema . ".tb_resposta as aux 
                                         where aux.recomendacao_fk = resposta.recomendacao_fk)
                          OR 
                          (select max(id) 
                             from " . $schema . ".tb_resposta as aux 
                            where aux.recomendacao_fk = resposta.recomendacao_fk) is null
                          )
                     and not exists (select 1
                                       from " . $schema . ".tb_usuario as auditor 
                                      where auditor.nome_login = resposta.id_usuario_log)";

	$command = Yii::app()->db->createCommand($sql);
	$dados = $command->query();
	$relatorios = $dados->readAll();

	if (sizeof($relatorios)) {

		// configura par�metros para enviar o e-mail
		$headers = "Reply-To: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "Return-Path: SIAUDI<".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "From: Unidade de Auditoria Interna <".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
		$assunto = 'SIAUDI - Relat�rio sem Manifesta��o - Auditado';

		//sempre envia uma c�pia para a ger�ncia de auditoria
                //PORTAL_SPB e-mail para um grupo
                if(Yii::app()->params['emailGrupoAuditoria'] != '')
                    $vetor_email[]=Yii::app()->params['emailGrupoAuditoria'];
		if (sizeof($relatorios)){
			foreach ($relatorios as $vetor) {
				$data_inicio = date("d/m/Y", strtotime(is_null($vetor['data_resposta']) ? $vetor['data_relatorio'] : $vetor['data_resposta']));
				//primeira verifica��o: verifica se passou 20 dias �teis
				$data_final = Feriado::model()->DiasUteis($data_inicio, 20);
				$data_final = explode("/", $data_final);
				$data_final = $data_final[2] . $data_final[1] . $data_final[0];
				$hoje = date("Ymd");
	
				if ($hoje == $data_final) {
					$mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
					$mensagem .= "Senhor(a) Gestor(a), informamos que foi ultrapassado, nesta data, o prazo de 20 dias �teis para que Vossa Senhoria informasse �
	                                  Unidade de Auditoria Interna sobre as provid�ncias corretivas adotadas � vista das recomenda��es constantes do 
	                                  Relat�rio de Auditoria n� " . $vetor[numero_relatorio] . ". Caso haja interesse de Vossa Senhoria, formalize pedido de 
	                                 prorroga��o de prazo (at� 20 dias �teis no m�ximo), devidamente justificado.";
					$mensagem .= "</font></body></html>";
				}
	
				//segunda verifica��o: verifica se passou 60 dias corridos
				$data = explode("/", $data_inicio);
				$dia = $data[0];
				$mes = $data[1];
				$ano = $data[2];
				$data_final = date('Y-m-d', mktime(0, 0, 0, $mes, $dia + 60, $ano));
				$data_final = explode("-", $data_final);
				$data_final = $data_final[0] . $data_final[1] . $data_final[2];
				$hoje = date("Ymd");
	
				if ($hoje == $data_final) {
					$mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
					$mensagem .= "Senhor(a) Gestor(a), tendo sido ultrapassado, nesta data, o prazo m�ximo para que Vossa Senhoria informasse �
	                                  Unidade de Auditoria Interna sobre as provid�ncias corretivas adotadas � vista das recomenda��es constantes do 
	                                  Relat�rio de Auditoria n� " . $vetor[numero_relatorio] . ", na esteira do art. 49 da Lei n� 9.784, de 29 de janeiro de 1999, considerar-se-� que essa 
	                                  Unidade Organizacional auditada, na pessoa de seu(sua) Titular, est� assumindo o risco de n�o corrigir a condi��o relatada pelos(as) 
	                                  profissionais auditores(as) internos(as), podendo incorrer oportunamente em responsabiliza��o nas esferas administrativa, 
	                                  civil e penal em face de poss�vel imputa��o de omiss�o e/ou de in�rcia administrativas por parte de inst�ncias fiscalizadoras e 
	                                  regulat�rias competentes, na via de consequ�ncia (ref. Pr�tica Recomendada/IPPF-IIA n� 2060-1).";
					$mensagem .= "</font></body></html>";
				}
                                
				if ($mensagem) {
					// Pega siaudi_clientes das Unidades Regionais do relat�rio
                                        //PORTAL_SPB identificar quem � o respons�vel pela unidade: � aquele que tiver perfil siaudi_cliente (151)
					$relatorioUnidadeAdm = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $vetor['relatorio_fk']));
					if (sizeof($relatorioUnidadeAdm)){
						foreach ($relatorioUnidadeAdm as $ua) {
							// Pesquisa quem � siaudi_cliente da regional
							$sql_cliente = "SELECT vusuario.nome_login, vusuario.substituto_fk, vusuario.email
		                                    FROM " . $schema . ".tb_usuario vusuario 
		                                    WHERE (vusuario.unidade_administrativa_fk = '" . $ua->unidade_administrativa_fk . "')
		                                    AND (vusuario.perfil_fk = 151)";
                                                        //PORTAL_SPB alterada a forma de gerar e-mail
							$command = Yii::app()->db->createCommand($sql_cliente);
							$result = $command->query();
							$resultado = $result->readAll();
							if (sizeof($resultado)){
								foreach ($resultado as $vetor_cliente) {
									$email = $vetor_cliente[email];
									$vetor_email[] = $email;
								}
							}
						}
					}
					
					if (sizeof($vetor_email) > 1) {
						foreach ($vetor_email as $destinatario) {
							//$destinatario .= Yii::app()->params['dominioEmail'];
							$ok = $this->Envia_email($destinatario, $assunto, $mensagem, $headers);
						}
					}
				}
			}
		}
	}
}

// Altera campo st_libera_homologa da tb_relatorio
// para relat�rios que j� expiraram o prazo de manifesta��o
public function LiberaHomologa($id_relatorio) {
	$schema = Yii::app()->params['schema'];
	$sql = 'UPDATE ' . $schema . '.tb_relatorio set st_libera_homologa=1 where id=' . $id_relatorio;
	$command = Yii::app()->db->createCommand($sql);
	$command->execute();
}

// Consulta auditor chefe que finalizou ou homologou
// o relat�rio. Par�metros de entrada:
// 1 => id do relat�rio
// 2 => pre_finaliza / finaliza / homologa
public function RelatorioAuditorChefe($id_relatorio, $acao) {
	$schema = Yii::app()->params['schema'];
	$sql = "  SELECT nome_usuario, nome_login, nome_cargo
                    FROM " . $schema . ".tb_usuario usuario 
                         INNER JOIN  " . $schema . ".tb_relatorio relatorio 
                               ON relatorio.login_" . $acao . "= usuario.nome_login 
                         LEFT OUTER JOIN
                               " . $schema . ".tb_cargo gerencia 
                               ON usuario.cargo_fk = gerencia.id
                    WHERE (relatorio.id=" . $id_relatorio . ") ";

	$command = Yii::app()->db->createCommand($sql);
	$result = $command->query();
	return ($result->read());
}

// Perfil Gerente, consulta todos os relat�rios
// de acordo com o par�metro de entrada
// tipo de relat�rio :: (1=> N�o homo   logado, 2=> Homologado)
// ano => ex: 2012, mas pode ser nulo
public function RelatorioSaidaGerente($tipo_relatorio, $unidade_administrativa_fk, $ano = null) {
	$perfil = strtolower(Yii::app()->user->role);
	$perfil = str_replace("siaudi2", "siaudi", $perfil);
	$schema = Yii::app()->params['schema'];
	//        $tipo_relatorio = ($tipo_relatorio == 1) ? "IS NULL" : "IS NOT NULL";
	$sql_ano = ($ano) ? " AND  date_part('year', data_relatorio)=" . $ano : "";

	// consulta padr�o para perfil siaudi_auditor (mostra
	// todos os relat�rios de auditoria) -> verificar se continuar� assim
	$sql = "SELECT relatorio.id, relatorio.numero_relatorio, relatorio.data_relatorio, e.sigla_auditoria
        FROM " . $schema . ".tb_relatorio relatorio 
            LEFT JOIN  " . $schema . ".tb_especie_auditoria e ON relatorio.especie_auditoria_fk = e.id 
            INNER JOIN  " . $schema . ".tb_relatorio_sureg rs ON relatorio.id = rs.relatorio_fk ";

	switch ($tipo_relatorio) {
		//n�o homologados
		case 1:
			$sql .= "WHERE data_relatorio IS NULL
                           AND numero_relatorio IS NULL";
			break;
			//homologados
		case 2:
			$sql .= "WHERE data_relatorio IS NOT NULL
                           AND numero_relatorio IS NOT NULL";
			break;
			//pr�-finalizados
		case 3:
			$sql .= "WHERE data_pre_finalizado IS NOT NULL
                           AND login_pre_finaliza IS NOT NULL
                           AND numero_relatorio IS NULL
                           AND data_relatorio IS NULL
                           AND data_finalizado IS NULL 
                           AND login_finaliza IS NULL";
			break;
			//finalizados
		case 4:
			$sql .= "WHERE login_finaliza IS NOT NULL
                           AND data_finalizado IS NOT NULL 
                           AND numero_relatorio IS NULL
                           AND data_relatorio IS NULL";
			break;
	}

	$sql .= $sql_ano;

	if ($unidade_administrativa_fk != "") {
		$sql .= " and rs.unidade_administrativa_fk = " . $unidade_administrativa_fk;
	}

	if ($perfil == "siaudi_gerente_nucleo") {
		$sql .= " and nucleo is true";
	}
	
	$sql .= "        GROUP BY relatorio.id, relatorio.numero_relatorio, relatorio.data_relatorio, e.sigla_auditoria";
	$sql .= "        ORDER BY relatorio.data_relatorio DESC";
	
	$command = Yii::app()->db->createCommand($sql);
	$result = $command->query();
	return($result->readAll());
}

// Monta combo apenas com relat�rios homologados
public function ComboRelatorioHomologado() {

	/*
	 * Deve ser mostrado para o perfil cliente somente os relat�rios
	 * Homologados em que o cliente esteja envolvido como auditado.
	 */
	$perfil = strtolower(Yii::app()->user->role);
	$perfil = str_replace("siaudi2", "siaudi", $perfil);
	if (strtolower($perfil) == 'siaudi_cliente') {
		$login = Yii::app()->user->login;
		$dados_relatorio_acesso = RelatorioAcesso::model()->findAll("nome_login='" . $login . "'");

		if (sizeof($dados_relatorio_acesso)) {
			$clausula_in = '';
			foreach ($dados_relatorio_acesso as $vetor) {

				$clausula_in .= $vetor['relatorio_fk'] . ',';
			}
			//retira a �ltima v�rgula
			$clausula_in = substr($clausula_in, 0, -1);

			$relatorios = Relatorio::model()->findAll('data_relatorio IS NOT NULL and id in (' . $clausula_in . ') ORDER BY data_relatorio DESC');
		}
	} else {
		$relatorios = Relatorio::model()->findAll('data_relatorio IS NOT NULL ORDER BY data_relatorio DESC');
	}
	if (sizeof($relatorios) > 0) {
		foreach ($relatorios as $vetor) {
			$vetor_saida[$vetor->id] = $vetor->numero_relatorio . " de " . $vetor->data_relatorio;
		}
	} else {
		$vetor_saida[0] = "Sem relat�rios";
	}

	return($vetor_saida);
}

// Monta combo apenas com relat�rios finalizados e n�o homologados
public function ComboRelatorioFinalizado() {
	$schema = Yii::app()->params['schema'];
	$sql = "SELECT relatorio.id,  e.sigla_auditoria
        FROM " . $schema . ".tb_relatorio relatorio 
            LEFT JOIN  " . $schema . ".tb_especie_auditoria e ON relatorio.especie_auditoria_fk = e.id 
        WHERE (relatorio.data_relatorio IS NULL and relatorio.st_libera_homologa=1)
        ORDER BY relatorio.id DESC";

	$command = Yii::app()->db->createCommand($sql);
	$result = $command->query();
	$resultado = $result->readAll();

	if (sizeof($resultado) > 0) {
		foreach ($resultado as $vetor) {
			$vetor_saida[$vetor[id]] = $vetor[id] . " - " . $vetor[sigla_auditoria];
		}
	} else {
		$vetor_saida[0] = "Sem relat�rios para homologar";
	}
	return($vetor_saida);
}

// Prorroga relat�rio homologado - acrescenta os dias
// �teis e envia e-mail avisando �s partes interessadas
// Par�metros de entrada: id do relat�rio homologado,
// e n�mero de dias �teis a prorrogar.
public function ProrrogarRelatorioHomologado($id, $dias_uteis) {
	$schema = Yii::app()->params['schema'];
	$relatorio = Relatorio::model()->findByPk($id);


	// ---------------------------------------------
	// passo 1 - Altera o prazo no banco
	// ---------------------------------------------
	$prazo_atual = $relatorio[valor_prazo];
	$prazo_novo = $prazo_atual + $dias_uteis;
	$sql = "UPDATE " . $schema . ".tb_relatorio
                    SET valor_prazo=" . $prazo_novo . "
                    WHERE id=" . $id;
	$command = Yii::app()->db->createCommand($sql);
	$result = $command->query();

	// ---------------------------------------------
	// passo 2 - Pegar e-mail dos envolvidos
	// ---------------------------------------------
	// pega os auditores do relat�rio
        //PORTAL_SPB alterada a forma de gerar e-mail
	$relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $id));
	if (sizeof($relatorio_auditor)){
		foreach ($relatorio_auditor as $vetor) {
			$auditor = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
			$vetor_email[] = $auditor->email;
		}
	}

	// pega os gerentes do relat�rio
	$relatorio_gerente = RelatorioGerente::model()->findAllByAttributes(array('relatorio_fk' => $id));
	if (sizeof($relatorio_gerente)){
		foreach ($relatorio_gerente as $vetor) {
			$gerente = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
			$vetor_email[] = $gerente->email;
		}
	}

	// Pega e-mails das unidades administrativas (auditados) do relat�rio
//	$relatorioUnidadeAdm = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $id));
//	if (sizeof($relatorioUnidadeAdm)){
//		foreach ($relatorioUnidadeAdm as $vetor) {
//			$unidade_administrativa = UnidadeAdministrativa::model()->findByAttributes(array('id' => $vetor->unidade_administrativa_fk));
//			// limpa caracterre �
//			$unidade_sigla = str_replace("�", "", $unidade_administrativa->sigla);
//			// considere somente valores at� a barra. Exemplo: CPL / AM => CPL
//			$barra = strpos($unidade_sigla, "/");
//			if ($barra) {
//				$unidade_sigla = substr($unidade_sigla, 0, $barra);
//			}
//			$unidade_sigla = trim($unidade_sigla);
//			$vetor_email[] = strtolower($unidade_sigla); // no final, vai ficar sigla"@dominio";
//		}
//	}

	// ---------------------------------------------
	// passo 3 - Envia e-mail dos envolvidos
	// ---------------------------------------------
	// configura par�metros para enviar o e-mail
	$headers = "Reply-To: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "Return-Path: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "From: Unidade de Auditoria Interna <".Yii::app()->params['adminEmail'].">\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
	$assunto = 'SIAUDI - Prazo do Relat�rio Prorrogado';

	// formata texto html
	$nova_data = Feriado::model()->DiasUteis($relatorio[data_relatorio], $prazo_novo);
	$mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
	$mensagem.= "Conforme solicita��o, o prazo para resposta do <strong>Relat�rio " .
                "de Auditoria N� " . $relatorio[numero_relatorio] .
                " de " . $relatorio[data_relatorio] . "</strong> foi " .
                "prorrogado para a data <strong>" . $nova_data . "</strong>.";
	$mensagem .= "</font></body></html>";

	// envia e-mails
        //PORTAL_SPB alterada a forma de gerar e-mail
	if (sizeof($vetor_email)){
		foreach ($vetor_email as $vetor) {
			// verifica se o e-mail para este relat�rio j� foi enviado
			// para evitar mais de 1 envio do mesmo relat�rio para o mesmo
			// destinar�rio
			if (!$check_email[$vetor]) {
				$check_email[$vetor] = 1;
				$destinatario = $vetor;// . Yii::app()->params['dominioEmail'];
				$ok = $this->Envia_email($destinatario, $assunto, $mensagem, $headers);
			}
		}
	}
}

// Homologa o relat�rio
// Envia e-mail avisando �s partes interessadas
// Par�metros de entrada: id do relat�rio a homologar
public function HomologarRelatorio($id) {
	$schema = Yii::app()->params['schema'];
	$relatorio = Relatorio::model()->findByPk($id);
	// s� executa se relat�rio ainda n�o foi homologado
	if (!$relatorio->data_relatorio) {
		$login = Yii::app()->user->login;
		// -----------------------------------------------
		// passo 1 - Identificar o n�mero do relat�rio
		// (sequencia � feita conforme a categoria - orgin�ria
		//  ou extraordinaria) 
                //  e insere as informa��es no banco
		// -----------------------------------------------
		$categoria = $relatorio->categoria_fk;
		$sql = "SELECT numero_relatorio
                   FROM " . $schema . ".tb_relatorio 
                   WHERE date_part('year', data_relatorio)=" . date("Y") . "AND
                         categoria_fk =" . $categoria . " and 
                         numero_relatorio IS NOT NULL
                   order by numero_relatorio DESC";
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->query();
		$resultado = $result->read();
		$numero_relatorio = $resultado[numero_relatorio];
		$numero_relatorio = ($numero_relatorio) ? $numero_relatorio : 0;

		// insere dados do relat�rio no banco
		$sql = "UPDATE " . $schema . ".tb_relatorio
                    SET login_homologa='" . $login . "',
                        valor_prazo=20,
                        numero_relatorio=" . ($numero_relatorio + 1) . ",
                        data_relatorio='" . date("Y-m-d") . "'       
                    WHERE id=" . $id;
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->query();


		// -----------------------------------------------------
		// passo 2 - Identificar o n�mero do �ltimo item
		// (sequencia � �NICA independente da categoria - ordin�ria
		//  ou extraordinaria) e insere as informa��es no banco
		// -----------------------------------------------------
		$sql = "SELECT numero_item
                   FROM " . $schema . ".tb_item item
                   LEFT JOIN  " . $schema . ".tb_capitulo capitulo ON capitulo.id = item.capitulo_fk                        
                   LEFT JOIN  " . $schema . ".tb_relatorio relatorio ON relatorio.id= capitulo.relatorio_fk 
                   WHERE numero_item IS NOT NULL
                   order by item.id DESC";
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->query();
		$resultado = $result->read();
		$numero_item = $resultado[numero_item];
		$numero_item = ($numero_item) ? $numero_item : 0;

		// verifica se o bot�o "Reiniciar contagem" foi marcado.
		// Em caso afirmativo, n�mero do item deve ser 0.
		$RelatorioReiniciar = RelatorioReiniciar::model()->VerificaReiniciarContagem();
		if (sizeof($RelatorioReiniciar) > 0) {
			$numero_item = 0;
			$RelatorioReiniciar = RelatorioReiniciar::model()->DesabilitarReiniciarContagem();
		}


		// pega todos os cap�tulos para numerar os itens internos
		$capitulo = Capitulo::model()->findAll('relatorio_fk=' . $id . ' order by id ASC');
		if (sizeof($capitulo) > 0) {
			foreach ($capitulo as $vetor_capitulo) {
				// para cada cap�tulo, recupera o item por ordem de id
				$item = Item::model()->findAll('capitulo_fk=' . $vetor_capitulo->id . ' order by id ASC');
				if (sizeof($item) > 0) {
					foreach ($item as $vetor_item) {
						// altera sequencia do item no banco
						$numero_item++;
						$sql = "UPDATE " . $schema . ".tb_item SET numero_item=" . $numero_item . "WHERE id=" . $vetor_item->id;
						$command = Yii::app()->db->createCommand($sql);
						$result = $command->query();

						// busca pelas recomenda��es do item para numerar
						$numero_recomendacao = 0;
						$recomendacao = Recomendacao::model()->findAll('item_fk=' . $vetor_item->id . " order by id ASC");
						if (sizeof($recomendacao) > 0) {
							foreach ($recomendacao as $vetor_recomendacao) {
								$numero_recomendacao++;
								$sql = "UPDATE " . $schema . ".tb_recomendacao SET numero_recomendacao=" . $numero_recomendacao . "WHERE id=" . $vetor_recomendacao->id;
								$command = Yii::app()->db->createCommand($sql);
								$result = $command->query();
							}
						}
					}
				}
			}
		}



		// ---------------------------------------------
		// passo 3 - Pegar e-mail dos envolvidos
		// ---------------------------------------------
		// pega os auditores do relat�rio
                //PORTAL_SPB alterada a forma de gerar e-mail
		$relatorio_auditor = RelatorioAuditor::model()->findAllByAttributes(array('relatorio_fk' => $id));
		if (sizeof($relatorio_auditor)){
			foreach ($relatorio_auditor as $vetor) {
				$auditor = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
				$vetor_email[] = $auditor->email;
			}
		}

		// pega os gerentes do relat�rio
		$relatorio_gerente = RelatorioGerente::model()->findAllByAttributes(array('relatorio_fk' => $id));
		if (sizeof($relatorio_gerente)){
			foreach ($relatorio_gerente as $vetor) {
				$gerente = Usuario::model()->findByAttributes(array('id' => $vetor->usuario_fk));
				$vetor_email[] = $gerente->email;
			}
		}

		// Pega e-mails das unidades administrativas (auditados) do relat�rio
//		$relatorioUnidadeAdm = RelatorioSureg::model()->findAllByAttributes(array('relatorio_fk' => $id));
//		if(sizeof($relatorioUnidadeAdm)){
//			foreach ($relatorioUnidadeAdm as $vetor) {
//				$unidade_administrativa = UnidadeAdministrativa::model()->findByAttributes(array('id' => $vetor->unidade_administrativa_fk));
//				// limpa caracterre �
//				$unidade_sigla = str_replace("�", "", $unidade_administrativa->sigla);
//				// considere somente valores at� a barra. Exemplo: CPL / AM => CPL
//				$barra = strpos($unidade_sigla, "/");
//				if ($barra) {
//					$unidade_sigla = substr($unidade_sigla, 0, $barra);
//				}
//				$unidade_sigla = trim($unidade_sigla);
//				$vetor_email[] = strtolower($unidade_sigla); // no final, vai ficar sigla"@dominio";
//			}
//		}

		// ---------------------------------------------
		// passo 4 - Envia e-mail dos envolvidos
		// ---------------------------------------------
		// configura par�metros para enviar o e-mail
		$headers = "Reply-To: SIAUDI <".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "Return-Path: SIAUDI<".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "From: Unidade de Auditoria Interna <".Yii::app()->params['adminEmail'].">\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html;charset=iso-8859-1\r\n";
		$assunto = 'SIAUDI - Relat�rio Homologado';

		// formata texto html
		$relatorio = Relatorio::model()->findByPk($id);
		$mensagem = "<html><head></head><body><font face='Verdana' size='2'>";
		$mensagem.= "Informamos que o <strong>Relat�rio de Auditoria Id Relat�rio: " . $id . "</strong> foi homologado pela Auditoria Interna e passou a ser identificado como <strong>Relat�rio de Auditoria N� " . $relatorio->numero_relatorio . "/" . date("Y") . "</strong><br><br>";
		$mensagem.= "Cumpre � Unidade Organizacional auditada se manifestar formalmente acerca das recomenda��es e sugest�es da Unidade de Auditoria Interna, <strong>no prazo de 20(vinte) dias �teis</strong>, ap�s a data de recebimento, em aten��o ao que explicita o Manual de Auditoria Interna.<br><br>";
		$mensagem.= "Acesse o Relat�rio de Auditoria via SIAUDI.";
		$mensagem.= "</font></body></html>";

		// envia e-mails
                //PORTAL_SPB alterada a forma de gerar e-mail
		if (sizeof($vetor_email)){
			foreach ($vetor_email as $vetor) {
				// verifica se o e-mail para este relat�rio j� foi enviado
				// para evitar mais de 1 envio do mesmo relat�rio para o mesmo
				// destinar�rio
				if (!$check_email[$vetor]) {
					$check_email[$vetor] = 1;
					$destinatario = $vetor;//. Yii::app()->params['dominioEmail'];
					$ok = $this->Envia_email($destinatario, $assunto, $mensagem, $headers);
				}
			}
		}
	}
}

// m�todo para centralizar o envio de e-mails
// (para facilitar o controle de comentar/descomentar
//  nos ambientes de desenvolvimento e produ��o)
public function Envia_email($destinatario, $assunto, $mensagem, $headers) {
	//$ok = mail($destinatario, $assunto, $mensagem, $headers);
}

// m�todo para buscar os relat�rios pendentes da p�gina inicial
// (index) de acordo com o tipo de pendentes (1=todos, 2=meus clientes -
// envolvidos com o relat�rio de um auditor espec�fico)
public function Relatorio_pendentes_index($tipo_pendentes) {
        $id_und_adm = Yii::app()->user->id_und_adm;
	$login = Yii::app()->user->login;
	$schema = Yii::app()->params['schema'];
	$perfil = Yii::app()->user->role;
	$perfil = str_replace("siaudi2", "siaudi", $perfil);
        

	//ESTA CONSULTA SER� EXECUTADA PARA TODOS OS PERFIS DE DIRETOR
	$sql_rel = "SELECT DISTINCT relatorio.id,
                           relatorio.numero_relatorio, 
                           relatorio.data_relatorio,
                           especie_auditoria.nome_auditoria,
                           relatorio.valor_prazo
                      FROM " . $schema . ".tb_relatorio relatorio 
                     INNER JOIN " . $schema . ".tb_especie_auditoria especie_auditoria ON 
                           relatorio.especie_auditoria_fk = especie_auditoria.id
                     INNER JOIN " . $schema . ".tb_relatorio_diretoria relatorio_diretoria ON 
                           relatorio_diretoria.relatorio_fk = relatorio.id AND
                           relatorio_diretoria.diretoria_fk = $id_und_adm 
                     WHERE (relatorio.numero_relatorio IS NOT NULL) 
                       AND (relatorio.data_regulariza IS NULL) 
                     ORDER BY relatorio.data_relatorio DESC, relatorio.numero_relatorio DESC ";
                    // data antiga -> ORDER BY date_part('year', relatorio.data_relatorio) DESC, relatorio.numero_relatorio DESC ";


	if ($perfil == "siaudi_auditor") {
		if (($tipo_pendentes == "2") || ($tipo_pendentes == "")) {//somente relat�rios do auditor (pend�ncias dos meus clientes)
			$sql_rel_tipo = " FROM  " . $schema . ".tb_relatorio relatorio INNER JOIN
                                                " . $schema . ".tb_relatorio_auditor relatorio_auditor ON relatorio.id=relatorio_auditor.relatorio_fk INNER JOIN
                                                " . $schema . ".tb_usuario usuario ON relatorio_auditor.usuario_fk= usuario.id INNER JOIN
                                                " . $schema . ".tb_especie_auditoria especie_auditoria ON relatorio.especie_auditoria_fk = especie_auditoria.id
                                          WHERE (relatorio.numero_relatorio IS NOT NULL) 
                                                AND (relatorio.data_regulariza IS NULL)
                                                AND (usuario.nome_login= '" . $login . "') ";
		} else { //todas as pend�ncias
			$sql_rel_tipo = " FROM " . $schema . ".tb_relatorio relatorio INNER JOIN
                                               " . $schema . ".tb_especie_auditoria especie_auditoria ON relatorio.especie_auditoria_fk=especie_auditoria.id
                                          WHERE (relatorio.numero_relatorio IS NOT NULL) 
                                                AND (relatorio.data_regulariza IS NULL) ";
		}

		$sql_rel = " SELECT DISTINCT  relatorio.id,
                                    relatorio.numero_relatorio, 
                                    relatorio.data_relatorio, 
                                    especie_auditoria.nome_auditoria,
                                    relatorio.valor_prazo
                                    " . $sql_rel_tipo . "
                                    ORDER BY relatorio.data_relatorio DESC, relatorio.numero_relatorio DESC ";
                                    // data antiga -> ORDER BY date_part('year', relatorio.data_relatorio) DESC, relatorio.numero_relatorio DESC ";
	}

	if ($perfil == "siaudi_gerente" || $perfil == "siaudi_chefe_auditoria" || $perfil == "siaudi_cgu" || $perfil == "siaudi_gabin") {
		$sql_rel = " SELECT DISTINCT  relatorio.id,
                                    relatorio.numero_relatorio, 
                                    relatorio.data_relatorio,
                                    especie_auditoria.nome_auditoria,
                                    relatorio.valor_prazo	
                            FROM " . $schema . ".tb_relatorio relatorio INNER JOIN
                                 " . $schema . ".tb_especie_auditoria especie_auditoria ON relatorio.especie_auditoria_fk = especie_auditoria.id
                            WHERE (relatorio.numero_relatorio IS NOT NULL) 
                                    AND (relatorio.data_regulariza IS NULL)
                                    ORDER BY relatorio.data_relatorio DESC, relatorio.numero_relatorio DESC ";
                                    // data antiga -> ORDER BY date_part('year', relatorio.data_relatorio) DESC, relatorio.numero_relatorio DESC ";
	}

	if ($perfil == "siaudi_gerente_nucleo") {
		$sql_rel = " SELECT DISTINCT  relatorio.id,
                                    relatorio.numero_relatorio, 
                                    relatorio.data_relatorio,
                                    especie_auditoria.nome_auditoria,
                                    relatorio.valor_prazo	
                            FROM " . $schema . ".tb_relatorio relatorio INNER JOIN
                                 " . $schema . ".tb_especie_auditoria especie_auditoria ON relatorio.especie_auditoria_fk = especie_auditoria.id
                            WHERE (relatorio.numero_relatorio IS NOT NULL) 
                                    AND (relatorio.data_regulariza IS NULL)
                                    AND (relatorio.nucleo IS TRUE)
                                    ORDER BY relatorio.data_relatorio DESC, relatorio.numero_relatorio DESC ";
                                    // data antiga -> ORDER BY date_part('year', relatorio.data_relatorio) DESC, relatorio.numero_relatorio DESC ";
	}

	if ($perfil == "siaudi_cliente") { 
		$sql_rel = " SELECT  DISTINCT relatorio.id,
                                    relatorio.numero_relatorio, 
                                    relatorio.data_relatorio,
                                    especie_auditoria.nome_auditoria,
                                    relatorio.valor_prazo
                            FROM    " . $schema . ".tb_relatorio relatorio INNER JOIN
                                    " . $schema . ".tb_especie_auditoria especie_auditoria ON relatorio.especie_auditoria_fk = especie_auditoria.id INNER JOIN
                                    " . $schema . ".tb_relatorio_acesso acesso_relatorio ON acesso_relatorio.relatorio_fk = relatorio.id 
                                    
                            WHERE (relatorio.numero_relatorio IS NOT NULL) 
                                    AND (relatorio.data_regulariza IS NULL) 
                                    AND (acesso_relatorio.nome_login = '" . $login . "')
                                    ORDER BY relatorio.data_relatorio DESC, relatorio.numero_relatorio DESC ";
                                    // data antiga -> ORDER BY date_part('year', relatorio.data_relatorio) DESC, relatorio.numero_relatorio DESC ";
	}


	if ($perfil == "siaudi_cliente_item") {
		$sql_rel = " SELECT DISTINCT relatorio.id,
                                  relatorio.numero_relatorio, 
                                  relatorio.data_relatorio,
                                  especie_auditoria.nome_auditoria,
                                  relatorio.valor_prazo
                           FROM " . $schema . ".tb_relatorio relatorio INNER JOIN
                                " . $schema . ".tb_capitulo capitulo ON relatorio.id=capitulo.relatorio_fk INNER JOIN
                                " . $schema . ".tb_item item ON capitulo.id= item.capitulo_fk INNER JOIN
                                " . $schema . ".tb_relatorio_acesso_item acesso_item ON item.id = acesso_item.item_fk INNER JOIN 
                                " . $schema . ".tb_especie_auditoria especie_auditoria ON relatorio.especie_auditoria_fk = especie_auditoria.id
                           WHERE (relatorio.numero_relatorio IS NOT NULL) 
                                AND (relatorio.data_regulariza IS NULL) 
                                AND (acesso_item.nome_login = '" . $login . "')
                            GROUP BY relatorio.id,relatorio.numero_relatorio, 
                                  relatorio.data_relatorio,
                                  especie_auditoria.nome_auditoria,
                                  relatorio.valor_prazo	                                    
                                    ORDER BY relatorio.data_relatorio DESC, relatorio.numero_relatorio DESC ";
                                    // data antiga -> ORDER BY date_part('year', relatorio.data_relatorio) DESC, relatorio.numero_relatorio DESC ";
	}

	$command = Yii::app()->db->createCommand($sql_rel);
	$result = $command->query();
	return ($result->readAll());
}

// m�todo que d� continuidade � busca das recomenda��es pendentes,
// na p�gina inicial(index). Recebe como par�metro o id do relat�rio
public function Relatorio_pendentes_index2($relatorio_id) {
	$login = Yii::app()->user->login;
	$schema = Yii::app()->params['schema'];
	$perfil = Yii::app()->user->role;
	$perfil = str_replace("siaudi2", "siaudi", $perfil);

	if ($perfil == "siaudi_cliente_item") {
		$sql_from = " INNER JOIN " . $schema . ".tb_relatorio_acesso_item acesso_item ON acesso_item.item_fk= item.id ";

		$sql_where = $sql_where . " AND (acesso_item.nome_login= '" . $login . "') ";
	}

	$sql = " SELECT capitulo.numero_capitulo,
                            item.numero_item,
                            recomendacao.id, 
                            recomendacao.numero_recomendacao,
                            recomendacao.descricao_recomendacao
                    FROM " . $schema . ".tb_capitulo  capitulo INNER JOIN
                         " . $schema . ".tb_item item ON capitulo.id=item.capitulo_fk INNER JOIN
                         " . $schema . ".tb_recomendacao recomendacao ON item.id=recomendacao.item_fk
                         " . $sql_from . "					
                    WHERE (capitulo.relatorio_fk = '" . $relatorio_id . "')
                            AND (recomendacao.recomendacao_tipo_fk = (SELECT id FROM " . $schema . ".tb_recomendacao_tipo WHERE nome_tipo ilike '%recomenda��o%')) /*Recomenda��o*/
                            " . $sql_where . "					
                    ORDER BY item.numero_item, recomendacao.numero_recomendacao";
        
	$command = Yii::app()->db->createCommand($sql);
	$result = $command->query();
	return ($result->readAll());

}

// m�todo que busca os relat�rios pendentes para
// gerar a tela de relat�rio do menu relat�rio -> relat�rios pendentes.
// recebe o ano exerc�cio como entrada (ex: 2013)
public function Relatorio_pendentes_saida($exercicio) {
        $id_und_adm = Yii::app()->user->id_und_adm;
	$login = Yii::app()->user->login;
	$schema = Yii::app()->params['schema'];
	$perfil = Yii::app()->user->role;
	$perfil = str_replace("siaudi2", "siaudi", $perfil);

	if ($perfil == "siaudi_cliente") {
		$sql_from = " INNER JOIN " . $schema . ".tb_acesso_relatorio acesso_relatorio ON relatorio.id = acesso_relatorio.relatorio_fk";
		$sql_where = $sql_where . " AND (acesso_relatorio.nome_login = '" . $login . "') ";
	} else if ((string) strpos($perfil, "siaudi_diretor") === (string) 0){
		$sql_from = " INNER JOIN " . $schema . ".tb_relatorio_diretoria relatorio_diretoria ON relatorio.id = relatorio_diretoria.relatorio_fk ";
		$sql_where = " AND (relatorio_diretoria.diretoria_fk = $id_und_adm)";
	}

	$sql = "SELECT relatorio.id,
                                relatorio.numero_relatorio, 
                                relatorio.data_relatorio,
                                relatorio.categoria_fk
                        FROM " . $schema . ".tb_relatorio relatorio $sql_from
                        WHERE  ((date_part('year', relatorio.data_relatorio) = '" . $exercicio . "') or ((relatorio.data_regulariza is NULL) and (date_part('year', relatorio.data_relatorio) <= '" . $exercicio . "')))
                                AND (relatorio.numero_relatorio IS NOT NULL) AND (relatorio.data_regulariza IS NULL) $sql_where
                        ORDER BY date_part('year',relatorio.data_relatorio) DESC, relatorio.numero_relatorio ";
	
	$command = Yii::app()->db->createCommand($sql);
	$result = $command->query();
	return ($result->readAll());
}

/* M�todo para verificar se o relat�rio pode ser aberto em PDF.
 *  Regras para abrir em PDF s�o:
 *  Regra 1 - Para relat�rios homologados: perfil de gerente, gerente_nucleo, coordenador, cliente ou cliente_item
 *                                                   siaudi_diretor
 *                                                   siaudi_cgu, siaudi_gabin.
 *  Regra 2 - Para relat�rios n�o-homologados: perfil de gerente, gerente_nucleo, coordenador ou auditor.
 *
 * @data_relatorio (date): data de homologa��o do relat�rio (null para relat�rio n�o homologado)
 */

public function Relatorio_Autorizar_PDF($data_relatorio) {
	$perfil = strtolower(Yii::app()->user->role);
	$perfil = str_replace("siaudi2", "siaudi", $perfil);
	$autorizar_pdf = 0;
	// Verifica regra 1
	if ($data_relatorio && ($perfil == "siaudi_gerente" || $perfil == "siaudi_chefe_auditoria" || $perfil == "siaudi_cliente" || $perfil == "siaudi_cliente_item" || $perfil == "siaudi_gerente_nucleo")) {
		$autorizar_pdf = 1;
	}
	// Verifica regra 2
	if (!$data_relatorio && ($perfil == "siaudi_gerente" || $perfil == "siaudi_chefe_auditoria" || $perfil == "siaudi_auditor" || $perfil == "siaudi_gerente_nucleo")) {
		$autorizar_pdf = 1;
	}

	// Verifica regra 3
	if ($data_relatorio && ((string) strpos($perfil, "siaudi_diretor") === (string) 0)){
		$autorizar_pdf = 1;
	}

	return $autorizar_pdf;
}

/* M�todo para buscar os usu�rios que acessaram os relat�rios (ou itens de relat�rio).
 * @parametros (array) - cont�m os par�metros necess�rios (id do relat�rio - para clientes -, data de in�cio e data de fim).
 */

public function RelatorioRegistrosAcessos($parametros) {
	$relatorio_id = $parametros["relatorio_id"];
	// converte datas do  per�odo para formato americano
	$periodo_inicio = MyFormatter::converteData($parametros["periodo_inicio"]);
	$periodo_fim = MyFormatter::converteData($parametros["periodo_fim"]);

	$criteria = new CDbCriteria();

	// faz uma lista com o login dos auditores (al�m de gerentes e chefes de auditoria) cadastrados
	if ($parametros["filtro_acesso"] == "auditor") {
		$Auditores = Usuario::model()->findAll();
		if (sizeof($Auditores)){
			foreach ($Auditores as $vetor) {
				$vetor_logins[] = $vetor->nome_login;
			}
		}
	}

	// faz uma lista com todos os clientes que possuem acesso ao relat�rio e item de relat�rio espec�fico
	if ($parametros["filtro_acesso"] == "cliente") {
		//Pega clientes do relat�rio
		$Clientes = RelatorioAcesso::model()->findAllbyAttributes(array('relatorio_fk' => $parametros["relatorio_id"]));
		if (sizeof($Clientes)){
			foreach ($Clientes as $vetor) {
				$vetor_logins[] = $vetor->nome_login;
			}
		}

		//Pega clientes de um item do relat�rio
		$schema = Yii::app()->params['schema'];

		$sql = "SELECT nome_login
                    FROM " . $schema . ".tb_relatorio_acesso_item
                    where item_fk IN (SELECT item.id
                                        FROM " . $schema . ".tb_item item 
                                          INNER JOIN " . $schema . ".tb_capitulo capitulo ON item.capitulo_fk = capitulo.id
                                          INNER JOIN " . $schema . ".tb_relatorio relatorio ON capitulo.relatorio_fk = relatorio.id
                                        WHERE  relatorio.id=" . $parametros[relatorio_id] . ")";
		$command = Yii::app()->db->createCommand($sql);
		$result = $command->query();
		$result = $result->readAll();
		// se existem clientes nos itens do relat�rio, ent�o incrementa vetor de logins
		// logins na tabela tb_relatorio_acesso_item
		if (sizeof($result) > 0) {
			foreach ($result as $vetor) {
				$vetor_logins[] = $vetor[nome_login];
			}
		}
		// filtra relat�rio selecionado para perfil do cliente, ou relat�rios em branco (em caso de acesso ao sistema)
		$criteria->addCondition(array("relatorio_fk=$parametros[relatorio_id]", "relatorio_fk IS NULL"), "OR");
	}

	$criteria->addInCondition("nome_login", $vetor_logins);
        
        
        if ($periodo_inicio==$periodo_fim){
            
        $criteria->addCondition("to_char(data_entrada,'YYYY-MM-DD')='" . $periodo_inicio . "'");
        } else {
	$criteria->addCondition("to_char(data_entrada,'YYYY-MM-DD')>='" . $periodo_inicio . "' AND to_char(data_entrada,'YYYY-MM-DD')<='" . $periodo_fim . "'");
        }
        
	$criteria->order = 'data_entrada DESC';

	$result = LogEntrada::model()->findAll($criteria);

	return ($result);
}

}