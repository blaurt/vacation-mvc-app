<?php
declare(strict_types=1);

use app\models\dayoffs\RequestsRecord;

?>

<?php if (!empty($vacations)) : ?>
    <div>
        <h2><?= $login ?>'s requests:</h2>
        <br>
        <br>
        <table class="table border">
            <thead>
            <tr>
                <th>#</th>
                <th>Start date</th>
                <th>End date</th>
                <th>Status</th>
                <th>Requested at</th>
                <th>Updated at</th>
                <th>Updated by</th>
                <th colspan="2">Actions:</th>
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
                    <td class="<?= $class ?> " id="status">
                        <?= $statusText ?>
                    </td>
                    <td><?= $vacation->created_at ?></td>
                    <td><?= $vacation->updated_at ?></td>
                    <td>id: <?= $vacation->updated_by ?></td>
                    <td>
                        <?php if ($vacation->status == RequestsRecord::STATUS_PENDING): ?>
                            <button data-id="<?= $vacation->id ?>"
                                    data-status="<?= RequestsRecord::STATUS_APPROVED ?>"
                                    class="btn btn-success btn-manage">Approve
                            </button>
                        <?php endif ?>
                    </td>
                    <td>
                        <?php if ($vacation->status == RequestsRecord::STATUS_PENDING): ?>
                            <button data-id="<?= $vacation->id ?>"
                                    data-status="<?= RequestsRecord::STATUS_REJECTED ?>"
                                    class="btn btn-danger btn-manage">
                                Reject
                            </button>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <h2>Nothing found</h2>
<?php endif; ?>
