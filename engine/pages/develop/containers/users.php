<?php
/**
 * @var $html string
 * Типовой шаблон
 */
?>

<fieldset>
    <legend>Менеджер пользователей</legend>
    <div class="search-user">
        <input type="text" placeholder="Введите имя или имя пользвателя" class="form-control search">
    </div>
    <ul class="userlist">
        <?= $html ?>
    </ul>
    <h5 class="pointer centered show-form">Добавление новых пользователей:</h5>
    <form action="" class="add-user hidden">
        <div class="form-data">
            <input type="text" name="username" class="form-control" placeholder="Имя пользвателя">
        </div>
        <div class="form-data">
            <input type="text" name="password" class="form-control password" placeholder="Пароль">
            <div class="generate-password">
                <span>Сгенерировать пароль</span>
            </div>
        </div>
        <div class="form-data">
            <input type="email" class="form-control" name="email" placeholder="Email">
        </div>
        <div class="form-data">
            <input type="text" class="form-control" name="name" placeholder="Имя">
        </div>
        <div class="form-data">
            <input type="date" class="form-control" name="birthday" placeholder="Дата рождения">
        </div>
        <div class="form-data">
            <div class=" upload">
                <input type="text" name="profile_image" placeholder="url картинки" class="form-control upload-url">
                <button type="button" class="upload-url-btn btn btn-info">
                    <i class="fa fa-upload" aria-hidden="true"></i>
                    <img src="/assets/img/ajax-loader.gif">
                </button>
                <label class="upload-label">
                    <input type="file" value="Загрузить с компьютера" class="hidden file-upload-input" accept="image/x-png, image/gif, image/jpeg">
                    <span class="btn btn-primary">Загрузить с компьютера</span>
                    <img src="/assets/img/ajax-loader.gif">
                </label>
            </div>
            <div class="preview"></div>
        </div>
        <br>
        <fieldset>
            <legend>Права администратора</legend>
            <label>Да<input type="radio" name="status" value="admin"></label>
            <label>Нет<input type="radio" name="status" value="user" checked></label>
        </fieldset>
        <br>
        <div class="form-data">
            <textarea name="comment" class="form-control"  placeholder="Комментарий"></textarea>
        </div>
        <input type="hidden" name="action" value="addUser">
        <button type="submit" class="btn btn-primary add">Добавить</button>
    </form>
</fieldset>

