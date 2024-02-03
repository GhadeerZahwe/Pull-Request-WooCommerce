<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
ini_set('max_execution_time', '300');

class WooCommerceController extends Controller
{
    public function getPullRequests()
    {
        // Increase the maximum execution time limit
        $n = 2;
        $mydate = date("Y-m-d", strtotime("-2 week"));
        $mytime = date("H:i:s");
        $formattedDate = $mydate . "T" . $mytime . "Z";
        for ($i = 1; $i < $n; $i++) {
            $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls?per_page=100&page=" . $i;
            $headers = [
                "Accept:application/vnd.github+json", 
                "User-Agent: GhadeerZahwe"
            ];
            $curl = curl_init($url);

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $resp = curl_exec($curl);
            curl_close($curl);

            $pull_requests = json_decode($resp, false);

            for ($j = 0; $j < count($pull_requests); $j++) {
                if ($pull_requests[$j]->created_at < $formattedDate) {
                    echo $pull_requests[$j]->created_at . "\n";
                }
            }
            if (count($pull_requests) === 100) {
                ++$n;
            }
        }
    }
}
