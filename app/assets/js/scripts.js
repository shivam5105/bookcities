$.extend({
	getUrlVars: function(){
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++){
		  hash = hashes[i].split('=');
		  vars.push(hash[0]);
		  vars[hash[0]] = hash[1];
		}
		return vars;
	},
	getUrlVar: function(name){
		return $.getUrlVars()[name];
	}
});
$.fn.hasExtension = function(exts) {
    return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test($(this).val().toLowerCase());
}
$(document).ready(function(){
	$(document).on('focus change', 'input.text, .tarea', function(){
		$(this).parent().addClass('focus');
	});
	$(document).on('blur change', 'input.text, .tarea', function(){
		if($(this).val() == ""){
			$(this).parent().removeClass('focus');
		}
	});
	$('.icon-link.delete').click(function(e){
		if(!confirm('are you sure you want to delete this record? this action can\'t be undone')){
			e.preventDefault();
		};
	})
	$(document).on('click', '.ftoggle', function(e){
		e.preventDefault();
		var tgt = $($(this).attr('href'));
		tgt.fadeToggle();
	});
	$(".limit-char").keyup(function(){
		var maxLength = $(this).attr("maxlength");
		var length = $(this).val().length;
		var length = maxLength-length;
		$(this).parent().find('.char-limit').text(length+' characters remaining');
	}).focus(function(){
		var maxLength = $(this).attr("maxlength");
		var length = $(this).val().length;
		var length = maxLength-length;
		if($(this).parent().find('.char-limit').length == 0){
			$(this).parent().append('<div class="char-limit">'+ length +' characters remaining</div>');
		}else{
			$(this).parent().find('.char-limit').show(0);			
		}
	}).blur(function(){
		$(this).parent().find('.char-limit').hide(0);
	});
	$('input.text, .tarea').each(function(){
		if($(this).val() != ""){
			$(this).parent().addClass('focus');
		}
	});
	$(".num-box").on('keyup focus', function(){
		var val = $(this).val();
		var min = $(this).attr("min");
		var max = $(this).attr("max");
		if($(this).parent().find('.char-limit').length == 0){
			$(this).parent().append('<div class="char-limit"/>');
		}
		$(this).parent().find('.char-limit').text('Please eneter a valid number between "' + min + ' - ' + max + '"').show(0);
	}).blur(function(){
		$(this).parent().find('.char-limit').hide(0);
	});
	
	$('input.text, .tarea').each(function(){
		if($(this).val() != ""){
			$(this).parent().addClass('focus');
		}
	});
	$(".sh-next").click(function(e){
		e.preventDefault();
		$(this).next().toggleClass('hidden');
	});
	$("#main").css({
		minHeight: $(window).height() - 183
	})
	$('input.password-field').keyup(function() { 
		
		// set password variable
		var pswd = $(this).val();

		//validate the length
		if ( pswd.length < 8 ) {
			$('#length').removeClass('valid').addClass('invalid');
		} else {
			$('#length').removeClass('invalid').addClass('valid');
		}

		//validate letter
		if ( pswd.match(/[A-z]/) ) {
			$('#letter').removeClass('invalid').addClass('valid');
		} else {
			$('#letter').removeClass('valid').addClass('invalid');
		}

		//validate uppercase letter
		if ( pswd.match(/[A-Z]/) ) {
			$('#capital').removeClass('invalid').addClass('valid');
		} else {
			$('#capital').removeClass('valid').addClass('invalid');
		}

		//validate number
		if ( pswd.match(/\d/) ) {
			$('#number').removeClass('invalid').addClass('valid');
		} else {
			$('#number').removeClass('valid').addClass('invalid');
		}

	}).focus(function() {
		$('#pswd_info').show();
	}).blur(function() {
		$('#pswd_info').hide();
	});
	$(".opening_day_checkbox").click(function(){
		var day = $.trim($(this).attr('data-day'));

		if($(this).is(":checked"))
		{
			$("."+day+" select").removeAttr("disabled","disabled");
		}
		else
		{
			$("."+day+" select").attr("disabled","disabled");
		}
	});
	$(".by_appointment_checkbox").click(function(){
		var day = $.trim($(this).attr('data-day'));

		if($(this).is(":checked"))
		{
			$("."+day+" select").attr("disabled","disabled");
			$("#"+day).prop('checked', false).attr("disabled","disabled");
		}
		else
		{
			$("#"+day).removeAttr("disabled","disabled");
		}
	});
	$(".by_appointment_checkbox").each(function(){
		var day = $.trim($(this).attr('data-day'));

		if($(this).is(":checked"))
		{
			$("."+day+" select").attr("disabled","disabled");
			$("#"+day).prop('checked', false).attr("disabled","disabled");
		}
		else
		{
			$("#"+day).removeAttr("disabled","disabled");
		}
	});

	$(".image_preview_delete").click(function(){
		var store_id = $.trim($(this).attr("data-store-id"));
		var image_no = $.trim($(this).attr("data-img-no"));

		$.get(site_url+"stores/deleteimage/"+store_id+"/"+image_no, function(res){
			console.debug(res);
			if(res.success)
			{
				$("#image_preview-"+image_no+", #cur_image"+image_no).remove();
			}
			else
			{
				alert(res.msg);
			}
		}, "JSON");		
	});
	showHideOpeningHours();
	$("#by_appointment").click(function(){
		showHideOpeningHours();
	});
	$('input.store_deals_in').on('change', function (e) {
	    if ($('input.store_deals_in:checked').length > 2) {
	        $(this).prop('checked', false);
	    }
	});
	$("#shop_form").submit(function(){
		var name 			= $.trim($("#name").val());
		var address 		= $.trim($("#address").val());
		var country 		= $.trim($("#country").val());
		var country_other 	= $.trim($("#country_other").val());
		var state 			= $.trim($("#state").val());
		var state_other 	= $.trim($("#state_other").val());
		var city 			= $.trim($("#city").val());
		var city_other 		= $.trim($("#city_other").val());
		var zipcode 		= $.trim($("#zipcode").val());
		var phone 			= $.trim($("#phone").val());
		var website 		= $.trim($("#website").val());
        var descriptionWords= CountWords('descriptions');

        var maxDescWords	= 130;
		var validImageExtensions = [".jpg", ".jpeg", ".png"];

		var str = "";
		if(name == "" || name == null)
		{
			str += "Please enter 'Shop Name'.\n";
		}
		if(address == "" || address == null)
		{
			str += "Please enter 'Street'.\n";
		}
		if(country == "" || country == null)
		{
			str += "Please select 'Country'.\n";
		}
		else if(country < 0 && (country_other == null || country_other == ""))
		{
			str += "Please enter 'Other Country'.\n";
		}
		if(state == "" || state == null)
		{
			str += "Please select 'State'.\n";
		}
		else if(state < 0 && (state_other == null || state_other == ""))
		{
			str += "Please enter 'Other State'.\n";
		}
		if(city == "" || city == null)
		{
			str += "Please select 'City'.\n";
		}
		else if(city < 0 && (city_other == null || city_other == ""))
		{
			str += "Please enter 'Other City'.\n";
		}
		if(zipcode == "" || zipcode == null)
		{
			str += "Please enter 'Zip Code'.\n";
		}
		/*if(phone == "" || phone == null)
		{
			str += "Please enter 'Phone'.\n";
		}
		if(website == "" || website == null)
		{
			str += "Please enter 'Website'.\n";
		}*/
		var nonWorkingDays = 0;
		var byAppointmentDays = 0;

		$(".by_appointment_checkbox").each(function(){
			if($(this).is(":checked"))
			{
				byAppointmentDays++;
			}
		});
		/*if(!$("#by_appointment").is(":checked"))
		{*/
		if(byAppointmentDays <= 7)
		{
			$(".opening_day_checkbox").each(function(){
				if($(this).is(":checked"))
				{
					var day 			= $.trim($(this).attr('data-day'));
					var from_hr 		= $.trim($("#"+day+"_from_hr").val());
					var from_mins 		= $.trim($("#"+day+"_from_mins").val());
					var to_hr 			= $.trim($("#"+day+"_to_hr").val());
					var to_mins 		= $.trim($("#"+day+"_to_mins").val());
					var lunch_from_hr 	= $.trim($("#"+day+"_lunch_from_hr").val());
					var lunch_to_hr 	= $.trim($("#"+day+"_lunch_to_hr").val());

					if(from_hr == 0 && from_mins == 0 && to_hr == 0 && to_mins == 0)
					{
						str += "Please select 'Opening Hours'.\n";
					}
					if((lunch_from_hr > 0 && lunch_to_hr > 0) && (from_hr > lunch_from_hr || to_hr < lunch_to_hr))
					{
						str += " Lunch time should come b/w 'Opening Hours - From & To' time.\n";
					}
				}
				else
				{
					nonWorkingDays++;
				}
			});
			if(nonWorkingDays == 7 && byAppointmentDays == 0)
			{
				str += "Please select 'Opening Hours'.\n";
			}
		}
        if (descriptionWords == 0 || descriptionWords == null)
        {
        	str += "Please enter 'Shop Description'.\n";
        }
        else if (descriptionWords > maxDescWords)
        {
        	str += "'Shop Description' should not exceeds "+maxDescWords+" words.\n";
        }
        var imageErrorStr = "", imagesUploaded = 0, i = 0;
		$('input[name^="image"]').each(function () {
			i++;
			var imagePath = $(this).val();
			var currImage = "";
			if($("#cur_image"+i))
			{
				currImage = $.trim($("#cur_image"+i).val());
				console.debug(currImage);
			}
			if(currImage.length || imagePath.length)
			{
				imagesUploaded++;
			}
			if(imagePath.length)
			{
				var hasValidExt = $(this).hasExtension(validImageExtensions);
				if(!hasValidExt)
				{
					imageErrorStr += "'Image "+i+"' is invalid, allowed extensions are: " + validImageExtensions.join(", ")+"\n";
				}
			}
		});
		if(imagesUploaded < 1)
		{
			str += "Please upload atleast 1 images.\n";			
		}
		str += imageErrorStr; 

		var storeDealsIn = 0;
		$(".store_deals_in").each(function(){
			if($(this).is(":checked"))
			{
				storeDealsIn++;
				return false;
			}
		});
		if(storeDealsIn == 0)
		{
			str += "Please select 'Store Deals in'.\n";
		}
		var bookCategories = 0;
		$(".book_categories").each(function(){
			if($(this).is(":checked"))
			{
				bookCategories++;
				return false;
			}
		});

		if(bookCategories == 0)
		{
			str += "Please select 'Categories'.\n";
		}

		if(str != "")
		{
			alert(str);
			return false;
		}
		else
		{
			return true;
		}
	});

	$(".reg_images").change(function () {
		var file_size = this.files[0].size / 1024;
		file_size = Math.round(file_size);
		if(file_size > 2000)
		{
			alert("File size should be less than 2000KB");
			this.value = '';
			return false;
		}
		if(file_size < 50)
		{
			alert("File size should be greater than 50KB");
			this.value = '';
			return false;
		}
		
		var ext = this.value.match(/\.(.+)$/)[1];
		ext = ext.toLowerCase();
		switch (ext) {
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
        		//$('#uploadButton').attr('disabled', false);
        		break;
			default:
				alert('This is not an allowed image format.');
				this.value = '';
		}
	});
	toggle_holiday_related_fields();
	$(document).on("change","#on_holiday",function(){
		toggle_holiday_related_fields();
	});
});
function showHideOpeningHours()
{
	if($("#by_appointment").is(":checked"))
	{
		$(".opening_hours_row").hide();
	}
	else
	{
		$(".opening_hours_row").show();
	}
}
function toggle_holiday_related_fields()
{
	var $on_holiday = $("#on_holiday");
	
	if($on_holiday && $on_holiday.is(":checked"))
	{
		$(".on_holiday_related_fields").show();
	}
	else
	{
		$(".on_holiday_related_fields").hide();
	}
}
function print_state(self_elm, elm, val)
{
	$(self_elm+"_other").hide().val("");
	var html = '<option value="">Select State</option>';
	if(val > 0)
	{
		$.get(site_url+"pages/get_states/"+val, function(states){
			$(elm).html("");
			$(elm).append('<option value="">Select State</option>');
			$.each(states, function(i, data){
				$(elm).append('<option value="' + data.id + '">' + data.name + '</option>');
			});
			$(elm).append('<option value="-1">Other</option>');
			if(jQuery().chosen)
			{
				/*$(elm).trigger("chosen:updated");*/
				$(elm).chosen("destroy").chosen();
			}
		}, "JSON")
	}
	else
	{
		$(elm).html($(html));
		$(elm).append('<option value="-1">Other</option>');
		if(jQuery().chosen)
		{
			/*$(elm).trigger("chosen:updated");*/
			$(elm).chosen("destroy").chosen();
		}
		if(val < 0)
		{
			$(self_elm+"_other").show();
			setTimeout(function() { $(self_elm+"_other").focus(); }, 200);
		}
	}
}
function print_city(self_elm, elm, val)
{
	var html = '<option value="">Select City</option>';
	if(val > 0)
	{
		$(self_elm+"_other").hide().val("");
		$.get(site_url+"pages/get_cities/"+val, function(cities){
			$(elm).html("");
			$(elm).append('<option value="">Select City</option>');
			$.each(cities, function(i, data){
				$(elm).append('<option value="' + data.id + '">' + data.name + '</option>');
			});
			$(elm).append('<option value="-1">Other</option>');
			if(jQuery().chosen)
			{
				/*$(elm).trigger("chosen:updated");*/
				$(elm).chosen("destroy").chosen();
			}
		}, "JSON")
	}
	else
	{
		$(elm).html($(html));
		$(elm).append('<option value="-1">Other</option>');
		if(jQuery().chosen)
		{
			/*$(elm).trigger("chosen:updated");*/
			$(elm).chosen("destroy").chosen();
		}
		if(val < 0)
		{
			$(self_elm+"_other").show();
			setTimeout(function() { $(self_elm+"_other").focus(); }, 200);
		}
	}
}
function other_city(elm, val)
{
	if(val >= 0)
	{
		$(elm+"_other").hide().val("");
	}
	else
	{
		$(elm+"_other").show();
		setTimeout(function() { $(elm+"_other").focus(); }, 200);
	}
}
function CountCharacters(editor_id)
{
    var body = tinymce.get(editor_id).getBody();
    var chars = 0;
    
    if((body.innerText != "" || body.innerText != null) || (body.textContent != "" || body.textContent != null))
    {
	    var content = tinymce.trim(body.innerText || body.textContent);
	    if(content != "" || content != null)
	    {
	    	if(content.length)
	    	{
	    		chars = content.length;
	    	}
	    }
	}
    return chars;
}
function CountWords(editor_id)
{
    var body = tinymce.get(editor_id).getBody();
    var words = 0;
    
    if((body.innerText != "" || body.innerText != null) || (body.textContent != "" || body.textContent != null))
    {
	    var content = tinymce.trim(body.innerText || body.textContent);
	    if(content != "" || content != null)
	    {
	    	if(content.length)
	    	{
	    		words = content.match(/\S+/g).length;
	    	}
	    }
	}
    return words;
}
function print_state_reg(self_elm, elm, val)
{
	$(self_elm+"_other").hide().val("");
	var html = '<option value="">Select State</option>';
	if(val > 0)
	{
		$.get(site_url+"shop/get_states/"+val, function(states){
			$(elm).html("");
			$(elm).append('<option value="">Select State</option>');
			$.each(states, function(i, data){
				$(elm).append('<option value="' + data.id + '">' + data.name + '</option>');
			});
			$(elm).append('<option value="-1">Other</option>');
			if(jQuery().chosen)
			{
				/*$(elm).trigger("chosen:updated");*/
				$(elm).chosen("destroy").chosen();
			}
		}, "JSON")
	}
	else
	{
		$(elm).html($(html));
		$(elm).append('<option value="-1">Other</option>');
		if(jQuery().chosen)
		{
			/*$(elm).trigger("chosen:updated");*/
			$(elm).chosen("destroy").chosen();
		}
		if(val < 0)
		{
			$(self_elm+"_other").show();
			setTimeout(function() { $(self_elm+"_other").focus(); }, 200);
		}
	}
}
function print_city_reg(self_elm, elm, val)
{
	var html = '<option value="0">Select City</option>';
	if(val > 0)
	{
		$(self_elm+"_other").hide().val("");
		$.get(site_url+"shop/get_cities/"+val, function(cities){
			$(elm).html("");
			$(elm).append('<option value="0">Select City</option>');
			$.each(cities, function(i, data){
				$(elm).append('<option value="' + data.id + '">' + data.name + '</option>');
			});
			$(elm).append('<option value="-1">Other</option>');
			if(jQuery().chosen)
			{
				/*$(elm).trigger("chosen:updated");*/
				$(elm).chosen("destroy").chosen();
			}
		}, "JSON")
	}
	else
	{
		$(elm).html($(html));
		$(elm).append('<option value="-1">Other</option>');
		if(jQuery().chosen)
		{
			/*$(elm).trigger("chosen:updated");*/
			$(elm).chosen("destroy").chosen();
		}
		if(val < 0)
		{
			$(self_elm+"_other").show();
			setTimeout(function() { $(self_elm+"_other").focus(); }, 200);
		}
	}
}