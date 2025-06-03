<?php
// sistema/logout.php
require 'config.php';

session_destroy();
redirect('public/index.php');