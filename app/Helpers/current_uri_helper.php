<?php

if (!function_exists('getCurrentUri')) {
    function getCurrentUri()
    {
        $app = \Config\Services::request();
        return $app->getUri()->getSegment(1);
    }
}
