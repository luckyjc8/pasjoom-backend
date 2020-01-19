<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function jsonify($status,$msg,$code=200,$data=null){
    	$res = [
    		"status" => $status ? "OK" : "ERROR",
    		"msg" => $msg
    	];
    	if($data){$res["data"] = $data;}
    	return response($res,$code);
    }
}
