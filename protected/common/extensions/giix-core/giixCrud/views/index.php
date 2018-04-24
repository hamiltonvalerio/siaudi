<?php
$class = get_class($model);
Yii::app()->clientScript->registerScript('gii.crud', "
$('#{$class}_controller').change(function(){
	$(this).data('changed',$(this).val()!='');
});
$('#{$class}_model').bind('keyup change', function(){
	var controller=$('#{$class}_controller');
	if(!controller.data('changed')) {
		var id=new String($(this).val().match(/\\w*$/));
		if(id.length>0)
			id=id.substring(0,1).toLowerCase()+id.substring(1);
		controller.val(id);
	}
});
");
?>
<h1>Gerador de CRUD giix</h1>

<p>Este gerador gera um controlador que implementam as opera��es CRUD para o modelo de dados especificado. </p>

<?php $form = $this->beginWidget('CCodeForm', array('model' => $model)); ?>

<div class="row">
    <?php echo $form->labelEx($model, 'model'); ?>
    <?php
    $form->widget('zii.widgets.jui.CJuiAutoComplete', array(
        'model' => $model,
        'attribute' => 'model',
        'source' => $this->getModels(),
        'options' => array(
            'delay' => 100,
            'focus' => 'js:function(event,ui){
                    $(this).val($(ui.item).val());
                    $(this).trigger(\'change\');
                }',
        ),
        'htmlOptions' => array(
            'size' => '65',
        ),
    ));
    ?>
    <div class="tooltip">
        Classe de modelo � sens�vel a mai�sculas. Pode ser um nome de classe (por exemplo <code> Mensagem </code>)
        ou o alias caminho do arquivo de classe (por exemplo, <code> application.models.Post </code>).
        Note que se o primeiro, a classe deve ser auto-carregáveis.
    </div>
<?php echo $form->error($model, 'model'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'controller'); ?>
<?php echo $form->textField($model, 'controller', array('size' => 65)); ?>
    <div class="tooltip">
        Controlador de ID � case-sensitive. Controladores de CRUD s�o muitas vezes o nome de
         o nome da classe modelo que eles est�o lidando. Abaixo est�o alguns exemplos:
         <ul>
             <li> <code> pos</code> gera <code> PostController.php </ code> </ li>
             <li> <code> postTag </code> gera <code> PostTagController.php </ code> </ li>
             <li> <code> admin/usuario </code> gera <code> admin/UserController.php </ code>.
                 Se o aplicativo tem um administrador <code> </ code> m�dulo ativado,
                 ele ir� gerar <code> UserController </code> (e codigo CRUD outros)
                 dentro do m�dulo em seu lugar.
             </li>
         </ul>
    </div>
<?php echo $form->error($model, 'controller'); ?>
</div>

<div class="row sticky">
    <?php echo $form->labelEx($model, 'authtype'); ?>
<?php echo $form->dropDownList($model, 'authtype', array('auth_none' => 'No access control')); ?>
    <div class="tooltip">
        O m�todo de autentica��o a ser usado no controlador. Yii Controle o acesso � o
        AccessControl padr�o de Yii usando o accessRules Controller () m�todo. sem acesso
        Controle n�o fornece controle de acesso. No futuro iremos fornecer srbac e
        authtypes possivelmente outros.
    </div>
<?php echo $form->error($model, 'authtype'); ?>
</div>

<div class="row sticky">
    <?php echo $form->labelEx($model, 'enable_ajax_validation'); ?>
    <?php
    echo $form->dropDownList($model, 'enable_ajax_validation', array(
        1 => 'Enable ajax Validation',
        0 => 'Disable ajax Validation'
    ));
    ?>
    <div class="tooltip">
        Permite Valida��o instantanea de campos de entrada via Generator Yii de utilizando ajax
        pedidos depois de peder o foco(blur()) do campo.
    </div>
    <?php echo $form->error($model, 'persistent_sessions'); ?>
</div>

<div class="row sticky">
<?php echo $form->labelEx($model, 'baseControllerClass'); ?>
<?php echo $form->textField($model, 'baseControllerClass', array('size' => 65)); ?>
    <div class="tooltip">
       Esta � a classe que a nova classe do controlador CRUD se estender.
       Por favor, certifique-se a classe existe e podem ser carregados automaticamente.
    </div>
<?php echo $form->error($model, 'baseControllerClass'); ?>
</div>

<?php $this->endWidget(); ?>