<?php
declare(strict_types=1);

use app\core\AppCore;

?>


<?php if (AppCore::$session->flash->existsFlash('register_error')) : ?>
    <div class="bg-danger" id="error-message">
        <?= AppCore::$session->flash->getFlash('register_error'); ?>
    </div>
<?php endif ?>

<form action="/?r=user/register" method="post">
    <div class="custom-form">
        <h1>Please, fill the form below:</h1>
        <br>
        <div class="input-group">
            <input type="text" name="login" class="form-control" placeholder="Login" aria-describedby="basic-addon1">
        </div>
        <br>

        <div class="input-group">
            <input type="text" class="form-control" name="username" placeholder="Name" aria-describedby="basic-addon1">
        </div>
        <br>

        <div class="input-group">
            <input type="password" class="form-control" name="password" placeholder="Password"
                   aria-describedby="basic-addon1">
        </div>
        <br>
        Type of user:
        <br>
        <input type="radio" name="role" value="<?= \app\models\users\UserRecord::WORKER_ROLE ?>" checked> Worker<br>
        <input type="radio" name="role" value="<?= \app\models\users\UserRecord::MANAGER_ROLE ?>"> Manager<br>
        <br>
        <input type="submit" class="btn btn-primary" value="SUBMIT">
    </div>
</form>
