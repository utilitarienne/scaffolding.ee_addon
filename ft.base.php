<?php  if ( ! defined('BASEPATH')) { exit('No direct script access allowed'); }

require_once PATH_THIRD.'${project.lowername}/_config.php';

class ${project.capname}_ft extends EE_Fieldtype {

	public $info = array(
		'name'		=> '${project.friendlyname}',
		'version'	=> '1.0.0'
	);
	
	public function display_field($data)
	{
		$this->EE->javascript->output('
			if (console)
			{
				console.log("javascript!");
			}
		');
		
		return form_input(array(
			'name'	=> $this->field_name,
			'id'	=> $this->field_id,
			'value'	=> $data
		));
	}
	
	public function replace_tag($data, $params = array(), $tagdata = FALSE)
	{
		return $data;
	}
	
	public function display_global_settings()
	{
		$val = array_merge($this->settings, $_POST);
		
		return ''; // HTML FORM ELEMENTS
	}
	
	public function save_global_settings()
	{
		return array_merge($this->settings, $_POST);
	}
	
	public function display_settings($data)
	{
		
	}
	
	public function save_settings($data)
	{
		return array(
			
		);
	}
	
	function install()
	{
		return array(
			
		);
	}
	
}