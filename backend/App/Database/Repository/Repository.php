<?php


namespace App\Database\Repository;


use App\Database\Connector;
use App\Database\Entity\Entity;
use App\Helpers\ReflectionUtils;
use App\User\Entity\UserEntity;
use PDO;

abstract class Repository {

    private $databaseConnection;

    public function __construct() {
        $this->databaseConnection = Connector::getInstance();
    }

    protected function prepare($statement) {
        $query = $this->databaseConnection->prepare($statement);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->getEntityName());

        return $query;
    }

    /**
     * @param $id
     * @return Entity
     */
    public function getById($id) {

        $query = $this->prepare("Select * from " . $this->getTableName() . " where id=:id");

        $query->execute(array(
            ":id" => $id
        ));

        return $query->fetch();
    }

    /**
     * @param Entity $object
     * @return null
     * @throws \ReflectionException
     */
    public function save(Entity $object) {

        $result = null;

        if (empty($object->getId())) {
            $result = $this->performSave($object);
        } else {
            //TODO implement update method.
            $result = $this->performUpdate($object);
        }

        return $result;
    }

    /**
     * @param Entity $object
     * @return null
     * @throws \ReflectionException
     */
    private function performSave(Entity $object) {

        //TODO remove all fields with null values
        //TODO change method perform save to return updated entity object

        $fields = ReflectionUtils::getObjectPrivateFields($object);

        unset($fields['id']);

        $fieldNamesString = implode(", ", array_keys($fields));

        $fieldPlaceholders = self::addPrefixToArrayKeys($fields, ":");
        $fieldPlaceholdersString = join(", ", array_keys($fieldPlaceholders));

        $sql = "INSERT INTO " . $this->getTableName() . "($fieldNamesString) VALUES ($fieldPlaceholdersString)";
        $stmt = $this->databaseConnection->prepare($sql);

        foreach ($fieldPlaceholders as $key => $value) {
            $stmt->bindParam($fields, $value);
        }

        $stmt->execute();

        return null;
    }

    /**
     * @param Entity $entity
     * @return null
     */
    private function performUpdate(Entity $entity) {
        return null;
    }

    //TODO move it to helper
    private static function addPrefixToArrayKeys($array, $prefix) {
        $resultArray = array();

        foreach ($array as $key => $value) {
            $resultArray[$prefix . $key] = $value;
        }

        return $resultArray;
    }

    protected abstract function getEntityName();

    protected abstract function getTableName();
}