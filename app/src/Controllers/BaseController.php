<?php

namespace App\Controllers;

use App\Lib\Controllers\AbstractController;
use App\Lib\Http\Request;

abstract class BaseController extends AbstractController {
    protected $translations;

    protected function initialize(Request $request) {
        session_start();

        $lang = $_SESSION['lang'] ?? 'fr';
        if ($request->getMethod() === 'POST' && isset($request->getBody()['lang'])) {
            $lang = $request->getBody()['lang'];
            $_SESSION['lang'] = $lang;
        }

        $projectRoot = dirname(__DIR__, 1);
        $langFile = $projectRoot . "/lang/$lang.json";
        if (file_exists($langFile)) {
            $this->translations = json_decode(file_get_contents($langFile), true);
        } else {
            $this->translations = [];
        }
    }
}