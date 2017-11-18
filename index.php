<?php

    // подключение конфига
    require_once 'config.php';
    
    // подключение библиотеки функций
    require_once 'functions.php';
    
    // получение основного содержания
    $main_content = use_template('templates/index.php', [
        'show_complete_tasks' => $show_complete_tasks,
        'array_tasks' => $array_tasks
    ]);
    
    // получение полного текста страницы
    $layout_content = use_template('templates/layout.php', [
        'title' => TITLE,
        'array_projects' => $array_projects,
        'array_tasks' => $array_tasks,
        'content' => $main_content
    ]);
    
    // вывод текста страницы
    print($layout_content);
    
?>