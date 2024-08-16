<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: /login.php');
    exit;
}