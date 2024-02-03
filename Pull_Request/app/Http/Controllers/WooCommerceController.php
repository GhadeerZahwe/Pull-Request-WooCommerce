<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WooCommerceController extends Controller
{
    public function getPullRequests()
    {
        $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls";
        $headers = ["Accept:application/vnd.github+json",
        "User-Agent: GhadeerZahwe"];
        $curl = curl_init($url);


        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $resp = curl_exec($curl);

        curl_close($curl);

        $pull_requests = json_decode($resp, false);

       // for($i=0;$i<count($pull_requests);$i++){
        //     print_r($pull_requests[$i]->url . "\n");
        // }

        $mydate=date("Y-m-d", strtotime("-2 week"));
        print_r($mydate); 

    }
}