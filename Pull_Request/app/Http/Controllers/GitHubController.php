<?php

namespace App\Http\Controllers;

class GitHubController extends Controller
{
    public function fetchPullRequests($url)
    {
        $n = 2;
        $headers = [
            "Accept:application/vnd.github+json",
            "User-Agent: GhadeerZahwe",
            "authorization: Bearer " . env("TOKEN")
        ];
        $pullRequests = [];

        for ($i = 1; $i < $n; $i++) {
            $fullUrl = $url . $i;
            $curl = curl_init($fullUrl);

            curl_setopt($curl, CURLOPT_URL, $fullUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $resp = curl_exec($curl);

            curl_close($curl);

            $pullRequests[] = json_decode($resp);
        }

        return $pullRequests;
    }
}
