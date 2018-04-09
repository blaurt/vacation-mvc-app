<?php
declare(strict_types=1);

use app\core\AppCore;

?>

<?php if (AppCore::$session->flash->existsFlash('user_error')) : ?>
    <div class="bg-danger" id="error-message">
        <?= AppCore::$session->flash->getFlash('user_error'); ?>
    </div>
<?php endif ?>
<form action="/?r=user/login" method="post">
    <div class="custom-form">
        <h1>Please, enter your log-in details:</h1>
        <br>
        <div class="input-group">
            <input type="text" name="login" class="form-control" placeholder="Login" aria-describedby="basic-addon1">
        </div>
        <br>
        <div class="input-group">
            <input type="password" name="password" class="form-control" placeholder="Password"
                   aria-describedby="basic-addon1">
        </div>
        <br>
        <input type="submit" value="SUBMIT" class="btn btn-primary">
    </div>
</form>