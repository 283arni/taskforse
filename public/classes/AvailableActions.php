<?php

namespace taskforce\classes;

class AvailableActions
{
    private int|null $employeeId;
    private int $customerId;
    private string $status;

    const STATUS_NEW = 'new';
    const STATUS_CANCEL = "cancel";
    const STATUS_AT_WORK = "at_work";
    const STATUS_COMPLETED = "completed";
    const STATUS_FAILED = "failed";


    const ACTION_COMPLETED = "completed";
    const ACTION_REJECT = "reject";
    const ACTION_CANCEL = "cancel";
    const ACTION_RESPOND = "respond";


    function __construct(string $status, int $customerId, ?int $employeeId = null)
    {
        $this->setStatus($status);

        $this->employeeId = $employeeId;
        $this->customerId = $customerId;
    }


    /**
     * @return string[]
     */
    public function getStatusesMap(): array
    {
        return [
            self::STATUS_NEW => "Новый",
            self::STATUS_AT_WORK => "В работе",
            self::STATUS_COMPLETED => "Выполнено",
            self::STATUS_CANCEL => "Отменено",
            self::STATUS_FAILED => "Провалено",
        ];
    }

    /**
     * @return string[]
     */
    public function getActionsMap(): array
    {
        return [
            self::ACTION_COMPLETED => "Выполнено",
            self::ACTION_CANCEL => "Отменено",
            self::ACTION_RESPOND => "Откликнуться",
            self::ACTION_REJECT => "Отказаться",
        ];
    }

    /**
     * @param string $status
     * @return void
     */
    public function setStatus(string $status): void
    {
        $statuses = [
            self::STATUS_NEW,
            self::STATUS_CANCEL,
            self::STATUS_AT_WORK,
            self::STATUS_COMPLETED,
            self::STATUS_FAILED,
        ];

        if (in_array($status, $statuses)) {
            $this->status = $status;
        }
    }

    /**
     * @param string $action
     * @return string|null
     */
    public function getNextStatus(string $action): ?string
    {
        $map = [
            self::ACTION_COMPLETED => self::STATUS_COMPLETED,
            self::ACTION_CANCEL => self::STATUS_CANCEL,
            self::ACTION_REJECT => self::STATUS_CANCEL,
        ];

        return $map[$action];
    }

    /**
     * Получить доступные actions в статусе
     *
     * @param string $status
     * @return string[]
     */
    public function getAllowedActions(string $status): array
    {
        $allowedActions = [
            self::STATUS_AT_WORK => [self::ACTION_COMPLETED, self::ACTION_CANCEL],
            self::STATUS_NEW => [self::ACTION_REJECT, self::ACTION_RESPOND],
        ];

        return $allowedActions[$status];
    }
}

