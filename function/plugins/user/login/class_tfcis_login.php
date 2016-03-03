<?php

class class_tfcis_login{
	
	static $url = "http://tfcis-dev.cf/login/";
	
	public static function status(){
		@session_start();
		if(@$_GET["logout"]==true){
			unset($_SESSION["user"]);
			header("Location:".self::geturl("logout"));
		}else if(isset($_SESSION["user"])){
			return (object)array( "login"=>true, "data"=>$_SESSION["user"], "url"=>"http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?logout=true" );
		}else if(isset($_GET["cookie"])){
			$cookie = $_GET["cookie"];
			$data = file_get_contents(self::$url."api/user.php?cookie=".$cookie);
			$data = json_decode($data);
			if($data->status === "success"){
				$_SESSION["user"] = $data->result;
				header("Location:".$_SERVER['PHP_SELF']);
			}else if($data->status === "error"){
				if($data->result === "notfound"){
					// cookie not found
				}else{
					throw new exception("Login API returned an error status");
				}
			}else{
				throw new exception("Unexpected API result");
			}
		}else{
			return (object)array( "login"=>false, "data"=>null, "url"=>self::geturl("login") );;
		}
	}

	public static function getinfobyaccount($uid){
		$data = file_get_contents(self::$url."api/getinfo.php?account=".$uid);
		$data = json_decode($data);
		if($data->status === "success"){
			return $data->result;
		}else if($data->status === "error"){
			if($data->result === "notfound"){
				return false;
			}else{
				throw new exception("Login API returned an error status");
			}
		}else{
			throw new exception("Unexpected API result");
		}
	}

	public static function getinfobyid($uid){
		$data = file_get_contents(self::$url."api/getinfo.php?uid=".$uid);
		$data = json_decode($data);
		if($data->status === "success"){
			return $data->result;
		}else if($data->status === "error"){
			if($data->result === "notfound"){
				// cookie not found
			}else{
				throw new exception("Login API returned an error status");
			}
		}else{
			throw new exception("Unexpected API result");
		}
	}
	
	private static function geturl($page){
		$current_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		return self::$url . "$page.php?continue=" . urlencode($current_url);
	}
	
}