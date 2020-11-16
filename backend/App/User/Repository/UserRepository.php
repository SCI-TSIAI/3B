<?php namespace App\User\Repository;


use App\Database\Connector;
use App\User\Entity\UserEntity;
use PDO;

class UserRepository {

    private $databaseConnection;

    /**
     * UserRepository constructor.
     */
    public function __construct() {
        $this->databaseConnection = Connector::getInstance();
    }

    /**
     * @param $id
     * @return UserEntity
     */
    public function getUserById($id) {

        $query = $this->databaseConnection->prepare("Select * from user where id=:id");

        $query->execute(array(":id" => $id));
        $query->setFetchMode(PDO::FETCH_CLASS, $this->getEntityName());

        return $query->fetch();
    }

    private function getEntityName() {
        return "App\User\Entity\UserEntity";
    }
}