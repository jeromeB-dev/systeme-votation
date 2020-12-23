<?
session_start();
require_once 'inc/header.inc.php';

// init var from post
$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');
// define some displays for user
$login_failed = "Impossible de vous identifier, veuillez réessayer.";
$user_inactive = "Votre compte n'est pas encore activé, verrifiez vos mails et vos spams.";

// prepare query for pass / username comparaison
$query = $db->prepare("SELECT * FROM users WHERE username = :username OR email = :username;");
$query->execute(array(
    ':username' => $username,
));
$user = $query->fetch();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($username == '' || $password == '')) { // if get method or empty inputs
    $msg->warning($login_failed, $base_url);
    exit();
} elseif (is_object($user) && password_verify($password, $user->password)) { // if login and password match
    if (filter_var($user->is_active, FILTER_VALIDATE_BOOLEAN)) { // if account is acive
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_login'] = $user->username;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_authenticated'] = TRUE;
        header("Location: dashboard.php"); /* browser redirect */
        exit();
    } else { // else account inactive
        $msg->warning($user_inactive, $base_url);
        exit();
    }
} else { // else if login or password failed
    $msg->warning($login_failed, $base_url);
    exit();
}