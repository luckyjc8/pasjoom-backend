<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function jsonify($status,$msg=null,$code=200,$data=null){
    	$res = [
    		"status" => $status ? "OK" : "ERROR",
    		
    	];
    	if($msg){$res["msg"] = $msg;}
    	if($data){$res["data"] = $data;}
    	return response($res,$code);
    }
}
