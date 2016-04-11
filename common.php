<?php 
	function dump($para){
		ob_clean();
		var_dump($para);
		die();

	}

	function app_init(){
		global $project_dir;
		$config = require 'config.php';
		set_time_limit(0);
		date_default_timezone_set('PRC');
		$project_dir = $config['project_dir'];

		define("PRO_DIR", $project_dir);

		// error_reporting(1);
		chdir($project_dir);
	}



 ?>