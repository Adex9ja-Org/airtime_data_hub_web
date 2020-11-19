<?php


namespace App\Model;


class ImageHelper
{

    public static function getProfileImage($image_url)
    {
        $url = (isset($image_url) && $image_url != '') ? $image_url : 'images/default_profile.png';
        return asset($url);
    }
}
