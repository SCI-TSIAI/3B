<?php namespace App\User\Repository;


use App\Database\Connector;
use App\Database\Repository\Repository;
use App\User\Entity\UserEntity;
use PDO;

class UserRepository extends Repository {

    /**
     * UserRepository constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    public function getEntityName() {
        return "App\User\Entity\UserEntity";
    }

    protected function getTableName() {
        return "user";
    }
}