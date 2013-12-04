<?php  if ( ! defined('BASEPATH')) { exit('No direct script access allowed'); }

require_once PATH_THIRD.'${project.lowername}/_config.php';

class ${project.capname}
{
	
	public function __construct()
	{
		$this->EE =& get_instance();
	}
	
	public function entries()
	{
		$original_view_path = $this->EE->load->_ci_view_path;
		$this->EE->load->_ci_view_path = PATH_THIRD.'${project.lowername}/views/';
		
		// $this->EE->load->view();
		
		$this->EE->load->_ci_view_path = $original_view_path;
	}
	
}