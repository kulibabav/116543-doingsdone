<?php
$name = $_POST['name'] ?? '';
$project_id = $_POST['project_id'] ?? '';
$date = $_POST['date'] ?? '';
$preview = $_FILES['preview']['name'] ?? '';

$error_name = $errors['name'] ?? '';
$error_project = $errors['project'] ?? '';
$error_date = $errors['date'] ?? '';
$error_preview = $errors['preview'] ?? '';
?>

<div class="modal">
    <button class="modal__close" type="button" name="button">Закрыть</button>

    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form"  action="index.php" method="POST" enctype="multipart/form-data">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input
                <?php if ($error_name) { echo 'form__input--error'; };?>
            " type="text" name="name" id="name" value="<?=$name?>" placeholder="Введите название">
            
            <?php if ($error_name) : ?>
                <p class="form__message"><?=$error_name?></p>
            <?php endif; ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select
                <?php if ($error_project) { echo ' form__input--error'; };?>
            " name="project_id" id="project">
                <?php
                    foreach ($array_projects as $item) :
                        if ($item['id'] != 0) :
                ?>
                        <option value="<?=$item['id']?>"
                            <?php if ($item['id']==$project_id) { echo 'selected'; };?>
                        >
                            <?=$item['name']?>
                        </option>
                <?php
                        endif;
                    endforeach;
                ?>
            </select>
            
            <?php if ($error_project) : ?>
                <p class="form__message"><?=$error_project?></p>
            <?php endif; ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения</label>

            <input class="form__input form__input--date
                <?php if ($error_date) { echo ' form__input--error'; };?>
            " type="text" name="date" id="date" value="<?=$date?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
            
            <?php if ($error_date) : ?>
                <p class="form__message"><?=$error_date?></p>
            <?php endif; ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="preview">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="preview" id="preview" value="<?=$preview?>">

                <label class="button button--transparent" for="preview">
                    <span>Выберите файл</span>
                </label>
            </div>
            
            <?php if ($error_preview) : ?>
                <p class="form__message"><?=$error_preview?></p>
            <?php endif; ?>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="task_input" value="Добавить">
        </div>
    </form>
</div>