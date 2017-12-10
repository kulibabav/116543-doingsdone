<?php
$email = $_POST['email'] ?? '';

$error_email = $errors['email'] ?? '';
$error_password = $errors['password'] ?? '';
?>

<div class="modal">
    <a href="<?=remove_get_param('login')?>">
        <button class="modal__close" type="button" name="button">Закрыть</button>
    </a>

    <h2 class="modal__heading">Вход на сайт</h2>

    <form class="form" action="index.php" method="post">
        <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>

            <input class="form__input
                <?php if ($error_email) { echo 'form__input--error'; };?>
            " type="text" name="email" id="email" value="<?=$email?>" placeholder="Введите e-mail">

            <?php if ($error_email) : ?>
                <p class="form__message"><?=$error_email?></p>
            <?php endif; ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>

            <input class="form__input
                <?php if ($error_password) { echo 'form__input--error'; };?>
            " type="password" name="password" id="password" value="" placeholder="Введите пароль">
            
            <?php if ($error_password) : ?>
                <p class="form__message"><?=$error_password?></p>
            <?php endif; ?>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="login" value="Войти">
        </div>
    </form>
</div>
