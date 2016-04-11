<?php 


/**
 * hg运行类
 */
class App{
	
	private $msg;
	private $dist_dir;
	public function run(){

		$params = $this->init();


		if($params){
			$cmd = $this->cmd_package($params);
			
			$sys_re = $this->cmd_run($cmd);

			$rs = $this->cmd_analyse($sys_re);
			$this->genZip();

			if($rs){
				$this->showMsg("New patch gened: {$this->dist_dir}.",true);
			}else{
				$this->showMsg("No file transported.",false);
			}			
		}

	}
	// 程序初始化，参数检查
	private function init(){
		
		if($_SERVER["REQUEST_METHOD"] != "POST"){
			
			return false;

		}else{

			$begin_ver = trim($_REQUEST['begin_ver']);
			$end_ver = trim($_REQUEST['end_ver']);
			$branches_arr = isset($_REQUEST['branch_name'])?$_REQUEST['branch_name']:[];

			// if(($begin_ver == "" && $end_ver == "") || count($branches_arr)<=0){
			// 	$msg = "请填写版本号，或选择分支";
			// 	$this->msg = "<span style='color:#FF3300;'>{$msg}</span>";
			// 	echo $this->msg;die(); 
			// }

			$this->m_model = $_REQUEST['m_model'];

			$date_time = date("Ymd-His");
			
			$this->dist_dir = __DIR__."\patch\patch_".$date_time."\htDoc" ;

			if(!isset($_REQUEST["extraction"])){
				$this->showMsg("请选择一种提取方式");
			}

			$extraction = $_REQUEST["extraction"];

			// dump($_REQUEST);
			
			$params = [];
			if(isset($extraction['branch'])&&(!isset($extraction['version']))&&(count($_REQUEST['branch_name']) == 0)){
				$this->showMsg("请选择要提取的分支");
			}

			if(in_array('branch',$extraction)&&(count($_REQUEST['branch_name']) >0)){

				$branches = $_REQUEST['branch_name'];
				$params['branches'] = $branches;
			}

			if(in_array('version',$extraction)){
				$params['version']["begin_ver"] = $_REQUEST['begin_ver'];
				$params['version']["end_ver"] = $_REQUEST['end_ver'];
			}

			return $params;

			}


	}

	// hg命令拼装
	private function cmd_package($params){
		$cmd = "hg log ";

		
		// 拼装分支条件
		$branch_cmd = "";
		if(isset($params['branches'])){
			$branches = $params['branches'];
			

			foreach ($branches as $k => $v) {
				$branch_cmd .= " -b {$v} ";
			}
		}
		
		// 拼装版本号条件
		$version_cmd = "";
		if(isset($params['version'])){
			$version = $params['version'];
			$begin_ver = $version['begin_ver'];
			$end_ver   = isset($version['end_ver'])?$version['end_ver']:0; 
			if($end_ver !== 0){
				$version_cmd = " -r {$begin_ver}:{$end_ver} ";
			}else{
				$version_cmd = " -r {$begin_ver} ";
			}
		}
		
		$model = " -M --stat";

		if($this->m_model == "lowerm"){
			$model = " -m --stat";
		}else{

		}

		$cmd = $cmd.$version_cmd.$branch_cmd.$model;		
		
		return $cmd;
	}

	// hg指令执行
	private function cmd_run($cmd){
		exec($cmd,$sys_re);
		return $sys_re; 
	}

	// hg返回结果分析执行
	private function cmd_analyse($sys_re){
		global $project_dir;
		foreach ($sys_re as $k => $v) {
			if(!strpos($v,"|")){
				unset($sys_re[$k]);
			}else{
				$tmp_arr = explode("|",$v);
				$sys_re[$k] = $project_dir."/".trim(array_shift($tmp_arr));		
			}

		}


		$file_arr = $sys_re;
		$file_arr = array_unique($file_arr);
		
		$igonre = file(".hgignore");

		foreach ($igonre  as &$i) {
			$i = str_replace("\\","/", $i);
			$i = trim($project_dir."/".$i);
		}


		$file_arr = array_diff($file_arr,$igonre);


		foreach ($file_arr as $k => $v) {
			$v = trim($v);
			if(is_file($v)){
				$dist_file = str_replace($project_dir,$this->dist_dir,$v);

				if(is_file($dist_file)){
					unlink($dist_file);
				}

				$_dir = dirname($dist_file);

				if(!is_dir($_dir)){
					$this -> mkdirs($_dir);
				}

				copy($v,$dist_file);
			}
		}
		return $dist_file;

	}

	private function mkDirs($dir){
	    if(!is_dir($dir)){
	        if(!$this->mkDirs(dirname($dir))){
	            return false;
	        }
	        if(!mkdir($dir,0777)){
	            return false;
	        }
	    }
	    return true;
	}

	// 同时生成zip包
	public function genZip(){
		$zip_dir = dirname($this->dist_dir);
		$zipName = $zip_dir.".zip"; 
		$cmd = __DIR__."\\7-Zip\\7z.exe a -tzip {$zipName} {$zip_dir}\\*";

		exec($cmd);

	}

	private function showMsg($msg,$success=false){
		if($success){
			$this->msg = "<span style='color:#009900;'>{$msg}</span>";
		}else{
			$this->msg = "<span style='color:#FF3300;'>{$msg}</span>";
		}
		echo $this->msg;die(); 
	}


}
?>	
