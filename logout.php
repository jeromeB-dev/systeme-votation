<?
require_once 'inc/header.inc.php';

// $autenticated = $_SESSION['user_authenticated'] ?? false;
// $type = ($autenticated) ? "info" : "warning";
// $message = ($autenticated) ? "avez été" : "êtes déjà";
// $msg->info("Vous $message déconnecté.");

session_destroy();
header("Location: $base_url");
exit(); 