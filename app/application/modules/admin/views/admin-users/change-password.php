<?php $this->load->view('part/header'); ?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#name").focus();
	});
</script>
<div class="login-page inset">
	<div class="row cf">
		<div class="col c12 centered">
			<h1 class="page-title">Change User Password</h1>
		</div>
	</div>
</div>
<?php
$form_attributes = array("id"=>"admin_user_form");
echo form_open("",$form_attributes);
?>
	<div class="inset">
		<div class="row cf">
			<div class="col c12">
				<div class="form">
					<div class="field input">
						<label for="password">Password</label>
						<input type="password" class="text" id="password" name="password" value="<?php echo set_value('password'); ?>" required />
						<?php echo form_error('password'); ?>
					</div>
					<div class="field input">
						<label for="cnf_password">Confirm Password</label>
						<input type="password" class="text" id="cnf_password" name="cnf_password" value="<?php echo set_value('cnf_password'); ?>" required />
						<?php echo form_error('cnf_password'); ?>
					</div>
					<div class="field input">
						<input type="submit" value="Update Password" class="button primary" />
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo form_close();?>