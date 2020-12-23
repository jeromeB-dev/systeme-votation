<?
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not allowed');
    exit();
};

if (!defined('PROJECT_NAME')) {define('PROJECT_NAME', 'systeme-votation');}
include_once '../../env/' . PROJECT_NAME . '/env.php';
$host_name = DB_HOST;
$database = DB_NAME;
$user_name = DB_LOGIN;
$password = DB_PASS;
$base_url = BASE_URL;

try {
    $db = new PDO("mysql:host=$host_name; dbname=$database;", $user_name, $password);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (PDOException $e) {
    echo "Erreur!: " . $e->getMessage() . "<br/>";
    die();
}