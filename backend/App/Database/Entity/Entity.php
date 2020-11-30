<?php


namespace App\Database\Entity;


use App\Helpers\ReflectionUtils;

abstract class Entity {

    public abstract function getId();
}