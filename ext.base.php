<?php  if ( ! defined('BASEPATH')) { exit('No direct script access allowed'); }

require_once PATH_THIRD.'${project.lowername}/_config.php';

class ${project.capname}_ext
{
	
	public $name = '${project.friendlyname}';
	public $version = '1.0.0';
	public $description = '${project.description}';
	public $settings_exist = 'n';
	public $docs_url = '';
	public $settings = array();
	
	public function __construct($settings='')
	{
		$this->EE =& get_instance();
		$this->settings = $settings;
	}

	public function activate_extension()
	{
		$this->settings = array(
			/*'max_link_length'	=> 18,
			'truncate_cp_links'	=> 'no',
			'use_in_forum'		=> 'no'*/
		);
		
		
		foreach (array(
			'sessions_start',
			'template_fetch_template',
			'template_post_parse',
		) as $hook)
		{
			$this->EE->db->insert('extensions', array(
				'class'		=> __CLASS__,
				'method'	=> $hook.'_action',
				'hook'		=> $hook,
				'settings'	=> serialize($this->settings),
				'priority'	=> 10,
				'version'	=> $this->version,
				'enabled'	=> 'y'
			));
		}
	}
	
	public function sessions_start_action()
	{
	
	}
	
	
		
	public function template_fetch_template_action($row)
	{
		$this->template_data = $row['template_data'];

		$method = '_configure_variables';
		
		if (method_exists($this, $method))
			{
				if ( $this->EE->session->cache(__CLASS__, $method) )
				{
					//don't run this method on subsequent runs
					continue;
				}

				$this->EE->session->set_cache(__CLASS__, $method, TRUE);

				$this->{$method}();
			}
		
	}
	
	
		
	/**
	 * Clean up unparsed variables
	 *
	 * @param string $final_template the template content after parsing
	 * @param bool $sub            whether or not the template is an embed
	 *
	 * @return string the final template content
	 */
	public function template_post_parse_action($final_template, $sub)
	{
		//don't parse this stuff in embeds
		if ($sub)
		{
			return $final_template;
		}

		if ($this->EE->extensions->last_call !== FALSE)
		{
			$final_template = $this->EE->extensions->last_call;
		}

		return $final_template;
	}
	
	
	protected function _configure_variables() {
	// set global vars
	
	//$this->_set_global_var('variable','value');
	}
	
	/**
	 * Set Global Variable
	 *
	 * @param string|array $key       the key/index/tag of the variable, or an array of key/value pairs of multiple variables
	 * @param string $value     the value of the variable, or the tag prefix if the first arg is an array
	 * @param bool $xss_clean whether or not to clean (used for GET/POST/COOKIE arrays)
	 * @param bool $embed     whether or not to add the embed: prefix
	 * @param string $separator change the default colon : separator between prefix and key
	 * @param string $prefix    a prefix to add to the key/index/tag
	 *
	 * @return void
	 */
	protected function _set_global_var($key, $value = '', $xss_clean = FALSE, $embed = FALSE, $separator = ':', $prefix = '')
	{
		if (is_array($key))
		{
			// set default values for this array if defaults exist in the settings;
			if ( ! empty($this->settings['defaults_'.$value]))
			{
				$defaults = preg_split('/[\r\n]+/', $this->settings['defaults_'.$value]);

				foreach ($defaults as $default)
				{
					$default = preg_split('/\s*:\s*/', $default);

					if ( ! isset($key[$default[0]]))
					{
						$key[$default[0]] = isset($default[1]) ? $default[1] : '';
					}
				}
			}

			foreach ($key as $_key => $_value)
			{
				$this->set_global_var($_key, $_value, $xss_clean, $embed, $separator, $value);//we use the second param, $value, as the prefix in the case of an array
			}
		}
		else if ( ! is_array($value) && ! is_object($value))
		{
			$key = ($prefix) ? $prefix.$separator.$key : $key;

			//this way of handling conditionals works best in the EE template parser
			if (is_bool($value))
			{
				$value = ($value) ? '1' : 0;
			}
			else
			{
				$value = ($xss_clean) ? $this->EE->security->xss_clean($value) : $value;
			}

			$this->EE->config->_global_vars[$key] = $value;

			if ($embed)
			{
				$this->EE->config->_global_vars['embed:'.$key] = $value;
			}
		}
	}
	
	public function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
		
		if ($current < '1.0')
		{
			// Update to version 1.0
		}
		
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->update('extensions', array('version' => $this->version));
	}
	
	public function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__);
		$this->EE->db->delete('extensions');
	}
	
}