<?php

namespace App\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;
use App\Utils\LanguageLoader;

class TestController extends AbstractController {


    public function process(Request $request): Response {
        return $this->render('test', [
            'title' => 'Doc 2 wheels',
            'items' => ['foo', 'bar', 'baz'],
        ]);;
    }
}