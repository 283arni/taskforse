<?php


namespace taskforce\classes\actions;


class CancelAction extends AbstractAction
{
    public static function getLabel():string
    {
        return "Отменить";
    }

    public static function getInternalName():string
    {
        return "act_cancel";
    }

    public static function checkRights(int $userId, ?int $performerId, ?int $clientId):bool
    {
        return $userId == $clientId;
    }

}
