<?php
require_once __DIR__ . '/customer_auth.php';
require_once __DIR__ . '/customer_backend.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'Method not allowed.']); exit; }
if (($_SESSION['auth_backend'] ?? '') !== 'evershop' || empty($_SESSION['backend_access_token'])) { http_response_code(401); echo json_encode(['error'=>'Please log in again.']); exit; }
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) { http_response_code(400); echo json_encode(['error'=>'Invalid request.']); exit; }
if (!isset($_SESSION['csrf']) || !is_string($input['csrf'] ?? null) || !hash_equals($_SESSION['csrf'], $input['csrf'])) { http_response_code(403); echo json_encode(['error'=>'Your session expired. Reload the account page and try again.']); exit; }
try {
    $profile = customer_backend_update_profile($input);
    $_SESSION['customer_name'] = $profile['fullName'] ?? trim((string)$input['full_name']);
    $_SESSION['customer_email'] = $profile['email'] ?? trim((string)$input['email']);
    echo json_encode(['success'=>true]);
} catch (CustomerBackendException $error) {
    http_response_code($error->getCode() >= 400 && $error->getCode() <= 599 ? $error->getCode() : 400);
    echo json_encode(['error'=>$error->getMessage()]);
} catch (Throwable $error) {
    error_log('Account update failed: ' . $error->getMessage());
    http_response_code(503);
    echo json_encode(['error'=>'Unable to save your account details right now. Check the PHP/server error log.']);
}
