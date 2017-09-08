<script src="<?php echo asset_url('tinymce/tinymce.min.js'); ?>"></script>
<script>
$(document).ready(function(){
	$("#content").tinymce({
		theme: 'modern',
		plugins: [
		'advlist autolink lists link image preview hr anchor pagebreak',
		'searchreplace wordcount visualblocks visualchars code fullscreen',
		'insertdatetime media nonbreaking save table contextmenu directionality',
		'template paste textcolor colorpicker textpattern imagetools'
		],
		paste_as_text: true,
		toolbar1: 'formatselect fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | cut copy paste | outdent indent blockquote | link unlink image media | forecolor backcolor | insertdatetime table hr | searchreplace removeformat fullscreen preview code',
		image_advtab: true,
		menubar: false,
		content_css: [
			'<?php echo asset_url('css/tinmice.css'); ?>'
		]
	})
});
function setSlug(val){
	var slug = (val.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,''));
	$('span.slug').text(slug);
	$('input.slug').val(slug);
}
</script>
<script src="<?php echo asset_url('tinymce/jquery.tinymce.min.js'); ?>"></script>
<div class="secondry-nav cf">
	<?php $this->load->view('part/pages_nav'); ?>
</div>
<?php echo form_open("admin/pages/update");?>
<input type="hidden" name="id" value="<?php echo $page->id; ?>" />
<div class="inset">	
	<div class="row cf">
		<div class="col c12 centered">
			<h1 class="page-title small"></h1>
		</div>
	</div>
	<div class="row cf">
		<div class="col c8">
			<div class="form">
				<div class="field input focus">
					<label for="title">Title</label>
					<input type="text" class="text" id="title" name="title" value="<?php echo $page->title; ?>" />
					<?php echo form_error('title'); ?>
				</div>
				<div class="field input focus">
					<label for="slug">URL: <?php echo base_url(); ?><span class="slug"><?php echo $page->slug; ?></span></label>
					<div class="input-box">
						<input type="text" onBlur="setSlug(this.value);" class="text slug" id="slug" name="slug" value="<?php echo $page->slug; ?>" />
					</div>
					<?php echo form_error('slug'); ?>
				</div>
				<div class="field input focus">
					<label for="content">Content</label>
					<textarea class="tarea content-area" id="content" name="content"><?php echo $page->content; ?></textarea>
				</div>
			</div>
		</div>
		<div class="col c4">
			<div class="form-action form">
				<h3 class="section-title">Update page</h3>
				<div class="field radio cf">
					<label>
						<input type="radio" checked class="radio" name="status" value="1" />
						Publish
					</label>
					<label>
						<input type="radio" class="radio" name="status" value="0" />
						Save as draft
					</label>
				</div>
				<div class="field action cf">
					<input type="submit" value="Update" class="button primary" />
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close();?>