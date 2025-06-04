<?php
// sistema/config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema');
define('DB_USER', 'root');
define('DB_PASS', '');

session_start();

// Funções úteis
function redirect($url) {
    header("Location: $url");
    exit;
}

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
}

function connect_db() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }
    return $conn;
}
?>