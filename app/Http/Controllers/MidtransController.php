<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MidtransController extends Controller
{
	public function getSnap(Request $request){
		$items = array(
	    array(
	      'id'       => 'item1',
	      'price'    => 100000,
	      'quantity' => 1,
	      'name'     => 'Adidas f50'
	    ),
	    array(
	      'id'       => 'item2',
	      'price'    => 50000,
	      'quantity' => 2,
	      'name'     => 'Nike N90'
	    ));
		$name = explode(' ',$request->name);	
		$billing_address = array(
            'first_name'        => $name[0],
            'last_name'         => end($name),
            'address'           => $request->address,
            'city'                  => $request->city,
            'postal_code'   => $request->postal_code,
            'country_code'  => 'IDN'
        );
        $customer_details = array(
            'first_name'            => "Andri",
            'last_name'             => "Setiawan",
            'email'                     => "andrisetiawan@asdasd.com",
            'phone'                     => "081322311801",
            'billing_address' => $billing_address
        );

        $params = array(
		    'transaction_details' => array(
		        'order_id' => rand(),
		        'gross_amount' => 10,
		        'customer_details' => $customer_details
		    )
		);

		$snapToken = \Midtrans\Snap::getSnapToken($params);
	}

    public function bayarTest(){
    	$billing_address = array(
            'first_name'        => "Andri",
            'last_name'         => "Setiawan",
            'address'           => "Karet Belakang 15A, Setiabudi.",
            'city'                  => "Jakarta",
            'postal_code'   => "51161",
            'phone'                 => "081322311801",
            'country_code'  => 'IDN'
        );
        $shipping_address = array(
            'first_name'    => "John",
            'last_name'     => "Watson",
            'address'       => "Bakerstreet 221B.",
            'city'              => "Jakarta",
            'postal_code' => "51162",
            'phone'             => "081322311801",
            'country_code'=> 'IDN'
        );
    	$customer_details = array(
            'first_name'            => "Andri",
            'last_name'             => "Setiawan",
            'email'                     => "andrisetiawan@asdasd.com",
            'phone'                     => "081322311801",
            'billing_address' => $billing_address,
            'shipping_address'=> $shipping_address
        );

		$params = array(
		    'transaction_details' => array(
		        'order_id' => rand(),
		        'gross_amount' => 10,
		        'customer_details' => $customer_details
		    )
		);

		$snapToken = \Midtrans\Snap::getSnapToken($params);

		return ('
			  <body>
			  	<a href="/"><h1>Balik</h1></a>
			    <button id="pay-button">Pay!</button>
			    <pre><div id="result-json">JSON result will appear here after payment:<br></div></pre> 

			<!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input your client key in "data-client-key" -->
			    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="'.env("MIDTRANS_CLIENT_KEY").'"></script>
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
	}
}