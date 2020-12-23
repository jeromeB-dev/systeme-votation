<?
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not allowed');
    exit();
};

if (!defined('PROJECT_NAME')) {define('PROJECT_NAME', 'systeme-votation');}
include_once '../../env/' . PROJECT_NAME . '/env.php';
$base_url = BASE_URL;
$SMTP_AUTH = SMTP_AUTH;