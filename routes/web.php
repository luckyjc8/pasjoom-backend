<?php

use Kavist\RajaOngkir\RajaOngkir;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('test',function(){

});

$router->post('register', 'AuthController@register');
$router->post('login', 'AuthController@login');

//$router->group(['middleware' => 'auth'], function () use ($router) {
	$router->get('/user/{id}','UsersController@getInfo');

	$router->get('/',function(){
		return '
			<a href="/bayar"><h2>Test Bayar</h2></a>
			<a href="/ongkir"><h2>Test Cek Ongkir</h2></a>
		';
	});

	$router->get('/getProvinces',function(){
		$rajaOngkir = new RajaOngkir(env('RAJA_ONGKIR_KEY'));

		$provinces = $rajaOngkir->provinsi()->all();
		return json_encode($provinces);
	});

	$router->get('/getCities/{id}',function($id){
		$rajaOngkir = new RajaOngkir(env('RAJA_ONGKIR_KEY'));

		$cities = $rajaOngkir->kota()->dariProvinsi($id)->get();
		return json_encode($cities);
	});
	$router->post('/calcOngkir',function(Request $request){
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

	$router->get('ongkir',function(){
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

	$router->get('/bayar', function(){

		$params = array(
		    'transaction_details' => array(
		        'order_id' => rand(),
		        'gross_amount' => 10000,
		    )
		);

		$snapToken = \Midtrans\Snap::getSnapToken($params);

		return ('
			  <body>
			  	<a href="/"><h1>Balik</h1></a>
			    <button id="pay-button">Pay!</button>
			    <pre><div id="result-json">JSON result will appear here after payment:<br></div></pre> 

			<!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input your client key in "data-client-key" -->
			    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-g3GQZfxGaUx4jVzT"></script>
			    <script type="text/javascript">
			      document.getElementById("pay-button").onclick = function(){
			        // SnapToken acquired from previous step
			        snap.pay("'.$snapToken.'", {
			          // Optional
			          onSuccess: function(result){
			            /* You may add your own js here, this is just example */ document.getElementById("result-json").innerHTML += JSON.stringify(result, null, 2);
			          },
			          // Optional
			          onPending: function(result){
			            /* You may add your own js here, this is just example */ document.getElementById("result-json").innerHTML += JSON.stringify(result, null, 2);
			          },
			          // Optional
			          onError: function(result){
			            /* You may add your own js here, this is just example */ document.getElementById("result-json").innerHTML += JSON.stringify(result, null, 2);
			          }
			        });
			      };
			    </script>
			  </body>
		');
	});

	$router->post('/product/create','ProductsController@create');
	$router->post('/product/update/{id}','ProductsController@update');
	$router->post('/product/delete/{id}','ProductsController@delete');

	$router->post('/transaction/create','TransactionController@create');
	$router->post('/transaction/update/{id}','TransactionController@update');
	$router->post('/transaction/delete/{id}','TransactionController@delete');

	$router->post('/product_images/create','ProductImagesController@create');
	$router->post('/product_images/update/{id}','ProductImagesController@update');
	$router->post('/product_images/delete/{id}','ProductImagesController@delete');
//});