<?php

namespace App\Youtube;

use DateInterval;
use Illuminate\Support\Facades\Http;

class YoutubeServices {

    private $key = null;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function handleYoutubeVideoDuration(string $video_url)
    {
        
        // Récupérer l'ID à partir de $video_url
        // https://www.youtube.com/embed/sdPhUIcDMMQ
        preg_match('/embed\/(.+)/', $video_url, $matches);
        // dd($matches);
        
        $id = $matches[1];
        // Appel l'API youtube pour récupérer la durée
        $response = Http::get("https://www.googleapis.com/youtube/v3/videos?key={$this->key}&id={$id}&part=contentDetails");
        $duration = (json_decode($response))->items[0]->contentDetails->duration;

        // convertir en seconde
        $interval = new DateInterval($duration);
        // dd($interval);
        return $interval->s + $interval->i * 60 + $interval->h * 3600;
    }
}