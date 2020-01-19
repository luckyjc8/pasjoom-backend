<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    public function getInfo($id){
    	$user = User::where('id',$id)->first();
    	return $this->jsonify(1,"Sucess",200,$user);
    }
}
