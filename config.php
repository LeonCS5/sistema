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

function add_svg($path, $class, $sp){
    $svg = file_get_contents($path);
        // Adiciona a classe dentro da tag <svg>
    $class = '<svg class="' . $class . '"';
    $svg = str_replace('<svg', $class, $svg);
    $svg = preg_replace('/stroke="[^"]*"/i', 'stroke="currentColor"', $svg);
    
    if($sp){
        $svg = preg_replace('/fill="[^"]*"/i', '', $svg);
        $svg = preg_replace("/fill='[^']*'/i", '', $svg);
    }

    return  $svg;

}

?>