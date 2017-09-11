<?php $this->load->view('part/header'); ?>
<?php 

$loggedin_data = $this->users->get_loggedin_data();
 $loggedin_user_role = $loggedin_data['role'];

?>
<script src="<?php echo asset_url('tinymce/tinymce.min.js'); ?>"></script>
<script>
	$(document).ready(function(){
		$("#descriptions").tinymce({
			theme: 'modern',
			plugins: [
			'autolink link paste',
			'save contextmenu directionality'
			],
			paste_as_text: true,
			/*plugins: [
			'advlist autolink lists link image preview hr anchor pagebreak',
			'searchreplace wordcount visualblocks visualchars code fullscreen',
			'insertdatetime media nonbreaking save table contextmenu directionality',
			'template paste textcolor colorpicker textpattern imagetools'
			],
			toolbar1: 'formatselect fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | cut copy paste | outdent indent blockquote | link unlink image media | forecolor backcolor | insertdatetime table hr | searchreplace removeformat fullscreen preview code',*/
			toolbar1: 'bold italic underline',
			image_advtab: false,
			menubar: false,
			content_css: [
				'<?php echo asset_url('css/tinmice.css'); ?>'
			]
		})
	});
</script>
<script src="<?php echo asset_url('tinymce/jquery.tinymce.min.js'); ?>"></script>
<link href="<?php echo asset_url('css/jquery-ui.min.css'); ?>" rel="stylesheet">
<script src="<?php echo asset_url('js/jquery-ui.min.js'); ?>"></script>
<script>
	/*var abc = 0; // Declaring and defining global increment variable.
	$(document).ready(function() {
	    //  To add new input file field dynamically, on click of "Add More Files" button below function will be executed.
	    $('#add_more').click(function() {
	        $(this).before($("<div/>", {
	            id: 'filediv'
	        }).fadeIn('slow').append($("<input/>", {
	            name: 'file[]',
	            type: 'file',
	            id: 'file'
	        })));
	    });
	    // Following function will executes on change event of file input to select different file.
	    $('body').on('change', '#file', function() {
	        if (this.files && this.files[0]) {
	            abc += 1; // Incrementing global variable by 1.
	            var z = abc - 1;
	            var x = $(this).parent().find('#previewimg' + z).remove();
	            $(this).before("<div id='abcd" + abc + "' class='abcd'><img id='previewimg" + abc + "' src=''/></div>");
	            var reader = new FileReader();
	            reader.onload = imageIsLoaded;
	            reader.readAsDataURL(this.files[0]);
	            $(this).hide();
	            $("#abcd" + abc).append($("<img/>", {
	                id: 'img',
	                src: '<?php echo asset_url("images/delete-icon_24.png"); ?>',
	                alt: 'delete'
	            }).click(function() {
	                $(this).parent().parent().remove();
	            }));
	        }
	    });
	    // To Preview Image
	    function imageIsLoaded(e) {
	        $('#previewimg' + abc).attr('src', e.target.result);
	    };
	    $('#upload').click(function(e) {
	        var name = $(":file").val();
	        if (!name) {
	            alert("First Image Must Be Selected");
	            e.preventDefault();
	        }
	    });
		$(".chosen").chosen({
			disable_search_threshold: 10,
			no_results_text: "Oops, nothing found!",
		});
	});*/
	$(document).ready(function() {
		$(".chosen").chosen({
			disable_search_threshold: 10,
			no_results_text: "Oops, nothing found!",
		});
	});
	$(function() {
		var dateFormat = "mm/dd/yy",
			holiday_from = $("#holiday_from").datepicker({
					/*defaultDate: "+1w",*/
					changeMonth: true,
					numberOfMonths: 1,
					minDate: "<?php echo date('m/d/Y', time()); ?>",
				}).on("change", function() {
					holiday_to.datepicker("option", "minDate", getDate(this));
				}),
			holiday_to = $("#holiday_to").datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				numberOfMonths: 1
			}).on("change", function() {
				holiday_from.datepicker("option", "maxDate", getDate(this));
			});

		function getDate(element)
		{
			var date;
			try {
				date = $.datepicker.parseDate(dateFormat, element.value);
			}catch(error) {
				date = null;
			}
			return date;
		}
	});
</script>
<div class="login-page inset">
	<div class="row cf">
		<div class="col c12 centered">
			<h1 class="page-title">Edit Store</h1>
		</div>
	</div>
</div>
<?php
$form_attributes = array("id"=>"shop_form");
echo form_open_multipart("admin/stores/edit/".$store->id,$form_attributes);
?>
	<div class="inset">
		<div class="row cf">
			<div class="col c8">
				<div class="form">
					<div class="field input">
						<label for="name">Shop Name <sup>*</sup></label>
						<input type="text" class="text" id="name" name="name" value="<?php echo $store->name; ?>" />
						<?php echo form_error('name'); ?>
					</div>
					<div class="field input">
						<label for="address">Street <sup>*</sup></label>
						<input type="text" class="text" id="address" name="address" value="<?php echo $store->address; ?>" />
						<?php echo form_error('address'); ?>
					</div>
					<!-- <div class="field input">
						<input type="text" class="text" id="address_2" name="address_2" value="<?php echo $store->address_2; ?>" />
					</div> -->
					<div class="field input">
						<label for="country">Select Country <sup>*</sup></label>
						<?php echo show_countries($countries, $store->country); ?>
						<?php echo form_error('country'); ?>
						<input type="text" class="text" id="country_other" name="country_other" value="<?php echo set_value('country_other'); ?>" placeholder="Other Country..." style="display:none;" />
						<?php echo form_error('country_other'); ?>
					</div>
					<div class="row cf">
						<div class="col c4">
							<div class="field input keep-focus">
								<label for="state">Select State <sup>*</sup></label>
								<?php echo show_states($states, $store->state); ?>
								<?php echo form_error('state'); ?>
								<input type="text" class="text" id="state_other" name="state_other" value="<?php echo set_value('state_other'); ?>" placeholder="Other State..." style="display:none;" />
								<?php echo form_error('state_other'); ?>
							</div>
						</div>
						<div class="col c4" style="margin:0 4%;">
							<div class="field input keep-focus">
								<label for="city">Select City <sup>*</sup></label>
								<?php echo show_cities($cities, $store->city); ?>
								<?php echo form_error('city'); ?>
								<input type="text" class="text" id="city_other" name="city_other" value="<?php echo set_value('city_other'); ?>" placeholder="Other City..." style="display:none;" />
								<?php echo form_error('city_other'); ?>
							</div>
						</div>
						<div class="col c3 right">
							<div class="field input keep-focus">
								<label for="zipcode">Zip code <sup>*</sup></label>
								<input type="text" class="text" id="zipcode" name="zipcode" value="<?php echo $store->zipcode; ?>" />
								<?php echo form_error('zipcode'); ?>
							</div>
						</div>
					</div>
					<div class="field input">
						<label for="latitude">Latitude <sup>*</sup></label>
						<input type="text" class="text" id="latitude" name="latitude" value="<?php echo $store->latitude; ?>" />
						<?php echo form_error('latitude'); ?>
					</div>
					<div class="field input">
						<label for="longitude">Longitude <sup>*</sup></label>
						<input type="text" class="text" id="longitude" name="longitude" value="<?php echo $store->longitude; ?>" />
						<?php echo form_error('longitude'); ?>
					</div>
					<div class="field input">
						<label for="phone">Phone</label>
						<input type="text" class="text" id="phone" name="phone" value="<?php echo $store->phone; ?>" />
						<?php echo form_error('phone'); ?>
					</div>
					<div style="font-size: 12px; font-weight: bold; color: orange; margin-top: -22px; margin-bottom: 20px;">Example: +XX XXX XXX XXXX</div>
					<div class="field input">
						<label for="website">Website</label>
						<input type="text" class="text" id="website" name="website" value="<?php echo $store->website; ?>" />
						<?php echo form_error('website'); ?>
					</div>
					<div class="field input">
						<label for="user_email">Email Address</label>
						<input type="text" class="text" id="user_email" name="user_email" value="<?php echo $store->user_email; ?>" />
					</div>
					<!-- <div class="field input">
						<label for="name">Images</label>
						<div class="db_images_preview_wrapper">
							<?php
							foreach($store_images as $img_key => $img_val)
							{
								$img_name = $img_val->file_name;
								?>
								<div class="abcd image_preview" id="image_preview-<?php echo $img_val->id; ?>">
									<img src="<?php echo base_url($this->store_images.$img_name);?>">
									<img class="image_preview_delete" data-id="<?php echo $img_val->id; ?>" src="<?php echo asset_url("images/delete-icon_24.png"); ?>" alt="delete">
								</div>
								<?php
							}
							?>
						</div>
						<div id="filediv"><input name="file[]" type="file" id="file"/></div>
						<input type="button" id="add_more" class="add_more_button" value="Add More Images"/>
					</div> -->
					<div class="field input">
						<label>Opening Hours <sup>*</sup></label>
						<!-- <textarea class="tarea" id="working_hours" name="working_hours"><?php echo set_value('working_hours'); ?></textarea>
						<?php echo form_error('working_hours'); ?> -->
						
						<!-- <div class="row cf by_appointment_checkbox_wrapper">
							<div class="col c3 relative">
								<label for="by_appointment"><input type="checkbox" name="by_appointment" id="by_appointment" value="1" <?php if($store->by_appointment == 1){ echo "checked='checked'"; } ?> /> &nbsp; By Appointment</label>
							</div>
						</div> -->

						<div class="row cf">
							<div class="col c12per"></div>
							<div class="col c2"><span class="common-label" style="margin-left: 40px;">From</span></div>
							<div class="col c2"><span class="common-label" style="margin-left: 48px;">To</span></div>
							<div class="col c2"><span class="common-label" style="margin-left: 16px;">Lunch From</span></div>
							<div class="col c2"><span class="common-label" style="margin-left: 25px;">Lunch To</span></div>
						</div>
						<?php
						foreach ($this->opening_days as $key => $day)
						{
							$store_day_from_hr 			= $store->{$day.'_from_hr'};
							$store_day_from_mins 		= $store->{$day.'_from_mins'};
							$store_day_to_hr 			= $store->{$day.'_to_hr'};
							$store_day_to_mins 			= $store->{$day.'_to_mins'};
							$store_day_lunch_from_hr 	= $store->{$day.'_lunch_from_hr'};
							$store_day_lunch_from_mins 	= $store->{$day.'_lunch_from_mins'};
							$store_day_lunch_to_hr 		= $store->{$day.'_lunch_to_hr'};
							$store_day_lunch_to_mins 	= $store->{$day.'_lunch_to_mins'};

							$store_day_by_appointment 	= $store->{$day.'_by_appointment'};

							$checked = "";
							if($store_day_from_hr > 0 || $store_day_from_mins > 0 || $store_day_to_hr > 0 || $store_day_to_mins > 0)
							{
								$checked = "checked='checked'";
							}
							?>
							<div class="row cf opening_hours_row">
								<div class="col relative c12per">
									<label for="<?php echo $day; ?>"><input type="checkbox" name="<?php echo $day; ?>" id="<?php echo $day; ?>" value="1" class="opening_day_checkbox" data-day="<?php echo $day; ?>" <?php echo $checked; ?> /> &nbsp; <?php echo ucwords($day); ?></label>
								</div>
								<div class="col c2 <?php echo $day; ?>">
									<select name="<?php echo $day; ?>_from_hr" id="<?php echo $day; ?>_from_hr" <?php if(trim($checked) == ""){ echo "disabled"; } ?>>
										<?php
										for($hr = 0; $hr < 24; $hr++)
										{
											$hr = str_pad($hr,2,"0", STR_PAD_LEFT);
											$select = "";
											if($hr == $store_day_from_hr)
											{
												$select = "Selected='selected'";
											}
											?>
											<option value="<?php echo $hr; ?>" <?php echo $select; ?>><?php echo $hr; ?></option>
											<?php
										}
										?>
									</select>
									:									
									<select name="<?php echo $day; ?>_from_mins" id="<?php echo $day; ?>_from_mins" <?php if(trim($checked) == ""){ echo "disabled"; } ?>>
										<?php
										for($min = 0; $min < 46; $min += 15)
										{
											$min = str_pad($min,2,"0", STR_PAD_LEFT);
											$select = "";
											
											if($min == $store_day_from_mins)
											{
												$select = "Selected='selected'";
											}
											?>
											<option value="<?php echo $min; ?>" <?php echo $select; ?>><?php echo $min; ?></option>
											<?php
										}
										?>
									</select>
								</div>
								<div class="col c2 <?php echo $day; ?>">
									<select name="<?php echo $day; ?>_to_hr" id="<?php echo $day; ?>_to_hr" <?php if(trim($checked) == ""){ echo "disabled"; } ?>>
										<?php
										for($hr = 0; $hr < 24; $hr++)
										{
											$hr = str_pad($hr,2,"0", STR_PAD_LEFT);
											$select = "";
											if($hr == $store_day_to_hr)
											{
												$select = "Selected='selected'";
											}
											?>
											<option value="<?php echo $hr; ?>" <?php echo $select; ?>><?php echo $hr; ?></option>
											<?php
										}
										?>
									</select>
									:
									<select name="<?php echo $day; ?>_to_mins" id="<?php echo $day; ?>_to_mins" <?php if(trim($checked) == ""){ echo "disabled"; } ?>>
										<?php
										for($min = 0; $min < 46; $min += 15)
										{
											$min = str_pad($min,2,"0", STR_PAD_LEFT);
											$select = "";
											if($min == $store_day_to_mins)
											{
												$select = "Selected='selected'";
											}
											?>
											<option value="<?php echo $min; ?>" <?php echo $select; ?>><?php echo $min; ?></option>
											<?php
										}
										?>
									</select>
								</div>
								<div class="col c2 <?php echo $day; ?>">
									<select name="<?php echo $day; ?>_lunch_from_hr" id="<?php echo $day; ?>_lunch_from_hr" <?php if(trim($checked) == ""){ echo "disabled"; } ?>>
										<?php
										for($hr = 0; $hr < 24; $hr++)
										{
											$hr = str_pad($hr,2,"0", STR_PAD_LEFT);
											$select = "";
											if($hr == $store_day_lunch_from_hr)
											{
												$select = "Selected='selected'";
											}
											?>
											<option value="<?php echo $hr; ?>" <?php echo $select;?>><?php echo $hr; ?></option>
											<?php
										}
										?>
									</select>
									:
									<select name="<?php echo $day; ?>_lunch_from_mins" id="<?php echo $day; ?>_lunch_from_mins" <?php if(trim($checked) == ""){ echo "disabled"; } ?>>
										<?php
										for($min = 0; $min < 46; $min += 15)
										{
											$min = str_pad($min,2,"0", STR_PAD_LEFT);
											$select = "";
											if($min == $store_day_lunch_from_mins)
											{
												$select = "Selected='selected'";
											}
											?>
											<option value="<?php echo $min; ?>" <?php echo $select;?>><?php echo $min; ?></option>
											<?php
										}
										?>
									</select>
								</div>
								<div class="col c2 <?php echo $day; ?>">
									<select name="<?php echo $day; ?>_lunch_to_hr" id="<?php echo $day; ?>_lunch_to_hr" <?php if(trim($checked) == ""){ echo "disabled"; } ?>>
										<?php
										for($hr = 0; $hr < 24; $hr++)
										{
											$hr = str_pad($hr,2,"0", STR_PAD_LEFT);
											$select = "";
											if($hr == $store_day_lunch_to_hr)
											{
												$select = "Selected='selected'";
											}
											?>
											<option value="<?php echo $hr; ?>" <?php echo $select;?>><?php echo $hr; ?></option>
											<?php
										}
										?>
									</select>
									:
									<select name="<?php echo $day; ?>_lunch_to_mins" id="<?php echo $day; ?>_lunch_to_mins" <?php if(trim($checked) == ""){ echo "disabled"; } ?>>
										<?php
										for($min = 0; $min < 46; $min += 15)
										{
											$min = str_pad($min,2,"0", STR_PAD_LEFT);
											$select = "";
											if($min == $store_day_lunch_to_mins)
											{
												$select = "Selected='selected'";
											}
											?>
											<option value="<?php echo $min; ?>" <?php echo $select;?>><?php echo $min; ?></option>
											<?php
										}
										?>
									</select>
								</div>
								<div class="col c2 <?php echo $day; ?>">
									<div class="relative">
										<?php
										$checked = "";
										if($store_day_by_appointment > 0)
										{
											$checked = "checked='checked'";
										}
										?>
										<label for="<?php echo $day; ?>_by_appointment"><input type="checkbox" name="<?php echo $day; ?>_by_appointment" id="<?php echo $day; ?>_by_appointment" value="1" class="by_appointment_checkbox" data-day="<?php echo $day; ?>" <?php echo $checked; ?> /> &nbsp; By Appointment</label>
									</div>
								</div>
							</div>
							<?php
						}
						?>
					</div>
					<div class="field input">
						<div class="row cf">
							<div class="col c2 relative">
								<label for="on_holiday"><input type="checkbox" id="on_holiday" name="on_holiday" value="1" class="on_holiday_checkbox" <?php if($store->on_holiday){ echo "checked='checked'"; } ?> /> On Holiday?</label>
							</div>
							<div class="col c3 on_holiday_related_fields">
								<?php
								$holiday_from = "";
								if(!empty($store->holiday_from))
								{
									$holiday_from = date('m/d/Y',$store->holiday_from);
								}
								?>
								<span class="common-label">From</span> <input type="text" class="text inline-block half-width" id="holiday_from" name="holiday_from" value="<?php echo $holiday_from; ?>" placeholder="mm/dd/yyyy" />
							</div>
							<div class="col c3 on_holiday_related_fields">
								<?php
								$holiday_to = "";
								if(!empty($store->holiday_to))
								{
									$holiday_to = date('m/d/Y',$store->holiday_to);
								}
								?>
								<span class="common-label">To</span> <input type="text" class="text inline-block half-width" id="holiday_to" name="holiday_to" value="<?php echo $holiday_to; ?>" placeholder="mm/dd/yyyy" />
							</div>
						</div>
					</div>
					<div class="field input on_holiday_related_fields">
						<label for="holiday_message">Holiday Flash Message</label>
						<input type="text" class="text" id="holiday_message" name="holiday_message" value="<?php echo $store->holiday_message; ?>" />
						<?php echo form_error('holiday_message'); ?>
					</div>	
					<div class="field input shop_description">
						<label for="descriptions">Shop Description <sup>*</sup></label>
						<textarea class="tarea content-area" id="descriptions" name="descriptions"><?php echo $store->descriptions; ?></textarea>
						<?php echo form_error('descriptions'); ?>
					</div>
					<div class="field input">
						<label for="name">Images</label>
						<?php
						for ($img=1; $img <= $this->store_max_images; $img++)
						{
							$cur_image_name = $store->{'image'.$img};
							?>
							<div class="row cf shop_images_upload">
								<div class="col c2">
									Image <?php echo $img; echo $img < 2 ? "*" : "" ?>
								</div>
								<div class="col c3 relative">
									<input class="reg_images" name="image<?php echo $img; ?>" id="image<?php echo $img; ?>" type="file" style="margin-top:5px;" />
									<?php echo form_error('image'.$img); ?>
									<input type="hidden" name="cur_image<?php echo $img; ?>" id="cur_image<?php echo $img; ?>" value="<?php echo $cur_image_name; ?>">
								</div>
								<?php
								if(trim($cur_image_name) != "")
								{
									?>
									<div class="col c7 store_images_preview">
										<div class="abcd image_preview" id="image_preview-<?php echo $img; ?>">
											<img src="<?php echo base_url($this->store_images.$cur_image_name);?>">
											<img class="image_preview_delete" data-store-id="<?php echo $store->id; ?>" data-img-no="<?php echo $img; ?>" src="<?php echo asset_url("images/delete-icon_24.png"); ?>" alt="delete">
										</div>

									</div>
									<?php
								}
								?>
							</div>
							<?php
						}
						?>
						<br />
						<p class="reg_image_note"><b><em>NOTE</em>: Allowed image formats are .jpg, .jpeg, .png, .gif. Upload file size is Max 2000KB and Min 50KB.</b></p>
					</div>
				</div>
			</div>
			<div class="col c4">
				<div class="form-action form">
					<h3 class="section-title">Update</h3>
					<div class="field radio cf">
						<?php if($loggedin_user_role == 1)
				             { ?>
						<label>
							<input type="radio" checked class="radio" name="status" value="1" />
							Publish
						</label>
						<label>
							<input type="radio" class="radio" name="status" value="0" />
							Save as draft
						</label>
							 <?php }else{ ?>
                                <label>
							<input type="radio" checked class="radio" name="status" value="0" />
							Submit 
						</label>						
						<?php } ?>
					</div>
					<div class="field action cf">
						<input type="submit" value="Update" class="button primary" />
					</div>
				</div>
				<div class="form-action form deals-in">
					<h3 class="section-title">Store Deals in <sup>*</sup></h3>
					<div class="field radio cf">
						<?php
						foreach ($deals_in as $key => $value)
						{
							$checked = "";
							if($store->$key == 1)
							{
								$checked = "checked='checked'";
							}
							?>
							<div>
								<label>
									<input type="checkbox" name="<?php echo $key; ?>" value="1" class="store_deals_in" <?php echo $checked; ?> />
									<?php echo $value;?>
								</label>
							</div>
							<?php
						}
						?>
						<br />
						<b><label><em>NOTE:</em> Choose up to two options</label></b>
					</div>
				</div>
				<div class="form-action form">
					<h3 class="section-title">Books Categories <sup>*</sup></h3>
					<div class="field radio cf" style="height:100px; overflow: auto;">
						<?php
						$books_categories = array_filter(array_unique(@explode(":", $store->books_category_ids)));
						foreach ($bookcategories as $key => $category)
						{
							$catID 	= $category->id;
							$catName= $category->name;
							$checked = "";
							if(@in_array($catID, $books_categories))
							{
								$checked = "checked='checked'";
							}
							?>
							<div>
								<label>
									<input type="checkbox" name="books_categories[]" value="<?php echo $catID;?>" class="book_categories" <?php echo $checked; ?> />
									<?php echo $catName;?>
								</label>
							</div>
							<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo form_close();?>