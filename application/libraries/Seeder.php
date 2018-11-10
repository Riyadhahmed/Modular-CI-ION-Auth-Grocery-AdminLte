<?php
/**
 * 『CodeIgniter徹底入門』のサンプルアプリケーションをCodeIgniter 3.0にアップデート
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    BSD 3-Clause License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-tettei-apps
 */

class Seeder
{
	private $CI;
	protected $db;
	protected $dbforge;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->dbforge();
		$this->db = $this->CI->db;
		$this->dbforge = $this->CI->dbforge;
	}

	/**
	 * Run another seeder
	 * 
	 * @param string $seeder Seeder classname
	 */
	public function call($seeder)
	{
		$file = APPPATH . 'database/seeds/' . $seeder . '.php';
		require_once $file;
		$obj = new $seeder;
		$obj->run();
	}

	public function __get($property)
	{
		return $this->CI->$property;
	}
}
