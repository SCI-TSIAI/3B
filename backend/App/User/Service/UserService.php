<?php


namespace App\User\Service;


use App\User\Entity\UserEntity;
use App\User\Model\UserRequest;
use App\User\Model\UserResponse;
use App\User\Repository\UserRepository;

class UserService {

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserService constructor.
     */
    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    /**
     * @param UserRequest $model
     * @return UserResponse | bool
     * @throws \ReflectionException
     */
    public function addUser(UserRequest $model) {

        $userEntity = new UserEntity();

        $userEntity->setUsername($model->getUsername())
            ->setPasswordHash(sha1($model->getPassword()));

        return $this->convertUserEntityToUserResponse($this->userRepository->save($userEntity));
    }

    /**
     * @param $id
     * @return UserResponse
     */
    public function getUser($id) {

        return $this->convertUserEntityToUserResponse($this->userRepository->getById($id));
    }

    public function isUserWithPasswordExists($username, $password) {
        return $this->userRepository->isUserWithPasswordExists($username, $password);
    }

    /**
     * @param UserEntity $userEntity
     * @return UserResponse | bool
     */
    private function convertUserEntityToUserResponse($userEntity) {

        if (empty($userEntity)) {
            return false;
        }

        $userResponse = new UserResponse();

        $userResponse
            ->setId($userEntity->getId())
            ->setUsername($userEntity->getUsername())
            ->setLastLogin($userEntity->getLastLogin())
            ->setCreatedAt($userEntity->getCreatedAt());

        return $userResponse;
    }
}