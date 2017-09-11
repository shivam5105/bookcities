<?php $this->load->view('part/header'); ?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#name").focus();
	});
</script>
<div class="login-page inset">
	<div class="row cf">
		<div class="col c12 centered">
			<h1 class="page-title">Add New Book Category</h1>
		</div>
	</div>
</div>
<?php echo form_open("admin/books_categories/create");?>
	<div class="inset">
		<div class="row cf">
			<div class="col c12">
				<div class="form">
					<div class="field input">
						<label for="name">Category Name</label>
						<input type="text" class="text" id="name" name="name" value="<?php echo set_value('name'); ?>" />
						<?php echo form_error('name'); ?>
					</div>					
					<div class="field action cf">
						<input type="submit" value="Create" class="button primary" />
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo form_close();?>