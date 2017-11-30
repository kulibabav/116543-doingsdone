CREATE DATABASE doingsdone;
USE doingsdone;
CREATE TABLE projects (
    name CHAR(32) PRIMARY KEY,
    author INT
);
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created DATETIME,
    completed DATETIME,
    name CHAR(128),
    file TEXT,
    deadline DATE,
    author INT,
    project INT
);
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name CHAR(128),
    email CHAR(128),
    password CHAR(32),
    contacts TEXT
);
CREATE UNIQUE INDEX users_email_unique ON users (email);
CREATE INDEX tasks_deadline ON tasks (deadline);
CREATE INDEX tasks_author ON tasks (author);
CREATE INDEX tasks_project ON tasks (project);
CREATE INDEX users_email ON users (email);
