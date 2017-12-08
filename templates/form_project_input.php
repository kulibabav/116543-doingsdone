<?php
$name = $_POST['name'] ?? '';
$error_name = $errors['name'] ?? '';
?>

<div class="modal">
    <button class="modal__close" type="button" name="button">Закрыть</button>

    <h2 class="modal__heading">Добавление проекта</h2>

    <form class="form" method="POST">
        <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>

            <input class="form__input
                <?php if ($error_name) { echo 'form__input--error'; };?>
            " type="text" name="name" id="project_name" value="<?=$name?>" placeholder="Введите название">
            
            <?php if ($error_name) : ?>
                <p class="form__message"><?=$error_name?></p>
            <?php endif; ?>
        </div>
        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="project_input" value="Добавить">
        </div>
    </form>
</div>