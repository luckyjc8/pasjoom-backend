<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function bayarTest(){
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
			    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="'.env("MIDTRANS_CLIENT_KEY").'"></script>
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