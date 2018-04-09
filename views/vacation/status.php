<?php
declare(strict_types=1);

use app\core\AppCore;
use app\models\dayoffs\RequestsRecord;

?>


<div id="vacations-statuses">
    <h1>Hello, <?= AppCore::$user->name ?>!</h1>
    <br>
    <h1>You have <?= $daysLeft ?> days left for vacation this year</h1>
    <?php if ($daysLeft > 0): ?>
        Would you like to <a href="/?r=vacation/request">request a vacation</a>?
    <?php endif; ?>

    <?php if (!empty($vacations)): ?>
        <br>
        <h2>Here are statuses of your vacation requests:</h2>
        <table class="table  border">
            <thead>
            <tr>
                <th>#</th>
                <th>Start date</th>
                <th>End date</th>
                <th>Status</th>
                <th>Requested at</th>
                <th>Updated at</th>
                <th>Updated by</th>
                <th>Actions</th>

            </tr>
            </thead>
            <tbody class="border">


            <?php foreach ($vacations as $number => $vacation): ?>
                <tr>
                    <td><?= $number + 1 ?></td>
                    <td><?= (new DateTime($vacation->start_date))->format('Y-m-d') ?></td>
                    <td><?= (new DateTime($vacation->finish_date))->format('Y-m-d') ?></td>
                    <?php switch ($vacation->status) {
                        case RequestsRecord::STATUS_PENDING:
                            $statusText = 'Not processed';
                            $class = 'not-processed';
                            break;
                        case RequestsRecord::STATUS_APPROVED:
                            $statusText = 'Approved';
                            $class = 'btn-success';
                            break;
                        case RequestsRecord::STATUS_REJECTED:
                            $statusText = 'Rejected';
                            $class = 'btn-danger';
                            break;

                    } ?>
                    <td class="<?= $class ?> ">
                        <?= $statusText ?>
                    </td>
                    <td><?= $vacation->created_at ?></td>
                    <td><?= $vacation->updated_at ?></td>
                    <td>id: <?= $vacation->updated_by ?></td>

                    <td>
                        <?php if ($vacation->status == RequestsRecord::STATUS_PENDING): ?>
                            <button data-id="<?= $vacation->id ?>" data-action="delete"
                                    class="btn btn-danger btn-manage btn-delete">Delete
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    <?php else: ?>
        <br>
        <h2>You don't have any requests yeat.</h2>

    <?php endif ?>
</div>