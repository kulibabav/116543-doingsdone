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
    function is_soon($str_date, $int_days) {
        $bool_result = false;
        if (!empty($str_date)) {
            $date = date_create_from_format('d.m.Y', $str_date);
            if ($date !== false) {
                $now = date_create();
                $bool_result = $date < $now || date_diff($date, $now)->days <= $int_days;
            };
        };
        return $bool_result;
    }
    
    // подсчет числа элементов массива с указанным значением свойства
    function property_items_count ($array, $property_name, $property_value) {
        $int_result = 0;
        if ($property_value == 'Все') {
            $int_result = count($array);
        } else {
            foreach($array as $item) {
                if ($item[$property_name] == $property_value) {
                    $int_result++;
                };
            };
        };
        return $int_result;
    }
    
    // проверка даты
    function validateDate($value, $format = 'd.m.Y')
    {
        $d = date_create_from_format($format, $value);
        return $d && $d->format($format) == $value;
    }
    
?>