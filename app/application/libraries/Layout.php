<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Layout
{

    var $obj;
    var $layout;
	private $title_for_layout = '';
	private $title_separator = ' | ';

    function __construct()
    {
        $this->obj =& get_instance();
        $this->layout = 'layout_main';
    }

    function setLayout($layout)
    {
		$this->layout = $layout;
    }
	
	public function set_title($title)
	{
		$this->title_for_layout = $title;
	} 

    function view($view, $data=null, $return=false)
    {
		$separated_title_for_layout = ($this->title_for_layout != "") ? $this->title_separator . $this->title_for_layout : "";
	
        $loadedData = array();
        $loadedData['content_for_layout'] = $this->obj->load->view($view,$data,true);
        $loadedData['title_for_layout'] = $separated_title_for_layout;

        if($return)
        {
            $output = $this->obj->load->view('layouts/' . $this->layout, $loadedData, true);
            return $output;
        }
        else
        {
            $this->obj->load->view('layouts/' . $this->layout, $loadedData, false);
        }
    }
}