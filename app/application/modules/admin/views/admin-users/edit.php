<?php $this->load->view('part/header'); ?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#name").focus();
	});
</script>
<div class="login-page inset">
	<div class="row cf">
		<div class="col c12 centered">
			<h1 class="page-title">Add New User</h1>
		</div>
	</div>
</div>
<?php
$form_attributes = array("id"=>"admin_user_form");
echo form_open("",$form_attributes);
?>
	<input type="hidden" name="role" id="role" value="2" /><?php /* roles: 1 for superadmin, 2 for normal-admin/staff */?>
	<div class="inset">
		<div class="row cf">
			<div class="col c8">
				<div class="form">
					<div class="field input">
						<label for="first_name">First Name</label>
						<input type="text" class="text" id="first_name" name="first_name" value="<?php echo $user->first_name; ?>" required />
						<?php echo form_error('first_name'); ?>
					</div>
					<div class="field input">
						<label for="last_name">Last Name</label>
						<input type="text" class="text" id="last_name" name="last_name" value="<?php echo $user->last_name; ?>" required />
						<?php echo form_error('last_name'); ?>
					</div>
					<div class="field input">
						<label for="email">Email</label>
						<input type="email" class="text" id="email" name="email" readonly value="<?php echo $user->email; ?>" required />
						<?php echo form_error('email'); ?>
					</div>
				</div>
			</div>
			<div class="col c4">
				<div class="form-action form">
					<h3 class="section-title">Create new User</h3>
					<div class="field radio cf">
						<label>
							<input type="radio" checked class="radio" name="status" value="1" <?php if($user->status){ echo "checked='checked'"; } ?> />
							Active
						</label>
						<label>
							<input type="radio" class="radio" name="status" value="0" <?php if(!$user->status){ echo "checked='checked'"; } ?> />
							In-Active
						</label>
					</div>
					<div class="field action cf">
						<input type="submit" value="Create" class="button primary" />
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo form_close();?>