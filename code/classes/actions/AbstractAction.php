<?php
namespace taskforce\classes\actions;

abstract class AbstractAction
{
    abstract public static function getLabel():string;

    abstract public static function getInternalName():string;

    abstract public static function checkRights(int $userId, ?int $performerId, ?int $clientId):bool;
}
