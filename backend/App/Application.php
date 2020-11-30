<?php namespace App;


use App\Database\Repository\Repository;
use App\User\Entity\UserEntity;
use App\User\Repository\UserRepository;

class Application {


    public static function run() {
        $userRepository = new UserRepository();

//        $result = $userRepository->getById(1);


        $entity = new UserEntity();
        $entity->setUsername("dsadsadsa");
        $entity->setPasswordHash("dsadsadsadsa");


        $userRepository->save($entity);
    }
}