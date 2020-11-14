<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\Youtube;

class YoutubeController extends Controller
{
    const API_KEY = 'AIzaSyDO4Z68dxWINsfsjcyNatk2uOkP6XWxWlo';
    const MAX_RESULTS = 2;

    private $helper;
    private $retriveHtmlResponse = false;

    public function __construct(
        \App\Helper\Youtube $helper
    )
    {
        $this->helper = $helper;
    }

    public function getKeyword(Request $request, $keyword)
    {
        if ($request->isMethod('post') && $keyword !== '') {
            $this->callYoutubeApi($this->processKeyWord($keyword));
        } elseif(!$request->isMethod('post')) {
            $this->helper->wrongMethod();
        } else {
            $this->helper->searchTermEmpty();
        }
    }

    private function processKeyWord($keyword)
    {
        if (strpos($keyword, ' ') !== false) {
            return str_replace(' ', '%20', $keyword);
        } else  {
            return $keyword;
        }
    }

    private function callYoutubeApi($keyword)
    {
        $googleApiUrl = 'https://www.googleapis.com/youtube/v3/search?part=snippet&q=' . $keyword . '&maxResults=' . self::MAX_RESULTS . '&key=' . self::API_KEY;

        try {
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
        } catch (\Exception $e){
            echo $e->getMessage();
        }

        if(!empty($data->items)){
            if($this->retriveHtmlResponse){
                $this->helper->getHtmlResponse($data);
            } else {
                echo json_encode($data);
            }
        } else {
            $this->helper->emptyResponse();
        }
    }

}
