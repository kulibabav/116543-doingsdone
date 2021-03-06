<?php
    // заполнение PHP-шаблона данными
    function use_template($str_path, $array_data) {
        $str_result = '';
        if (file_exists($str_path)) {
            extract($array_data);
            ob_start();
            require_once($str_path);
            $str_result = ob_get_clean();
        };
        return $str_result;
    }
    
    // проверка, что дата находится в прошлом или настоящем
    function is_soon($date, $int_days) {
        $bool_result = false;
        if ($date != null) {
            $date = date_create($date);
            $now = date_create();
            $bool_result = $date < $now || date_diff($date, $now)->days <= $int_days;
        };
        return $bool_result;
    }
    
    // валидация формы
    function validateForm($required, $rules) {
        $errors = [];
        foreach ($_POST as $key => $value) {
            if (in_array($key, $required) && $value == '') {
                $errors[$key] = 'Пожалуйста, заполните это поле';
                continue;
            };
            if (array_key_exists($key, $rules) && $value <> '') {
                $result = call_user_func($rules[$key], $value);
                if (!$result) {
                    $errors[$key] = 'Пожалуйста, укажите корректное значение';
                };
            };
        };
        return $errors;
    }
    
    // проверка даты
    function validateDate($value, $format = 'd.m.Y') {
        $d = date_create_from_format($format, $value);
        return $d && $d->format($format) == $value;
    }
    
    // проверка email
    function validateEmail($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
    
    // добавление GET-параметра в адресную строку
    function add_get_param($name, $value) {
        $url = strtok($_SERVER["REQUEST_URI"],'?') . '?' . $name . '=' . $value;
        foreach($_GET as $var_name => $var_value) {
            if ($var_name != $name) {
                $url = $url . '&' . $var_name . '=' . $var_value ;
            };
        };
        return $url;
    }
    
    // удаление GET-параметра из адресной строки
    function remove_get_param($name) {
        $url = strtok($_SERVER["REQUEST_URI"],'?');
        $delimiter = '?';
        foreach($_GET as $var_name => $var_value) {
            if ($var_name != $name) {
                $url = $url . $delimiter . $var_name . '=' . $var_value ;
                $delimiter = '&';
            };
        };
        return $url;
    }
    
    // вывод сообщения об ошибке
    function show_error($error_text) {
        print(use_template('templates/error.php', ['error_text' => $error_text]));
        exit();
    };
    
?>