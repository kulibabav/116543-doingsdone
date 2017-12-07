<div class="content">

    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php foreach ($array_projects as $project) : ?>
                    <li class="main-navigation__list-item
                        <?php if ($project['id']==$selected_project_id) { echo ' main-navigation__list-item--active'; };?>
                    ">
                        <a class="main-navigation__list-item-link" href="<?=add_get_param('project_id', $project['id'])?>">
                            <?=htmlspecialchars($project['name'])?>
                        </a>
                        <span class="main-navigation__list-item-count">
                            <?=$project['tasks_count']?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button" href="#">Добавить проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="index.html" method="post">
            <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">
            <input class="search-form__submit" type="submit" name="" value="Искать">
        </form>

        <div class="tasks-controls">
            <nav class="tasks-switch">
                <a
                    href="<?=add_get_param('deadline', 'all')?>"
                    class="tasks-switch__item<?php if($selected_deadline == 'all') { echo ' tasks-switch__item--active'; }; ?>"
                >Все задачи</a>
                <a
                    href="<?=add_get_param('deadline', 'today')?>"
                    class="tasks-switch__item<?php if($selected_deadline == 'today') { echo ' tasks-switch__item--active'; }; ?>"
                >Повестка дня</a>
                <a
                    href="<?=add_get_param('deadline', 'tomorrow')?>"
                    class="tasks-switch__item<?php if($selected_deadline == 'tomorrow') { echo ' tasks-switch__item--active'; }; ?>"
                >Завтра</a>
                <a
                    href="<?=add_get_param('deadline', 'expired')?>"
                    class="tasks-switch__item<?php if($selected_deadline == 'expired') { echo ' tasks-switch__item--active'; }; ?>"
                >Просроченные</a>
            </nav>

            <label class="checkbox">
                <a
                    href="<?=add_get_param('show_completed', $show_completed_tasks==0 ? '1' : '0')?>"
                >
                    <input class="checkbox__input visually-hidden" type="checkbox"
                        <?php if ($show_completed_tasks==1) { echo ' checked'; };?>
                    >
                    <span class="checkbox__text">Показывать выполненные</span>
                </a>
            </label>
        </div>

        <table class="tasks">
            <?php foreach ($array_tasks as $task) :?>
                <tr class="tasks__item task
                    <?php
                        if (is_soon($task['date'], SOON_DAYS)) { echo ' task--important'; };
                        if ($task['completed'] <> null) { echo ' task--completed'; };
                    ?>
                ">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden" type="checkbox">
                            <a href="<?=add_get_param('change_status', $task['id'])?>"><span class="checkbox__text">
                                <?=htmlspecialchars($task['name'])?>
                            </span></a>
                        </label>
                    </td>

                    <td class="task__file">
                    </td>

                    <td class="task__date">
                        <?php
                            if ($task['deadline'] == null) {
                                echo NO_DATE;
                            } else {
                                echo htmlspecialchars($task['deadline']);
                            };
                        ?>
                    </td>
                </tr>
            <?php endforeach;?>
        </table>
    </main>
    
</div>