<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductsController extends Controller
{
    public function create(Request $request){
        try{
            $m = new Product;
            foreach($m->getFillables() as $field){
                $m->$field = $request->$field;
            }
            $m->save();
            return $this->jsonify(1,"Create Success",201);
        } catch(\Exception $e){
            return $this->jsonify(0,$e->getMessage(),400);
        }
    }

    public function update(Request $request,$id){
        $m = Product::where('id',$id)->first();
        if(!$m){
            return $this->jsonify(0,"No User Found");
        }
        try{
            $fillables = $m->getFillables();
            $updated = false;
            foreach($request->all() as $key=>$r){
                if(in_array($key,$fillables)){
                    $m->$key = $r;
                    $updated=true;
                }
            }
            if(!$updated){
                return $this->jsonify(0,"No update");
            }
            $m->save();
            return $this->jsonify(1,"Update Success");
        } catch(\Exception $e){
            return $this->jsonify(0,$e->getMessage(),400);
        }
    }

    public function delete($id){
        $m = Product::where('id',$id)->first();
        if(!$m){return $this->jsonify(0,"Not found");}
        $m->delete();
        return $this->jsonify(1,"Delete Success");
    }
}
