<?php

namespace Azuriom\Plugin\WebMap\Controllers;

use Azuriom\Http\Controllers\Controller;

class WebMapController extends Controller
{
    /**
     * Display the map.
     */
    public function index()
    {
        $url = setting('webmap.url', 'http://play.nexaria.fr:8123/');

        return view('webmap::index', [
            'url' => $url
        ]);
    }
}
