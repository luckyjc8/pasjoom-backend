<?php

namespace App\Http\Controllers;

use Kavist\RajaOngkir\RajaOngkir;
use Illuminate\Http\Request;

class OngkirController extends Controller
{
    public function jsonify($status,$msg,$code=200,$data=null){
    	$res = [
    		"status" => $status ? "OK" : "ERROR",
    		"msg" => $msg
    	];
    	if($data){$res["data"] = $data;}
    	return response($res,$code);
    }

    public function getProvinces(){
		$rajaOngkir = new RajaOngkir(env('RAJA_ONGKIR_KEY'));

		$provinces = $rajaOngkir->provinsi()->all();
		return json_encode($provinces);
	});

	public function getCities($id){
		$rajaOngkir = new RajaOngkir(env('RAJA_ONGKIR_KEY'));

		$cities = $rajaOngkir->kota()->dariProvinsi($id)->get();
		return json_encode($cities);
	});
	
	public function calc(Request $request){
		$rajaOngkir = new RajaOngkir(env('RAJA_ONGKIR_KEY'));

		$res = $rajaOngkir->ongkir([
			'origin' => $request->origin,
			'destination' => $request->destination,
			'weight' => $request->weight,
			'courier' => $request->courier
		]);

		$result = "OKE : ".$res->get()[0]['costs'][0]['cost'][0]['value'].", REG : ".$res->get()[0]['costs'][1]['cost'][0]['value'];

		return $result;
	});

	public function ongkirTest(){
		return '
			<body>
				<a href="/"><h1>Balik</h1></a>
				<h1> Test Hitung Ongkir </h1>
				<label> weight (gram) : </label>
				<input id="weight" type="text" value=1>

				<h2>Courier</h2>
				<select id="courier">
					<option>jne</option>
					<option>tiki</option>
					<option>pos</option>
				</select>
				<h2>From:</h2>
				<select id="prov">
				</select>
				<select id="city" name="origin_city">
				</select>
				

				<h2>To:</h2>
				<select id="prov2" name="destination_prov">
				</select>
				<select id="city2" name="destination_city">
				</select><br><br>

				<button id="calc">Calculate</button>
				
				<h2> Result : <span id="res"></span></h2>


			</body>

			<script
				src="https://code.jquery.com/jquery-3.4.1.min.js"
				integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
				crossorigin="anonymous">
			</script>

			<script>
				$.ajax({url: "/getProvinces", success: function(result){
					res = ""
				    JSON.parse(result).forEach(function(item){
				    	res += "<option value="+item.province_id+">"+item.province+"</option>"
				    })

				    $("#prov").html(res)
				    $("#prov2").html(res)
				}});

				$.ajax({url: "/getCities/1", success: function(result){
					res = ""
				    JSON.parse(result).forEach(function(item){
				    	res += "<option value="+item.city_id+">"+item.city_name+"</option>"
				    })

				    $("#city").html(res)
				    $("#city2").html(res)
				}});

				$("#prov").change(function(){
					$.ajax({url: "/getCities/"+$("#prov").val(), success: function(result){
						res = ""
					    JSON.parse(result).forEach(function(item){
					    	res += "<option value="+item.city_id+">"+item.city_name+"</option>"
					    })

					    $("#city").html(res)
					}});
				})

				$("#prov2").change(function(){
					$.ajax({url: "/getCities/"+$("#prov2").val(), success: function(result){
						res = ""
					    JSON.parse(result).forEach(function(item){
					    	res += "<option value="+item.city_id+">"+item.city_name+"</option>"
					    })

					    $("#city2").html(res)
					}});
				})

				$("#calc").click(function(){
					formdata = {
						origin : $("#city").val(),
						destination : $("#city2").val(),
						weight : $("#weight").val(),
						courier : $("#courier").val(),
					}
					console.log(formdata)
					$.ajax({
					  type: "POST",
					  url: "/calcOngkir",
					  data: formdata,
					  success: function(res){
					  	$("#res").html(res)
					  },
					  error: function(err){
					  }
					});
				})
			</script>
			';
	});
}
