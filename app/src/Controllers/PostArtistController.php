<?php

namespace App\Controllers;

use App\Entities\Artist;
use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Managers\ArtistManager;

class PostArtistController extends AbstractController {


    public function process(Request $request): Response {
        $artistManager = new ArtistManager();
        $artist = new Artist();
        $artist->setName('Test Artist');

        $artist->id = $artistManager->save($artist);
        return new Response(json_encode($artist), 201, ['Content-Type' => 'application/json']);
    }
}