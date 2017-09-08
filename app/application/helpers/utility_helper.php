<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('asset_url()')){
	function asset_url($arg = ''){
		return base_url().'assets/'.$arg;
	}
}
if (!function_exists('admin_url()')){
	function admin_url($arg = ''){
		return base_url().'admin/'.$arg;
	}
}
if (!function_exists('active_link')){
    function active_link($controller, $action = null){
		$CI =& get_instance();
		$class = $CI->router->fetch_class();
		$method = $CI->router->fetch_method();
		if($action == null){
			return ($class == $controller) ? 'active' : '';
		}else{
			return ($class == $controller && $method == $action) ? 'active' : '';
		}
    }
}
if (!function_exists('page_class')){
    function page_class(){
		$CI =& get_instance();
		$class = $CI->router->fetch_class();
		$method = $CI->router->fetch_method();
		return strtolower($class).'-'.strtolower($method);
    }
}
function seturl_param($key, $val){
	$CI =&get_instance();
	$params = $CI->data['uri_params'];
	$params[$key] = $val;
	return current_url() . '?' . http_build_query($params);
}
function icon_link($icon, $url, $title = '', $class='', $attr = array()){
	$myattr = "";
	foreach($attr as $k=>$v){
		$myattr .= "$k='$v' ";
	}
	if($url != ''){
		$myurl = 'href="'.base_url($url).'"';
	}else{
		$myurl = 'href="javascript:void:(0);"';
	}
	echo "<a class='icon-link $icon $class' $myattr title='$title' $myurl><svg class='icon $icon'><use xlink:href='". base_url("assets/images/icons.svg") ."#$icon'></use></svg></a>";
};
function limit_text($text, $limit, $link = '') {
	if(str_word_count($text, 0) > $limit) {
		$words = str_word_count($text, 2);
		$pos = array_keys($words);
		$text = substr($text, 0, $pos[$limit]);
		if($link != '') $text .= ' <a href="'. $link .'" class="link">MORE &#10095;</a>';
	}
	return $text;
}
function show_countries_nologin($countries, $selected = ""){
	echo '<select class="select chosen" onchange="print_state_reg(\'#country\',\'#state\',this.value);" id="country" name="country">';
		echo '<option value="">Select Country</option>';
		foreach($countries as $country){
			$sel = "";
			if($selected == $country->id){
				$sel = "selected";
			}
			echo "<option $sel value='{$country->id}'>{$country->name}</option>";
		}
		echo '<option value="-1">Other</option>';
	echo '</select>';
}
function show_countries($countries, $selected = ""){
	echo '<select class="select chosen" onchange="print_state(\'#country\',\'#state\',this.value);" id="country" name="country">';
		echo '<option value="">Select Country</option>';
		foreach($countries as $country){
			$sel = "";
			if($selected == $country->id){
				$sel = "selected";
			}
			echo "<option $sel value='{$country->id}'>{$country->name}</option>";
		}
		echo '<option value="-1">Other</option>';
	echo '</select>';
}
function show_states_nologin($states = array(), $selected = "", $get_cities = true){
	if($get_cities)
	{
		echo '<select class="select chosen" id="state" name="state" onchange="print_city_reg(\'#state\',\'#city\',this.value);">';
	}
	else
	{
		echo '<select class="select chosen" id="state" name="state">';
	}
		echo '<option value="">Select State</option>';
		if(!empty($states)){
			foreach($states as $state){
				$sel = "";
				if($selected == $state->id){
					$sel = "selected";
				}
				echo "<option $sel value='{$state->id}'>{$state->name}</option>";
			}
		}
		echo '<option value="-1">Other</option>';
	echo '</select>';
}
function show_states($states = array(), $selected = "", $get_cities = true){
	if($get_cities)
	{
		echo '<select class="select chosen" id="state" name="state" onchange="print_city(\'#state\',\'#city\',this.value);">';
	}
	else
	{
		echo '<select class="select chosen" id="state" name="state">';
	}
		echo '<option value="">Select State</option>';
		if(!empty($states)){
			foreach($states as $state){
				$sel = "";
				if($selected == $state->id){
					$sel = "selected";
				}
				echo "<option $sel value='{$state->id}'>{$state->name}</option>";
			}
		}
		echo '<option value="-1">Other</option>';
	echo '</select>';
}
function show_cities_nologin($citys = array(), $selected = ""){
	echo '<select class="select chosen" id="city" name="city" onchange="other_city(\'#city\',this.value);">';
		echo '<option value="">Select City</option>';
		if(!empty($citys)){
			foreach($citys as $city){
				$sel = "";
				if($selected == $city->id){
					$sel = "selected";
				}
				echo "<option $sel value='{$city->id}'>{$city->name}</option>";
			}
		}
		echo '<option value="-1">Other</option>';
	echo '</select>';
}
function show_cities($citys = array(), $selected = ""){
	echo '<select class="select chosen" id="city" name="city" onchange="other_city(\'#city\',this.value);">';
		echo '<option value="">Select City</option>';
		if(!empty($citys)){
			foreach($citys as $city){
				$sel = "";
				if($selected == $city->id){
					$sel = "selected";
				}
				echo "<option $sel value='{$city->id}'>{$city->name}</option>";
			}
		}
		echo '<option value="-1">Other</option>';
	echo '</select>';
}
function get_lat_long($address){

    $address = urlencode(trim($address));

    $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=");
    $json = json_decode($json);

    if(!empty($json->{'results'}))
    {
	    $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
	    $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
	    $address_componentsArr = $json->{'results'}[0]->{'address_components'};

	    $country = "";
	    $city = "";
	    foreach ($address_componentsArr as $key => $dataArr)
	    {
	       $type = strtolower(trim($dataArr->{'types'}[0]));
	       if($type == 'country')
	       {
	            $country = $dataArr->{'long_name'};
	       }
	       if($type == 'administrative_area_level_1')
	       {
	            $city = $dataArr->{'long_name'};
	       }
	    }
	}
	else
	{
		$lat 		= "";
		$long 		= "";
		$city 		= "";
		$country 	= "";
	}

    return array("lat" => $lat, "long" => $long, "city" => $city, "country" => $country);
}
function trim_data($data)
{
	if(is_array($data) && count($data) > 0)
	{
		foreach ($data as $key => $value)
		{
			if(is_array($value) && count($value) > 0)
			{
				$data[$key] = trim_data($value);
			}
			else
			{
				$data[$key] = trim($value);
			}
		}
	}
	else if(!empty($data))
	{
		$data = trim($data);
	}
	return $data;
}