<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YoutubeController extends Controller
{
    const API_KEY = 'AIzaSyBQcH2J0b0HJIv-tNLQiwINjCjU0kPS4lI';
    const MAX_RESULTS = 10;

    public function getKeyword(Request $request, $keyword)
    {
        if ($request->isMethod('post') && $keyword !== '') {
//            $this->callYoutubeApi($keyword);
        } else {
            /** TODO: error reporting */
        }
    }

    private function callYoutubeApi($keyword)
    {
        $googleApiUrl = 'https://www.googleapis.com/youtube/v3/search?part=snippet&q=' . $keyword . '&maxResults=' . self::MAX_RESULTS . '&key=' . self::API_KEY;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status != 200) {
            die("Error: call to URL $googleApiUrl failed with status $status, response $response, curl_error " . curl_error($ch) . ", curl_errno " . curl_errno($ch));
        }
        curl_close($ch);

        $data = json_decode($response);
        echo json_encode($data);
    }
}
