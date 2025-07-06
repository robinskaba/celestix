<?php

class PageController {
    public function home() {
        $title = 'Stellara';
        $view = 'home';
        $css = ['/assets/css/home.css'];
        $scripts = [
            // ['src' => '/assets/js/home.js', 'defer' => true],
            // ['src' => '/assets/js/analytics.js', 'async' => true],
        ];
        require __DIR__ . '/../template.php';
    }

    public function not_found() {
        $title = 'Page not found | Stellara';
        $view = 'not-found';
        $css = ['/assets/css/constellation.css'];
        $scripts = [
        ];
        require __DIR__ . '/../template.php';
    }
}
