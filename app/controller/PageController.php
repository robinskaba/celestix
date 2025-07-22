<?php

namespace app\controller;

class PageController {
    public function home() {
        $title = 'Celestix';
        $view = 'home';
        $css = ['/assets/css/home.css'];
        $scripts = [];
        require __DIR__ . '/../template.php';
    }

    public function notFound() {
        $title = 'Page not found | Celestix';
        $view = 'not-found';
        $css = ['/assets/css/constellation.css'];
        $scripts = [
        ];
        require __DIR__ . '/../template.php';
    }
}
