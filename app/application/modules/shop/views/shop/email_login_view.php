<div class="login-page inset">
	<div class="row cf">
		<div class="col c12 centered">
			<div class="reg_description">
				<div class="reg_logo_left">
					<a href="http://www.bookcities.org" target="blank"><img src="<?php echo asset_url('images/logo-new.png'); ?>"/></a>
					<p style="text-align:left;"><a href="http://www.bookcities.org" target="blank">www.bookcities.org</a></p>
				</div>
				<div class="row cf"> 
					<div class="col c8 left"> 
						<div class="text-wrap-reg"> 
							<p>To register your book store on Book Cities App please enter your e-mail:
								<br />
								To make changes on your previous entry, request a new password by re-entering your e-mail here:</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_open(); ?>
	<div class="inset">
		<div class="row cf">
			<div class="col c12">
				<div class="form">
					<div class="field input">
						<input type="text" placeholder="enter your mail here" class="text" name="email_form" value="" size="50" />
						<input type="hidden" name="number_otp" value="<?php echo rand(1000,9999); ?>" />
						<?php echo form_error('email_form'); ?>
					</div>
					<div class="reg_description text-left">
						<p>Check your mailbox: You will shortly receive a one-time password by e-mail.<br />
						The password is valid 1 hour.<br /></p>
					</div>
					<div class="field" align="center">
						<input  class="button primary" type="submit" value="Submit" />
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo form_close();?>