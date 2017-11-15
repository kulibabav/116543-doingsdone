<?php

	// подключение библиотеки функций
	require_once 'functions.php';

	// подсчет числа задач по проекту
	function project_tasks_count ($array_tasks, $str_project) {
		
		$int_result = 0;
		
		if ($str_project == 'Все') {
			$int_result = count($array_tasks);
		} else {
			foreach($array_tasks as $task) {
				if ($str_project == 'Все' || $task['project'] == $str_project) {
					$int_result++;
				};
			};
		};
		
		return $int_result;
		
	}
	
	// показывать или нет выполненные задачи
	$show_complete_tasks = rand(0, 1);

	// устанавливаем часовой пояс в Московское время
	date_default_timezone_set('Europe/Moscow');

	$days = rand(-3, 3);
	$task_deadline_ts = strtotime("+" . $days . " day midnight"); // метка времени даты выполнения задачи
	$current_ts = strtotime('now midnight'); // текущая метка времени

	// запишите сюда дату выполнения задачи в формате дд.мм.гггг
	$date_deadline = date("d.m.Y", $task_deadline_ts);

	// в эту переменную запишите кол-во дней до даты задачи
	$days_until_deadline = floor(($task_deadline_ts - $current_ts) / 86400);

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