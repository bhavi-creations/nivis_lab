<?php
require_once __DIR__ . '/customer_auth.php';
if (empty($_SESSION['customer_id'])) {
    header('Location: login.php');
    exit;
}
