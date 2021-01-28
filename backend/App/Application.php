<?php namespace App;

use App\Router\RestRouter;

class Application {

    public static function run() {
        RestRouter::run();
    }
}