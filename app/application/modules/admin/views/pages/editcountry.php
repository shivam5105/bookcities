<?php $this->load->view('part/header'); ?>
<div class="login-page inset">
	<div class="row cf">
		<div class="col c12 centered">
			<h1 class="page-title">Edit Country</h1>
		</div>
	</div>
</div>
<?php echo form_open_multipart("admin/pages/editcountry?id=".$_GET['id']);?>
	<div class="inset">
		<div class="row cf">
			<div class="col c12">
				<div class="form">
					<div class="field input">
						<label for="name">Enter Country <sup>*</sup></label>
						<input type="text" class="text" id="name" name="name" value="<?php echo $country->name; ?>" />
						<?php echo form_error('name'); ?>
					</div>
					<div class="field input">
						<label for="sortname">Enter Country Short Name<sup>*</sup></label>
						<input type="text" class="text" id="sortname" name="sortname" value="<?php echo $country->sortname; ?>" />
						<?php echo form_error('sortname'); ?>
					</div>
					<div class="field input">
						<label for="country_code">Enter Country Code <sup>*</sup> <span style="margin-left:10px; text-transform:none;"><b><i>(Example: +1)</i></b></span></label>
						<input type="text" class="text" id="country_code" name="country_code" value="<?php echo $country->country_code; ?>" />
						<?php echo form_error('country_code'); ?>
					</div>
					<div class="field action cf">
						<input type="submit" value="Update" class="button primary" />
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo form_close();?>