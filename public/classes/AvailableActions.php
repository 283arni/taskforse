<?php

namespace taskforce\classes;

use DateTime;
use taskforce\classes\actions\AbstractAction;
use taskforce\classes\actions\CancelAction;
use taskforce\classes\actions\CompleteAction;
use taskforce\classes\actions\DenyAction;
use taskforce\classes\actions\ResponseAction;
use taskforce\classes\exceptions\StatusException;

class AvailableActions
{
    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'proceed';
    const STATUS_CANCEL = 'cancel';
    const STATUS_COMPLETE = 'complete';
    const STATUS_EXPIRED = 'expired';

    const ROLE_PERFORMER = 'performer';
    const ROLE_CLIENT = 'customer';

    private $performerId = null;
    private $clientId = null;

    private $status = null;
    private $finishDate = null;

    /**
     * AvailableActionsStrategy constructor.
     * @param string $status
     * @param int $performerId
     * @param int $clientId
     */
    public function __construct(string $status, ?int $performerId, int $clientId)
    {
        $this->setStatus($status);

        $this->performerId = $performerId;
        $this->clientId = $clientId;
    }

    public function setFinishDate(DateTime $dt) {
        $curDate = new DateTime();

        if ($dt > $curDate) {
            $this->finishDate = $dt;
        }
    }

    public function getAvailableActions(string $role, int $id)
    {
        $statusActions = $this->statusAllowedActions()[$this->status];
        $roleActions = $this->roleAllowedActions()[$role];

        $allowedActions = array_intersect($statusActions, $roleActions);

        $allowedActions = array_filter($allowedActions, function ($action) use ($id) {
            return $action::checkRights($id, $this->performerId, $this->clientId);
        });

        return array_values($allowedActions);
    }

    public function getNextStatus(string $action): ?string
    {
        $map = [
            CompleteAction::class => self::STATUS_COMPLETE,
            CancelAction::class => self::STATUS_CANCEL,
            DenyAction::class => self::STATUS_CANCEL,
            ResponseAction::class => null
        ];
            
        return $map[$action];
    }

    public function setStatus(string $status)
    {
        $availableStatuses = [self::STATUS_NEW, self::STATUS_IN_PROGRESS, self::STATUS_CANCEL, self::STATUS_COMPLETE,
            self::STATUS_EXPIRED];

        if (!in_array($status, $availableStatuses)) {
            throw new StatusException("Нет такого статуса: $status");
        }

        $this->status = $status;
    }

    /**
     * Возвращает действия, доступные для каждой роли
     * @return array
     */
    private function roleAllowedActions()
    {
        $map = [
            self::ROLE_CLIENT => [CancelAction::class, CompleteAction::class],
            self::ROLE_PERFORMER => [ResponseAction::class, DenyAction::class]
        ];

        return $map;
    }

    /**
     * Возвращает действия, доступные для каждого статуса
     * @return array
     */
    private function statusAllowedActions() {
        $map = [
            self::STATUS_CANCEL => [],
            self::STATUS_COMPLETE => [],
            self::STATUS_IN_PROGRESS => [DenyAction::class, CompleteAction::class],
            self::STATUS_NEW => [CancelAction::class, ResponseAction::class],
            self::STATUS_EXPIRED => []
        ];

        return $map;
    }

    private function getStatusMap()
    {
        $map = [
            self::STATUS_NEW => [self::STATUS_EXPIRED, self::STATUS_CANCEL],
            self::STATUS_IN_PROGRESS => [self::STATUS_CANCEL, self::STATUS_COMPLETE],
            self::STATUS_CANCEL => [],
            self::STATUS_COMPLETE => [],
            self::STATUS_EXPIRED => [self::STATUS_CANCEL]
        ];

        return $map;
    }

}

