<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
                <?php foreach ($array_projects as $index => $project) : ?>
                    <li class="main-navigation__list-item
                        <?php if ($index==$selected_project_id) { echo ' main-navigation__list-item--active'; };?>
                    ">
                        <a class="main-navigation__list-item-link" href="index.php?project_id=<?=$index?>">
                            <?=htmlspecialchars($project)?>
                        </a>
                        <span class="main-navigation__list-item-count">
                            <?=property_items_count($array_tasks, 'project', $project)?>
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
                <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                <a href="/" class="tasks-switch__item">Повестка дня</a>
                <a href="/" class="tasks-switch__item">Завтра</a>
                <a href="/" class="tasks-switch__item">Просроченные</a>
            </nav>

            <label class="checkbox">
                <a href="/">
                    <input class="checkbox__input visually-hidden" type="checkbox"
                        <?php if ($show_complete_tasks==1) { echo ' checked'; }; ?>
                    >
                    <span class="checkbox__text">Показывать выполненные</span>
                </a>
            </label>
        </div>

        <table class="tasks">
            <?php foreach ($array_tasks_to_show as $task) :?>
                <tr class="tasks__item task
                    <?php
                        if (is_soon($task['date'], SOON_DAYS)) { echo ' task--important'; };
                        if ($task['done']) { echo ' task--completed'; };
                    ?>
                ">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden" type="checkbox">
                            <a href="/"><span class="checkbox__text">
                                <?=htmlspecialchars($task['name'])?>
                            </span></a>
                        </label>
                    </td>

                    <td class="task__file">
                    </td>

                    <td class="task__date">
                        <?php
                            if (empty($task['date'])) {
                                echo NO_DATE;
                            } else {
                                echo htmlspecialchars($task['date']);
                            };
                        ?>
                    </td>
                </tr>
            <?php endforeach;?>
        </table>
    </main>
    
</div>