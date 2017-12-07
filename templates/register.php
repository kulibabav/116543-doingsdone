<?php
$email = $_POST['email'] ?? '';
$name = $_POST['name'] ?? '';

$error_email = $errors['email'] ?? '';
$error_password = $errors['password'] ?? '';
$error_name = $errors['name'] ?? '';
?>

<div class="content">
    
    <section class="content__side">
        <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

        <a class="button button--transparent content__side-button" href="index.php?login=true">Войти</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Регистрация аккаунта</h2>

        <form class="form" action="register.php" method="post">
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
                    <?php if ($error_email) { echo 'form__input--error'; };?>
                " type="password" name="password" id="password" value="" placeholder="Введите пароль">
                
                <?php if ($error_password) : ?>
                    <p class="form__message"><?=$error_password?></p>
                <?php endif; ?>
            </div>

            <div class="form__row">
                <label class="form__label" for="name">Имя <sup>*</sup></label>

                <input class="form__input
                    <?php if ($error_name) { echo 'form__input--error'; };?>
                " type="text" name="name" id="name" value="<?=$name?>" placeholder="Введите имя">
                
                <?php if ($error_name) : ?>
                    <p class="form__message"><?=$error_name?></p>
                <?php endif; ?>
            </div>

            <div class="form__row form__row--controls">
                <?php if (count($errors)) : ?>
                    <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
                <?php endif; ?>
                
                <input class="button" type="submit" name="register" value="Зарегистрироваться">
            </div>
        </form>
    </main>
</div>