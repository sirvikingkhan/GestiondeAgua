<?php
echo form_open_multipart('employees/do_excel_import/',array('id'=>'supplier_form'));
?>
<div id="required_fields_message">Importa datos de una hoja de Excel.</div>
<ul id="error_message_box"></ul>
<b><a href="<?php echo base_url(). '/'.'Import-PHPPOS-Sup.xls'; ?>">Download Import Excel Template</a></b>
<fieldset id="item_basic_info">
<legend>Importar</legend>

<div class="field_row clearfix">	
<?php echo form_label('Camino Archivo:', 'name',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_upload(array(
		'name'=>'file_path',
		'id'=>'file_path',
		'value'=>'')
	);?>
	</div>
</div>

<?php
echo form_submit(array(
	'name'=>'submitf',
	'id'=>'submitf',
	'value'=>$this->lang->line('common_submit'),
	'class'=>'submit_button float_right')
);
?>
</fieldset>
<?php 
echo form_close();
?>
<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{	
	$('#supplier_form').validate({
		submitHandler:function(form)
		{
			/*
			make sure the hidden field #item_number gets set
			to the visible scan_item_number value
			*/
			//$('#item_number').val($('#scan_item_number').val());
			$(form).ajaxSubmit({
			success:function(response)
			{
				tb_remove();
				post_person_form_submit(response);
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules: 
		{
			file_path:"required"
   		},
		messages: 
		{
   			file_path:"Es requerido el camino completo al archivo de excel"
		}
	});
});
</script>