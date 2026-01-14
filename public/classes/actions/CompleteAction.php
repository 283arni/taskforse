<?php


namespace taskforce\classes\actions;


class CompleteAction extends AbstractAction
{
    public static function getLabel()
    {
        return "Завершить";
    }

    public static function getInternalName()
    {
        return "act_complete";
    }

    public static function checkRights($userId, $performerId, $clientId)
    {
        return $performerId == $userId;
    }

}
