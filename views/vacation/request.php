<?php
declare(strict_types=1);

use app\core\AppCore;

?>

<?php if (AppCore::$session->flash->existsFlash('request_error')) : ?>
    <div class="bg-danger" id="error-message">
        <?= AppCore::$session->flash->getFlash('request_error'); ?>
    </div>
<?php endif ?>

<div>
    <h3>Vacation request</h3>
    You have : <?= $daysLeft ?> vacation day(s)
    <br>
    Choose start & end dates of your vacation
    <br>
    <br>
    <form action="/?r=vacation/request" method="post">
        <label for="start_date">Start date</label>

        <input type="date" name="start_date" id="start_date" placeholder="Start date">
        <br><br>

        <label for="start_date">End date </label>
        <input type="date" name="end_date" id="end_date" placeholder="End date">
        <br><br>
        <input type="submit" class="btn-primary btn" value="Submit">
    </form>
</div>