<?php

use app\core\AppCore;

?>


<br><br>
<h1>Welcome to <?= AppCore::$config['app_name'] ?></h1>
<br>
<?php if (AppCore::$session->isInSession('user_data')) : ?>
    Welcome, <?= AppCore::$user->name; ?>
<?php else: ?>
    <h1>Please, <a href="/?r=user/login">login</a> or <a href="/?r=user/register">register</a></h1>
<?php endif; ?>
