<?php

    // подключение библиотеки функций
    require_once 'functions.php';
	
	// устанавливаем часовой пояс в Московское время
    date_default_timezone_set('Europe/Moscow');
	
    // массив проектов
    $array_projects = ['Все', 'Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];

    // массив задач
    $array_tasks = [
        [
            'name' => 'Собеседование в IT компании',
            'date' => '01.06.2018',
            'project' => 'Работа',
            'done' => false
        ],
        [
            'name' => 'Выполнить тестовое задание',
            'date' => '25.05.2018',
            'project' => 'Работа',
            'done' => false
        ],
        [
            'name' => 'Сделать задание первого раздела',
            'date' => '21.04.2018',
            'project' => 'Учеба',
            'done' => true
        ],
        [
            'name' => 'Встреча с другом',
            'date' => '22.04.2018',
            'project' => 'Входящие',
            'done' => false
        ],
        [
            'name' => 'Купить корм для кота',
            'date' => 'Нет',
            'project' => 'Домашние дела',
            'done' => false
        ],
        [
            'name' => 'Заказать пиццу',
            'date' => 'Нет',
            'project' => 'Домашние дела',
            'done' => false
        ]
    ];
    
	// показывать или нет выполненные задачи
    $show_complete_tasks = rand(0, 1);

    $main_content = use_template('templates/index.php', [
        'show_complete_tasks' => $show_complete_tasks,
        'array_tasks' => $array_tasks
    ]);
    
    $layout_content = use_template('templates/layout.php', [
        'title' => 'Дела в порядке',
        'array_projects' => $array_projects,
        'array_tasks' => $array_tasks,
        'content' => $main_content
    ]);
    
    print($layout_content);
    
?>