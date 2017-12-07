<?php

    // ПОДГОТОВКА
    
    // подключаем библиотеки c функциями
    require_once 'functions.php';
    require_once 'mysql_helper.php';   
    
    // инициируем работу с базой данных
    require_once 'init.php';
    
    // подключаем константы и настройки по-умолчанию
    require_once 'config.php';
    
    // инициируем работу с шаблонами
    $content_template = '';
    $content_data = [];
    $layout_data = [
        'title' => TITLE
    ];
    
    // ЗАГРУЖАЕМ ДОПОЛНИТЕЛЬНУЮ ИНФОРМАЦИЮ ИЗ COOKIE
    
    if (isset($_COOKIE['show_completed_tasks'])) {
        $show_completed_tasks = intval($_COOKIE['show_completed_tasks']);
    };
    
    // ИЩЕМ СОХРАНЕННУЮ СЕССИЮ
    
    session_start();
    
    if (isset($_SESSION['user'])) {
        
        // ЕСЛИ ПОЛЬЗОВАТЕЛЬ ОПРЕДЕЛЁН
        
        // сохраняем пользователя в переменную
        $user = $_SESSION['user'];
        // добавляем пользователя в параметры прототипа
        $layout_data['user'] = $user;
        // выбираем шаблон для авторизованного пользователя
        $content_template = 'templates/index.php';
        
        // загружаем проекты пользователя в массив и добавялем в параметры шаблона
        $sql =
          "SELECT "
            . "0 AS id, "
            . "'Все' AS name, "
            . "(SELECT COUNT(tc.id) FROM tasks tc WHERE tc.users_id = $user[id]) AS tasks_count "
        . "UNION ALL "
        . "SELECT "
            . "p.id, "
            . "p.name, "
            . "COUNT(t.id) "
        . "FROM "
            . "projects p LEFT JOIN tasks t ON "
                . "p.users_id = t.users_id "
                . "AND p.id = t.projects_id "        
        . "WHERE "
            . "p.users_id = $user[id] "
        . "GROUP BY "
            . "p.id, p.name";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $array_projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $content_data['array_projects'] = $array_projects;
        } else {
            show_error(mysqli_error($con));
        };
        
        // ОБРАБОТКА ДОБАВЛЕНИЯ ЗАДАЧИ
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' &&  isset($_POST['task_input'])) {
            
            // выполняем валидацию формы
            $required = ['name', 'project_id'];
            $rules = ['date' => 'validateDate'];
            $errors = validateForm($required, $rules);
            
            if (count($errors)) {
                
                // ЕСЛИ ЕСТЬ ОШИБКИ ВАЛИДАЦИИ
                
                // оставляем форму открытой
                $_GET['add'] = true;
                
            } else {
                
                // ЕСЛИ ВАЛИДАЦИЯ ПРОШЛА УСПЕШНО
                
                // сохраняем вложенный файл
                $file_name = '';
                if (isset($_FILES['preview'])) {
                    $file_name = $_FILES['preview']['name'];
                    $file_path = __DIR__ . '\\' . $file_name;
                    move_uploaded_file($_FILES['preview']['tmp_name'], $file_path);
                };
                
                // определяем параметры для подстановки в запрос
                $name = $_POST['name'];
                $file = $file_name;
                $deadline = $_POST['date'];
                $users_id = $user['id'];
                $projects_id = $_POST['project_id'];
                
                // записываем задачу в базу данных
                $sql = "INSERT INTO tasks SET "
                        . "created = NOW(), "
                        . "name = ?, "
                        . "file = '$file', "
                        . "deadline = CASE WHEN '$deadline' = '' THEN NULL ELSE STR_TO_DATE('$deadline', '%d.%m.%Y') END, "
                        . "users_id = $users_id, "
                        . "projects_id = $projects_id";
                $stmt = db_get_prepare_stmt($con, $sql, [$name]);
                $success = mysqli_stmt_execute($stmt);
                if ($success) {
                    // если запись прошла успешно — обновляем страницу
                    header('Location: index.php');
                } else {
                    // если возникли ошибки при записи — выводим ошибку и завершаем сценарий
                    show_error(mysqli_error($con));
                };
                
            };
        };
        
        // ОБРАБОТКА GET-ПАРАМЕТРОВ
        
        // обрабатываем нажатие на чекбокс "Показывать выполненные"
        if (isset($_GET['show_completed'])) {
            // записываем новое значение параметра в cookie
            setcookie('show_completed_tasks', $_GET['show_completed'], strtotime("+30 days"),'/');
            // устанавливаем новое значение параметра сценария
            $show_completed_tasks = intval($_GET['show_completed']);
            //обновляем страницу, убрав параметр
            header('Location: ' . remove_get_param('show_completed'));
        };
        
        // обрабатываем смену статуса выполнения
        if (isset($_GET['change_status'])) {
            $sql = "UPDATE tasks SET completed = CASE "
                        . "WHEN completed IS NULL THEN NOW() "
                        . "ELSE NULL "
                        . "END "
                    . "WHERE "
                        . "id = ? "
                        . "AND users_id = $user[id]";
            $stmt = db_get_prepare_stmt($con, $sql, [$_GET['change_status']]);
            $success = mysqli_stmt_execute($stmt);
            if ($success) {
                // если обновление прошло успешно — обновляем страницу, убрав параметр
                header('Location: ' . remove_get_param('change_status'));
            } else {
                // если возникли ошибки при записи — выводим ошибку и завершаем сценарий
                show_error(mysqli_error($con));
            };
        };
        
        // обрабатываем нажатие кнопки "Добавить задачу"
        if (isset($_GET['add'])) {
            // добавляем в параметры прототипа форму добавления задачи и признак модального режима
            $errors = $errors ?? [];
            $layout_data['form'] = use_template('templates/form_task_input.php', [
                'errors' => $errors,
                'array_projects' => $array_projects
            ]);
            $layout_data['overlay'] = ' class="overlay"';
        };
        
        // определяем id выбранного проекта
        $selected_project_id = 0;
        if (isset($_GET['project_id'])) {
            $selected_project_id = $_GET['project_id'];
            // проверяем, что id проекта находится в массиве
            $project_found = false;
            foreach ($array_projects as $project) {
                if ($project['id'] == $selected_project_id) {
                    $project_found = true;
                    break;
                };
            };
            // если проект не найден — возвращаем код 404 и завершаем сценарий
            if (!$project_found) {
                header('HTTP/1.1 404 Not Found');
                exit;
            };
        };
        // добавляем id проекта в параметры шаблона
        $content_data['selected_project_id'] = $selected_project_id;
        
        // определяем выбранный фильтр по сроку выполнения
        $selected_deadline = 'all';
        if (isset($_GET['deadline'])) {
            $selected_deadline = $_GET['deadline'];
            if (!in_array($selected_deadline, ['all', 'today', 'tomorrow', 'expired'])) {
                // если фильтр не корректный — сбрасываем его на значение по умолчанию
                $selected_deadline = 'all';
            };
        };
        // добавляем фильтр по сроку исполнения в параметры шаблона
        $content_data['selected_deadline'] = $selected_deadline;
        
        // загружаем и добавляем в параметры шаблона массив задач        
        $sql = "SELECT * FROM tasks "
                . "WHERE "
                    . "users_id = $user[id] "
                    . "AND (projects_id = $selected_project_id OR $selected_project_id = 0) "
                    . "AND (completed IS NULL OR $show_completed_tasks > 0) "
                    . "AND ( "
                        . "'$selected_deadline' = 'all' "
                        . "OR ('$selected_deadline' = 'today' AND deadline = CURDATE()) "
                        . "OR ('$selected_deadline' = 'tomorrow' AND deadline = CURDATE() + INTERVAL 1 DAY) "
                        . "OR ('$selected_deadline' = 'expired' AND IFNULL(deadline, CURDATE()) < CURDATE()) AND completed IS NULL "
                    . ")";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $array_tasks = mysqli_fetch_all($result, MYSQLI_ASSOC) ?? [];
            $content_data['array_tasks'] = $array_tasks;
            $content_data['show_completed_tasks'] = $show_completed_tasks;
        } else {
            show_error(mysqli_error($con));
        };
        
    } else {
        
        // ЕСЛИ ПОЛЬЗОВАТЕЛЬ НЕ ОПРЕДЕЛЕН
        
        // выбираем шаблон для гостевого пользователя
        $content_template = 'templates/guest.php';
        
        // обработка входа под учетной записью
        if  ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
            
            $required = ['email', 'password'];
            $rules = ['email' => 'validateEmail'];
            $errors = validateForm($required, $rules);
            
            // если нет ошибок валидации
            if(!count($errors)) {
                
                // ищем пользователя
                $sql = 'SELECT * FROM users WHERE email = ?';
                $stmt = db_get_prepare_stmt($con, $sql, [$_POST['email']]);
                $success = mysqli_stmt_execute($stmt);
                if ($success) {
                    
                    $result = mysqli_stmt_get_result($stmt);
                    if ($user_found = mysqli_fetch_assoc($result)) {
                        if (password_verify($_POST['password'], $user_found['password'])) {
                            $_SESSION['user'] = $user_found;
                            header('Location: index.php');
                        } else {
                            $errors['password'] = 'Вы ввели неверный пароль';
                        };
                    } else {
                        $errors['email'] = 'Пользователь с указанным email не найден';
                    };
                
                } else {
                    show_error(mysqli_error($con));
                };
                
            };
            
            // если есть ошибки — оставляем форму открытой
            if (count($errors)) {
                $_GET['login'] = true;
            };
            
        };
        
        // ОБРАБОТКА GET-ПАРАМЕТРОВ
        
        // обрабатываем нажатие кнопки "Войти"
        if (isset($_GET['login'])) {
            // добавляем в параметры прототипа форму авторизации и признак модального режима
            $errors = $errors ?? [];
            $layout_data['form'] = use_template('templates/form_login.php', ['errors' => $errors]);
            $layout_data['overlay'] = ' class="overlay"';
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