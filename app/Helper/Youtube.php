<?php

namespace App\Helper;

use function GuzzleHttp\Psr7\str;

class Youtube
{
    public function emptyResponse()
    {
        $message = '<h3>Your search returned no results, please try again.</h3>';
        echo json_encode($message);
    }

    public function wrongMethod()
    {
        $message = '<h3>Request method is not post!</h3>';
        echo json_encode($message);
    }

    public function searchTermEmpty()
    {
        $message = '<h3>Search term is empty!</h3>';
        echo json_encode($message);
    }

    public function getHtmlResponse($data)
    {
        try {
            if (!empty($data)) {
                $html = '';
                $replaceElements = ["'","\""];
                foreach ($data->items as $key => $item) {
                    $videoId = $item->id->videoId;
                    $title = str_replace($replaceElements,"", $item->snippet->title);
                    $description = str_replace($replaceElements,"", $item->snippet->description);
                    $html .= '
                    <div  class="videoDiv">
                        <iframe id="iframe" style="width:50%;height:300px" src="//www.youtube.com/embed/' . $videoId . '"
                                data-autoplay-src="//www.youtube.com/embed/' . $videoId . 'autoplay=1"></iframe>
                    </div>
                    <div class="videoInfo">
                        <div class="videoTitle"><b>' . $title . '</b></div>
                        <div class="videoDesc">' . $description . '</div>
                    </div>
                    ';
                }
                echo json_encode($html);
            }
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
        }
    }
}
