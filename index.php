<?php

    // подключение конфига
    require_once 'config.php';
    
    // подключение библиотеки функций
    require_once 'functions.php';
    
    // обработка GET-параметра project_id
    $selected_project_id = 0;
    if (isset($_GET['project_id'])) {
        $selected_project_id = $_GET['project_id'];
        $project = $array_projects[$selected_project_id];
        if (isset($project)) {
            $array_tasks_to_show = array_filter($array_tasks, function($task) use($project) {
                return ($project == 'Все' || $task['project'] == $project);
            });
        } else {
            header('HTTP/1.1 404 Not Found');
            die();
        };
    };
    
    // получение основного содержания
    $main_content = use_template('templates/index.php', [
        'show_complete_tasks' => $show_complete_tasks,
        'array_tasks' => isset($array_tasks_to_show) ? $array_tasks_to_show : $array_tasks
    ]);
    
    // получение полного текста страницы
    $layout_content = use_template('templates/layout.php', [
        'title' => TITLE,
        'array_projects' => $array_projects,
        'selected_project_id' => $selected_project_id,
        'array_tasks' => $array_tasks,
        'content' => $main_content
    ]);
    
    // вывод текста страницы
    print($layout_content);
    
?>