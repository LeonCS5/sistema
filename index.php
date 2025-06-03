<?php
// sistema/index.php
require 'config.php';

if (is_logged_in() && is_admin()) {
    redirect('admin/index.php');
} else if (is_logged_in()) {
    redirect('user/index.php');
} else {
    redirect('public/index.php');
}
?>