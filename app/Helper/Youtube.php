<?php
/**
 * Youtube search request helper file
 * PHP version 7.35
 * @author     Original Author <danindragosavac@gmail.com>
 * @link       https://github.com/Daninphp
 */

namespace App\Helper;

class Youtube
{
    /**
     * Returns message for no results given by Youtube
     * @return string
     */
    public function emptyResponse()
    {
        $message = '<h3>Your search returned no results, please try again.</h3>';
        echo json_encode($message);
    }

    /**
     * Returns message for wrong request method sent to controller
     * @see App\Http\Controllers\Youtubecontroller
     * @return string
     */
    public function wrongMethod()
    {
        $message = '<h3>Request method is not post!</h3>';
        echo json_encode($message);
    }

    /**
     * Returns message for empty keyword sent to controller
     * @see App\Http\Controllers\Youtubecontroller
     * @return string
     */
    public function searchTermEmpty()
    {
        $message = '<h3>Search term is empty!</h3>';
        echo json_encode($message);
    }

    /**
     * Removes non html elements from string given by Youtube
     * @return string
     */
    private function replaceNonHtml($string)
    {
        return preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $string);
    }

    /**
     * Processes Youtube API V3 response and returns html block
     * @return string
     */
    public function getHtmlResponse($data)
    {
        try {
            if (!empty($data->items)) {
                $html = '';
                foreach ($data->items as $key => $item) {
                    $videoId =  $item->id->videoId;
                    $title = $this->replaceNonHtml($item->snippet->title);
                    $description = $this->replaceNonHtml($item->snippet->description);
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
