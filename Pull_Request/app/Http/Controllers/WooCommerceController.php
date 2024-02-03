<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

ini_set('max_execution_time', '300');

class WooCommerceController extends Controller
{

    

    public function get14DaysPullRequests()
    {
        $n = 2;
        $mydate = date("Y-m-d", strtotime("-2 week"));
        $mytime = date("H:i:s");
        $formattedDate = $mydate . "T" . $mytime . "Z";
        $headers = [
            "Accept:application/vnd.github+json", 
            "User-Agent: GhadeerZahwe",
            "authorization: Bearer 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"
        ];
        $filename = storage_path("app/1-old-pull-requests.txt");
        for ($i = 1; $i < $n; $i++) {
    
            $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls?&per_page=100&page=" . $i;
    
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
            
            if (!is_null($pull_requests)) {
                foreach ($pull_requests as $pull_request) {
                    if ($pull_request->created_at < $formattedDate) {
                        $output = $pull_request->number . " " .$pull_request->title . " " . $pull_request->created_at . "\n";
                        // Append the output to the file
                        file_put_contents($filename, $output, FILE_APPEND);
                    }
                }
        
                if (count($pull_requests) === 100) {
                    ++$n;
                }
            } else {
                // Handle the case where $pull_requests is null
                echo "Error fetching pull requests.\n";
            }
        }
        echo "Done". $output;
    }
    
    public function getRRPullRequests()
    {
        $n = 2;
        $headers = [
            "Accept:application/vnd.github+json", 
            "User-Agent: GhadeerZahwe",
            "authorization: Bearer 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"
        ];
        $filename = storage_path("app/2-review-required-pull-requests.txt");
        
        for ($i = 1; $i < $n; $i++) {
            $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls?&per_page=100&page=" . $i;
    
            $curl = curl_init($url);
    
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
            // For debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $resp = curl_exec($curl);
    
            curl_close($curl);
            $pull_requests = json_decode($resp, false);
    
            foreach ($pull_requests as $pull_request) {
                if (!empty($pull_request->requested_reviewers) || !empty($pull_request->requested_teams)) {
                    $output = $pull_request->number . " " . $pull_request->title . " " . $pull_request->created_at . "\n";
                    // Append the output to the file
                    file_put_contents($filename, $output, FILE_APPEND);
                }
            }
    
            if (count($pull_requests) === 100) {
                ++$n;
            }
        }
        echo "Done"." ". $output;

    }
    


 public function getSuccessPullRequests()
{
    $headers = [
        "Accept:application/vnd.github+json",
        "User-Agent: GhadeerZahwe",
        "authorization: Bearer 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"
    ];
    $n = 2;

    for ($i = 1; $i < $n; $i++) {
        
        $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls?&per_page=100&page=" . $i;

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // For debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $resp = curl_exec($curl);

        curl_close($curl);
        $pull_requests = json_decode($resp, true); // Set the second argument to true to decode JSON as an associative array

        // Check if $pull_requests is an array
        if (is_array($pull_requests)) {
            foreach ($pull_requests as $pull_request) {
                $url2 = "https://api.github.com/repos/woocommerce/woocommerce/commits/" . $pull_request['head']['sha'] . "/status";

                $curl2 = curl_init($url2);

                curl_setopt($curl2, CURLOPT_URL, $url2);
                curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);

                // For debug only!
                curl_setopt($curl2, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl2, CURLOPT_HTTPHEADER, $headers);
                $resp2 = curl_exec($curl2);

                curl_close($curl2);
                $pull_requests2 = json_decode($resp2, true);

                if ($pull_requests2['state'] == "success") {
                    echo   $pull_request['number'] ." " .$pull_request['title'] ." " . $pull_request['created_at'] . "\n";
                }
            }
        } else {
            // Handle the case where $pull_requests is not an array (e.g., an error occurred during JSON decoding)
            echo "Error fetching pull requests.\n";
        }

        if (count($pull_requests) === 100) {
            ++$n;
        }
    }
}

    public function getUnassignedPullRequests()
    {
        $headers = [
            "Accept:application/vnd.github+json", "User-Agent: GhadeerZahwe",
            "authorization: Bearer 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"
        ];
        $n = 2;


        for ($i = 1; $i < $n; $i++) {

            $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls?&per_page=100&page=" . $i;



            $curl = curl_init($url);


            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            //for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $resp = curl_exec($curl);

            curl_close($curl);
            $pull_requests = "";
            $pull_requests = json_decode($resp, false);


            for ($j = 0; $j < count($pull_requests); $j++) {
                if ($pull_requests[$j]->requested_reviewers == [] && $pull_requests[$j]->requested_teams == []) {
                    echo $pull_requests[$j]->number . " ". $pull_requests[$j]->title. " ". $pull_requests[$j]->created_at. "\n" ;
                
                }
            }
            if (count($pull_requests) === 100) {
                ++$n;
            }
        }
    }
}