<?php

    // ОПРЕДЕЛЕНИЕ ФУНКЦИЙ И ПАРАМЕТРОВ СТРАНИЦЫ ПО УМОЛЧАНИЮ
    
    // подключение библиотеки функций
    require_once 'functions.php';
        
    // подключение конфига
    require_once 'config.php';
    
    // заполнение $layout_data, $content_data и $content_template по умолчанию (для неавторизованного пользователя)
    $layout_data = [
        'title' => TITLE,
        'overlay' => '',
        'user' => [],
        'content' => '',
        'form' => ''
    ];
    $content_data = [];
    $content_template = 'templates/guest.php';
    
    // ЧТЕНИЕ ДАННЫХ ИЗ СЕССИИ
    
    // запуск сессии
    session_start();
    
    // если пользователь определен
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        // передаем данные в layout
        $layout_data['user'] = $_SESSION['user'];
        // передаем связанные данные в content и выбираем соответствующий template
        $content_data['array_projects'] = $array_projects;
        $content_data['selected_project_id'] = 0;
        $content_data['array_tasks'] = $array_tasks;
        $content_data['array_tasks_to_show'] = $array_tasks;
        $content_data['show_complete_tasks'] = $show_complete_tasks;
        $content_template = 'templates/index.php';
    };
    
    // ОБРАБОТКА POST ЗАПРОСОВ
    
    // обработка входа под учетной записью
    if  ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
        
        $required = ['email', 'password'];
        $rules = ['email' => 'validateEmail'];
        $errors = validateForm($required, $rules);
        
        // если нет ошибок валидации
        if(!count($errors)) {
            
            // ищем пользователя
            $user_found = false;
            require_once('userdata.php');
            foreach($users as $user_item) {
                // если нашелся user
                if ($_POST['email'] == $user_item['email']) {
                    $user_found = true;
                    // проверяем пароль
                    if (password_verify($_POST['password'], $user_item['password'])) {
                        $_SESSION['user'] = $user_item;
                        header('Location: index.php');
                    } else {
                        $errors['password'] = 'Вы ввели неверный пароль';
                    };
                    break;
                };
            };
            // если не нашелся user
            if (!$user_found) {
                $errors['email'] = 'Пользователь с указанным email не найден';
            };
        };
        
        // если есть ошибки — оставляем форму открытой
        if (count($errors)) {
            $_GET['login'] = true;
        };
        
    };
    
    // обработка добавления задачи
    if ($_SERVER['REQUEST_METHOD'] == 'POST' &&  isset($_POST['task_input']) && isset($user)) {
        
        $required = ['name', 'project_id'];
        $rules = ['date' => 'validateDate'];
        $errors = validateForm($required, $rules);
        
        // если нет ошибок валидации
        if (!count($errors)) {
            
            // добавление задачи в массив
            array_unshift($array_tasks, [
                'name' => $_POST['name'],
                'project' => $array_projects[$_POST['project_id']],
                'date' => $_POST['date'],
                'done' => false
            ]);
            // обновление данных для заполнения шаблона
            $content_data['array_tasks'] = $array_tasks;
            $content_data['array_tasks_to_show'] = $array_tasks;
            // сохранение вложенного файла
            if (isset($_FILES['preview'])) {
                $file_path = __DIR__ . '/' . $_FILES['preview']['name'];
                move_uploaded_file($_FILES['preview']['tmp_name'], $file_path);
            };
            
        // если есть ошибки валидации
        } else {
            
            // форма остается открытой
            $_GET['add'] = true;
        };
    };
    
    // ОБРАБОТКА GET ПАРАМЕТРОВ
    
    // обработка нажатия кнопки "Войти"
    if (isset($_GET['login']) && !isset($user)) {
        $layout_data['overlay'] = ' class="overlay"';
        $layout_data['form'] = use_template('templates/form_login.php', [
            'errors' => $errors
        ]);
    };
    
    // обработка нажатия кнопки "Добавить задачу"
    if (isset($_GET['add']) && isset($user)) {
        $layout_data['overlay'] = ' class="overlay"';
        $layout_data['form'] = use_template('templates/form_task_input.php', [
            'errors' => $errors,
            'array_projects' => $array_projects
        ]);
    };
    
    // если выбран проект для показа (параметр project_id)
    if (isset($_GET['project_id']) && isset($user)) {
        $selected_project_id = $_GET['project_id'];
        $project = $array_projects[$selected_project_id];
        if (isset($project)) {
            $content_data['array_tasks_to_show'] = array_filter($array_tasks, function($task) use($project) {
                return ($project == 'Все' || $task['project'] == $project);
            });
            $content_data['selected_project_id'] = $selected_project_id;
        } else {
            header('HTTP/1.1 404 Not Found');
            die();
        };
    };
    
    // получение основного содержания
    $index_content = use_template($content_template, $content_data);
    $layout_data['content'] = $index_content;
    
    // получение полного текста страницы
    $layout_content = use_template('templates/layout.php', $layout_data);
    
    // вывод текста страницы
    print($layout_content);
    
?>