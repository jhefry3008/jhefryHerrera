<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
