<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

ini_set('max_execution_time', '300');

class WooCommerceController extends Controller
{

    

    public function getOldPullRequests()
    {
        $n = 2;
        $mydate = date("Y-m-d", strtotime("-2 week"));
        $mytime = date("H:i:s");
        $formattedDate = $mydate . "T" . $mytime . "Z";
        $headers = [
            "Accept:application/vnd.github+json", 
            "User-Agent: GhadeerZahwe",
            "authorization: Bearer " . env("TOKEN")
        ];
        $filename = storage_path("app/1-old-pull-requests.txt");
        for ($i = 1; $i < $n; $i++) {
    
            $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls?&per_page=100&page=" . $i;
    
            $curl = curl_init($url);
    
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
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
                        file_put_contents($filename, $output, FILE_APPEND);
                    }
                }
        
                if (count($pull_requests) === 100) {
                    ++$n;
                }
            } else {
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
            "authorization: Bearer " . env("TOKEN")
        ];
        $filename = storage_path("app/2-review-required-pull-requests.txt");
        
        for ($i = 1; $i < $n; $i++) {
            $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls?&per_page=100&page=" . $i;
    
            $curl = curl_init($url);
    
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $resp = curl_exec($curl);
    
            curl_close($curl);
            $pull_requests = json_decode($resp, false);
    
            foreach ($pull_requests as $pull_request) {
                if (!empty($pull_request->requested_reviewers) || !empty($pull_request->requested_teams)) {
                    $output = $pull_request->number . " " . $pull_request->title . " " . $pull_request->created_at . "\n";
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
            "authorization: Bearer " . env("TOKEN")
        ];
        $n = 2;
    
        $filename = storage_path("app/3-Successful-PRs.txt");
    
        for ($i = 1; $i < $n; $i++) {
            
            $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls?&per_page=100&page=" . $i;
    
            $curl = curl_init($url);
    
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $resp = curl_exec($curl);
    
            curl_close($curl);
            $pull_requests = json_decode($resp, true);
    
            if (is_array($pull_requests)) {
                foreach ($pull_requests as $pull_request) {
                    $url2 = "https://api.github.com/repos/woocommerce/woocommerce/commits/" . $pull_request['head']['sha'] . "/status";
    
                    $curl2 = curl_init($url2);
    
                    curl_setopt($curl2, CURLOPT_URL, $url2);
                    curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
    
                    curl_setopt($curl2, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl2, CURLOPT_HTTPHEADER, $headers);
                    $resp2 = curl_exec($curl2);
    
                    curl_close($curl2);
                    $pull_requests2 = json_decode($resp2, true);
    
                    if ($pull_requests2['state'] == "success") {
                        $output = $pull_request['number'] . " " . $pull_request['title'] . " " . $pull_request['created_at'] . "\n";
                        
                        file_put_contents($filename, $output, FILE_APPEND);
                        
                      
                    }
                }
            } else {
                echo "Error fetching pull requests.\n";
            }
    
            if (count($pull_requests) === 100) {
                ++$n;
            }
        }
        echo $output;
    }
    
    public function getUnassignedPullRequests()
{
    $headers = [
        "Accept:application/vnd.github+json",
        "User-Agent: GhadeerZahwe",
        "authorization: Bearer " . env("TOKEN")
    ];
    $n = 2;

    $filename = storage_path("app/4-Unassigned-PRs.txt"); 

    for ($i = 1; $i < $n; $i++) {
        $url = "https://api.github.com/repos/woocommerce/woocommerce/pulls?&per_page=100&page=" . $i;

        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $resp = curl_exec($curl);

        curl_close($curl);
        $pull_requests = json_decode($resp, false);

        foreach ($pull_requests as $pull_request) {
            if (empty($pull_request->requested_reviewers) && empty($pull_request->requested_teams)) {
                $output = $pull_request->number . " " . $pull_request->title . " " . $pull_request->created_at . "\n";
                
                file_put_contents($filename, $output, FILE_APPEND);
                
            }
        }

        if (count($pull_requests) === 100) {
            ++$n;
        }
    }
    echo $output;

}

}