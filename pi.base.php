<?php  if ( ! defined('BASEPATH')) { exit('No direct script access allowed'); }

require_once PATH_THIRD.'${project.lowername}/_config.php';

$plugin_info = array(
	'pi_name'		=> '${project.friendlyname}',
	'pi_version'	=> '1.0.0',
	'pi_author'		=> '',
	'pi_author_url'	=> '',
	'pi_description'=> '${project.description}',
	'pi_usage'		=> "
		Usage directions go here.
		
		"
);


class ${project.capname} {

	public $return_data;
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
	}

	
}
