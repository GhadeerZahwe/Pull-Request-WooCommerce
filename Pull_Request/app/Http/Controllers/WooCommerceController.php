<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WooCommerceController extends Controller
{
    public function getPullRequests()
    {
        // Increase the maximum execution time limit
        set_time_limit(40);

        for ($i = 1; $i <= 3; $i++) {
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

            if (is_array($pull_requests)) {
                foreach ($pull_requests as $pull_request) {
                    echo $pull_request->url . "\n";
                }
                echo "Number of pull requests: " . count($pull_requests) . "\n";
            } else {
                echo "Error decoding JSON response.\n";
            }
        }
    }
}
