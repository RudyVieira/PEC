<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;
use App\Lib\Http\Response;

class LogoutController extends AbstractController {

    public function process(Request $request): Response {
        session_start();
        session_destroy();

        return new Response('', 302, ['Location' => '/login']);
    }
}