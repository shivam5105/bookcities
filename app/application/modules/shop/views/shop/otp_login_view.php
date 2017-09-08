<header id="header" class="header">
	<div class="inset cf">
		<div class="logo">
			<a href="<?php echo admin_url(); ?>">
				<!-- <img src="<?php echo asset_url("images/logo.png"); ?>" height="40" /> -->
				Book Cities
			</a>
		</div>
	</div>
</header>
<?php echo form_open('/shop/otp_login_form?user_email='.$user_email); ?>
	<div class="inset">
		<div class="row cf">
			<div class="col c12">
				<div class="form">
					<div class="field input">
						<label>Enter Your OTP</label>
						<input type="number" placeholder="enter your OTP here" class="text" name="otp_form" value=""  />
						<input type="hidden" name="otp_email" value="<?php echo $user_email; ?>" />
						<?php echo form_error('otp_form'); ?>
					</div>
					<div class="field" align="center">
						<input  class="button primary" type="submit" value="Submit" />
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo form_close();?>