<?php
/**
 * Youtube API V3 search request process controller
 * PHP version 7.35
 * @author     Original Author <danindragosavac@gmail.com>
 * @link       https://github.com/Daninphp
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YoutubeController extends Controller
{
    /**
     * Google API V3 key
     * Only possible to use certain amount of times per day
     */
    const API_KEY = 'AIzaSyDO4Z68dxWINsfsjcyNatk2uOkP6XWxWlo';

    /**
     * Max amount of results returned by google API
     */
    const MAX_RESULTS = 2;

    /**
     * Helper class
     * @see App\Helper\Youtube
     */
    private $helper;

    /**
     * Should the controller return HTML or array
     * @var boolean $retriveResponseAsHtml
     */
    private $retriveResponseAsHtml = false;

    public function __construct(
        \App\Helper\Youtube $helper
    )
    {
        $this->helper = $helper;
    }

    /**
     * Accepts request AJAX
     * @param string $keyword  AJAX requested keyword
     */
    public function getKeyword(Request $request, $keyword)
    {
        if ($request->isMethod('post') && $keyword !== '') {
            $this->callYoutubeApi($this->processKeyword($keyword));
        } elseif(!$request->isMethod('post')) {
            $this->helper->wrongMethod();
        } else {
            $this->helper->searchTermEmpty();
        }
    }

    /**
     * Check if keyword has empty space
     * @param string $keyword
     * @return string
     */
    private function processKeyword($keyword)
    {
        if (strpos($keyword, ' ') !== false)
            return str_replace(' ', '%20', $keyword);

        return $keyword;
    }

    /**
     * Calls Youtube API
     * @param string $keyword
     * @return mixed
     */
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
            if($this->retriveResponseAsHtml){
                $this->helper->getHtmlResponse($data);
            } else {
                echo json_encode($data);
            }
        } else {
            $this->helper->emptyResponse();
        }
    }
}
