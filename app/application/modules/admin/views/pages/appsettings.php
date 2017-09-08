<?php $this->load->view('part/header'); ?>
<script src="<?php echo asset_url('tinymce/tinymce.min.js'); ?>"></script>
<script>
	$(document).ready(function(){
		$("#info_text").tinymce({
			theme: 'modern',
			plugins: [
			'advlist autolink lists link image preview hr anchor pagebreak',
			'searchreplace wordcount visualblocks visualchars code fullscreen',
			'insertdatetime media nonbreaking save table contextmenu directionality',
			'template paste textcolor colorpicker textpattern imagetools jbimages'
			],
			paste_as_text: true,
			toolbar1: 'formatselect fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | cut copy paste | outdent indent blockquote | link unlink | jbimages image media | forecolor backcolor | insertdatetime table hr | searchreplace removeformat fullscreen preview code',
			image_advtab: true,
			relative_urls: false,
			remove_script_host: false,
			menubar: false,
			content_css: [
				'<?php echo asset_url('css/tinmice.css'); ?>'
			]
		})
	});
</script>
<script src="<?php echo asset_url('tinymce/jquery.tinymce.min.js'); ?>"></script>
<div class="login-page inset">
	<div class="row cf">
		<div class="col c12 centered">
			<h1 class="page-title">App Settings</h1>
		</div>
	</div>
</div>
<?php echo form_open_multipart("");?>
	<div class="inset">
		<div class="row cf">
			<div class="col c12">
				<div class="form">
					<div class="field input shop_description">
						<label for="info_text">Info Text</label>
						<textarea class="tarea content-area" id="info_text" name="info_text"><?php echo $app_data->info_text; ?></textarea>
						<?php echo form_error('info_text'); ?>
					</div>
					<div class="field action cf">
						<input type="submit" value="Save" class="button primary" />
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo form_close();?>