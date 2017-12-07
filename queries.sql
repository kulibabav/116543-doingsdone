USE doingsdone;

# Заполнение пользователей        
INSERT INTO users (email, name, password)
VALUES
    ('ignat.v@gmail.com', 'Игнат', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka'),
    ('kitty_93@li.ru', 'Леночка', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa'),
    ('warrior07@mail.ru', 'Руслан', '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW');

# Заполнение проектов
INSERT INTO projects (name, users_id)
SELECT
    p.name,
    u.id
FROM
    (SELECT 'Входящие' AS name
    UNION SELECT 'Учеба'
    UNION SELECT 'Работа'
    UNION SELECT 'Домашние дела'
    UNION SELECT 'Авто') p
    LEFT JOIN users u ON TRUE;

# Заполнение задач
INSERT INTO tasks (created, completed, name, deadline, users_id, projects_id)
SELECT
    NOW(),
    t.completed,
    t.name,
    t.deadline,
    u.id,
    t.projects_id + (u.id - 1) * 5
FROM
    (SELECT
        NULL AS completed,
        'Собеседование в IT компании' AS name,
        STR_TO_DATE('01.06.2018', '%d.%m.%Y') AS deadline,
        3 AS projects_id
    UNION SELECT
        NULL,
        'Выполнить тестовое задание',
        STR_TO_DATE('25.05.2018', '%d.%m.%Y'),
        3
    UNION SELECT
        STR_TO_DATE('2018-04-20 14:20:00', '%Y-%m-%d %H:%i:%s'),
        'Сделать задание первого раздела',
        STR_TO_DATE('21.04.2018', '%d.%m.%Y'),
        2
    UNION SELECT
        NULL,
        'Встреча с другом',
        STR_TO_DATE('22.04.2018', '%d.%m.%Y'),
        1
    UNION SELECT
        NULL,
        'Купить корм для кота',
        NULL,
        4
    UNION SELECT
        NULL,
        'Заказать пиццу',
        NULL,
        4) t
    LEFT JOIN users u ON TRUE;
    
# Получить список из всех проектов для одного пользователя
SELECT * FROM projects
WHERE users_id = 1;
    
# Получить список из всех задач для одного проекта
SELECT * FROM tasks
WHERE projects_id = 2;

# Пометить задачу как выполненную
UPDATE tasks SET completed = NOW() WHERE id = 1;

# Получить все задачи для завтрашнего дня
SELECT * FROM tasks WHERE deadline = CURDATE() + INTERVAL 1 DAY;

# Обновить название задачи по её идентификатору
UPDATE tasks SET name = 'name_provided' WHERE id = 1;