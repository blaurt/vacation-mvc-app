<?php
declare(strict_types=1);

namespace app\interfaces;

interface IDayOff
{
    public static function getRemainedDays(\app\models\users\User $user): int;

    public static function createRequest(\DateTime $startDay, \DateTime $endDay): bool;

    public function deleteRequest(): bool;

    public function canBeUpdated(int $daysLeft = null): bool;
}