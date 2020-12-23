<?
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not allowed');
    exit();
};

session_start();
// retrieve user from session
$autenticated = $_SESSION['user_authenticated'] ?? FALSE;

// check if user is autenticated
if ($autenticated !== TRUE) {
    $msg->warning("Vous devez être connecté pour accéder à cette page", $base_url);
    exit();
}