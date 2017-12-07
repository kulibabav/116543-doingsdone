<?php

$con = mysqli_connect('doingsdone', 'root', '', 'doingsdone');
if (!$con) {
    print(use_template('templates/error.php', ['error_text' => mysqli_connect_error()]));
    exit;
};

?>