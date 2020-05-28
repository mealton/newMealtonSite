<?php

session_start();
?>

<?php if (!$_SESSION['auth']): ?>
    <li class="auth"><a data-toggle="modal" data-target="#sign_in" href="#sign_in">Войти</a></li>
<?php else: ?>
    <li class="auth"><a id="log_out" class="pointer"><?= $_SESSION['username'] ?> (выйти)</a></li>
    <li class="<?= strval($_GET['page'] == 'profile') ? 'active' : '' ?>">
        <a href="/profile" data-content="profile" class="session-config">Личный кабинет</a>
    </li>
<?php endif; ?>

<?php if ($_SESSION['admin']): ?>
    <li class="admin-zone <?= strval($_GET['page'] == 'develop') ? 'active' : '' ?>">
        <a <?= strval($_GET['page'] == 'develop') ? '' : 'href="/develop"'?> data-content="develop" class="session-config">Разработчикам</a>
    </li>
<?php endif; ?>
