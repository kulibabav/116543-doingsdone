<?php

    // подключение конфига
    require_once 'config.php';
    
    // подключение библиотеки функций
    require_once 'functions.php';
    
    // обработка добавления задачи
    $required = ['name', 'project', 'date'];
    $rules = ['date' => 'validateDate'];
    $errors = [];
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        foreach ($_POST as $key => $value) {
            if (in_array($key, $required) && $value == '') {
                $errors[$key] = 'Пожалуйста, заполните это поле';
                continue;
            };
            if (array_key_exists($key, $rules)) {
                $result = call_user_func($rules[$key], $value);
                if (!$result) {
                    $errors[$key] = 'Пожалуйста, укажите корректное значение';
                };
            };
        };
        
        if (!count($errors)) {
            array_unshift($array_tasks, [
                'name' => $_POST['name'],
                'project' => $_POST['project'],
                'date' => $_POST['date'],
                'done' => false
            ]);
        } else {
            $_GET['add'] = true;
        };
    };
    
    // сохранение вложенного файла
    if (isset($_FILES['preview'])) {
        $file_path = __DIR__ . '/' . $_FILES['preview']['name'];
        move_uploaded_file($_FILES['avatar']['tmp_name'], $file_path);
    };
    
    // заполнение $main_content_data по-умолчанию
    $main_content_data = [
        'show_complete_tasks' => $show_complete_tasks,
        'array_tasks' => $array_tasks
    ];
    
    // заполнение $layout_data по-умолчанию
    $layout_data = [
        'title' => TITLE,
        'array_projects' => $array_projects,
        'array_tasks' => $array_tasks,
        'selected_project_id' => 0,
        'content' => '',
        'body_overlay' => '',
        'task_input_form' => ''
    ];
    
    // если выбран проект для показа (параметр project_id)
    if (isset($_GET['project_id'])) {
        $selected_project_id = $_GET['project_id'];
        $project = $array_projects[$selected_project_id];
        if (isset($project)) {
            $main_content_data['array_tasks'] = array_filter($array_tasks, function($task) use($project) {
                return ($project == 'Все' || $task['project'] == $project);
            });
            $layout_data['selected_project_id'] = $selected_project_id;
        } else {
            header('HTTP/1.1 404 Not Found');
            die();
        };
    };
    
    // если инициировано добавление задачи (параметр add)
    if (isset($_GET['add'])) {
        $layout_data['body_overlay'] = ' class=\"overlay\"';
        $layout_data['task_input_form'] = use_template('templates/task_input.php', [
            'errors' => $errors,
            'array_projects' => $array_projects
        ]);
    };
    
    // получение основного содержания
    $main_content = use_template('templates/index.php', $main_content_data);
    $layout_data['content'] = $main_content;
    
    // получение полного текста страницы
    $layout_content = use_template('templates/layout.php', $layout_data);
    
    // вывод текста страницы
    print($layout_content);
    
?>