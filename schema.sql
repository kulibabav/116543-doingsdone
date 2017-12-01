CREATE DATABASE doingsdone;
USE doingsdone;
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name CHAR(32),
    author INT FOREIGN KEY REFERENCES users (id)
);
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created DATETIME,
    completed DATETIME,
    name CHAR(128),
    file TEXT,
    deadline DATE,
    users_id INT FOREIGN KEY REFERENCES users (id),
    projects_id INT FOREIGN KEY REFERENCES projects (id)
);
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name CHAR(128),
    email CHAR(128),
    password CHAR(32),
    contacts TEXT
);
CREATE UNIQUE INDEX users_email ON users (email);
CREATE INDEX tasks_name ON tasks (name);
CREATE INDEX tasks_deadline ON tasks (deadline);