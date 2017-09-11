<?php $this->load->view('part/header'); ?>
<script>
	$(document).ready(function() {
		$(".chosen").chosen({
			disable_search_threshold: 10,
			no_results_text: "Oops, nothing found!",
		});
	});
</script>
<div class="login-page inset">
	<div class="row cf">
		<div class="col c12 centered">
			<h1 class="page-title">Add New City</h1>
		</div>
	</div>
</div>
<?php echo form_open_multipart("");?>
	<div class="inset">
		<div class="row cf">
			<div class="col c12">
				<div class="form">
					<div class="field input">
						<label for="country">Select Country <sup>*</sup></label>
						<?php echo show_countries($countries); ?>
						<?php echo form_error('country'); ?>
						<input type="text" class="text" id="country_other" name="country_other" value="<?php echo set_value('country_other'); ?>" placeholder="Other Country..." style="display:none;" />
						<?php echo form_error('country_other'); ?>
					</div>
					<div class="field input">
						<label for="state">Select State <sup>*</sup></label>
						<?php echo show_states($states,"",false); ?>
						<?php echo form_error('state'); ?>
						<input type="text" class="text" id="state_other" name="state_other" value="<?php echo set_value('state_other'); ?>" placeholder="Other State..." style="display:none;" />
						<?php echo form_error('state_other'); ?>
					</div>
					<div class="field input">
						<label for="city">Enter City <sup>*</sup></label>
						<input type="text" class="text" id="city" name="city" value="<?php echo set_value('city'); ?>" />
						<?php echo form_error('city'); ?>
					</div>
					<!-- <div class="field input">
						<label for="city_latitude">Latitude</label> -->
						<input type="hidden" class="text" id="city_latitude" name="city_latitude" value="<?php echo set_value('city_latitude'); ?>" />
						<?php //echo form_error('city_latitude'); ?>
					<!-- </div> -->
					<!-- <div class="field input">
						<label for="city_longitude">Longitude</label> -->
						<input type="hidden" class="text" id="city_longitude" name="city_longitude" value="<?php echo set_value('city_longitude'); ?>" />
						<?php //echo form_error('city_longitude'); ?>
					<!-- </div> -->
					<div class="field action cf">
						<input type="submit" value="Create" class="button primary" />
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo form_close();?>