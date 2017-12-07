<?php

    // ОПРЕДЕЛЕНИЕ ФУНКЦИЙ И ПАРАМЕТРОВ СТРАНИЦЫ ПО УМОЛЧАНИЮ
    
    // подключение библиотек c функциями
    require_once 'functions.php';
    require_once 'mysql_helper.php';   
    
    // инициализация работы с базой данных
    require_once 'init.php';
    
    // подключение конфига
    require_once 'config.php';
    
    // заполнение $layout_data, $content_data и $content_template по умолчанию (для неавторизованного пользователя)
    $layout_data = [
        'title' => 'Регистрация аккаунта',
        'overlay' => '',
        'user' => [],
        'content' => '',
        'form' => ''
    ];
    $content_data = [];
    $content_template = 'templates/register.php';
    
    // ЧТЕНИЕ ДАННЫХ ИЗ СЕССИИ
    
    // запуск сессии
    session_start();
    
    // если пользователь определен
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        // передаем данные в layout
        $layout_data['user'] = $_SESSION['user'];
    };
    
    // ОБРАБОТКА POST ЗАПРОСОВ
    
    // обработка регистрации
    if  ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
        
        $required = ['email', 'password', 'name'];
        $rules = ['email' => 'validateEmail'];
        $errors = validateForm($required, $rules);
        
        // если нет ошибок валидации
        if(!count($errors)) {
            // ищем пользователя
            $sql = 'SELECT email FROM users WHERE email = ?';
            $stmt = db_get_prepare_stmt($con, $sql, [$_POST['email']]);
            $success = mysqli_stmt_execute($stmt);
            if (!$success) {
                print(use_template('templates/error.php', ['error_text' => mysqli_error($con)]));
                exit;
            } elseif (mysqli_num_rows(mysqli_stmt_get_result($stmt))) {
                $errors['email'] = 'Указанный email уже зарегистрирован';
            };
        };
        
        // если нет ошибок — добавляем пользователя и переадресуем на страницу входа
        if (!count($errors)) {
            $sql = 'INSERT INTO users SET email = ?, password = ?, name = ?';
            $stmt = db_get_prepare_stmt($con, $sql, [$_POST['email'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['name']]);
            $success = mysqli_stmt_execute($stmt);
            if ($success) {
                header('Location: index.php?login=true');
            } else {
                print(use_template('templates/error.php', ['error_text' => mysqli_error($con)]));
                exit;
            };
        } else {
            $content_data['errors'] = $errors;
        };
        
    };
    
    // получение основного содержания
    $register_content = use_template($content_template, $content_data);
    $layout_data['content'] = $register_content;
    
    // получение полного текста страницы
    $layout_content = use_template('templates/layout.php', $layout_data);
    
    // вывод текста страницы
    print($layout_content);
    
?>