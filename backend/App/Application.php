<?php namespace App;


use App\User\Repository\UserRepository;

class Application {


    public static function run() {
        $userRepository = new UserRepository();

        $result = $userRepository->getUserById(1);

        var_dump($result->getCreatedAt());
    }
}