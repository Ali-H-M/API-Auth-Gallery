<?php

function getFlickrPhotos($api_url)
{
    $response = file_get_contents($api_url);
    if ($response) {
        $data = json_decode($response, true);
        return $data['photos']['photo'] ?? [];
    }
    return [];
}

?>