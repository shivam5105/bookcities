<div class="login-page inset">	
	<div class="row cf">
		<div class="col c12 centered">
			<h1 class="page-title">Book Cities - Login</h1>
			<div class="form">
			<?php echo form_open("admin/auth");?>
				<div class="field input">
					<label for="email">Email</label>
					<input type="text" class="text" id="email" name="email" value="<?php echo set_value('email'); ?>" />
					<?php echo form_error('email'); ?>
				</div>
				<div class="field input">
					<label for="password">Password</label>
					<input type="password" class="text" id="password" name="password" value="<?php echo set_value('password'); ?>" />
					<?php echo form_error('password'); ?>
				</div>
				<div class="field action cf">
					<input type="submit" value="Login" class="button primary" />
				</div>
			<?php echo form_close();?>
			</div>
		</div>
	</div>
</div>