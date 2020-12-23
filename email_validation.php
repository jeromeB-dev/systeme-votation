<?
session_start();
require_once 'inc/header.inc.php';

// var def
$id = $_GET['id'] ?? '';
$token = $_GET['token'] ?? '';
$status = '';

// prepare query for id / password comparaison
$query = $db->prepare("SELECT * FROM users WHERE (id = :id AND password = :token AND is_active = 0)");
$query->execute(array(
    ':id' => $id,
    ':token' => $token,
));
$user = $query->fetch();

if (is_object($user)) { // if user found in DB active it

    $update_user = $db->query("UPDATE users SET is_active = 1 WHERE id = $id;");
    $update_user->execute();

    if ($update_user->errorCode() == '00000') { // check query result

        $message = "<h3>Votre inscription est terminée !</h3>";
        $message .= "<p>Nous somme heureux de vous revoir <b>$user->username</b></p>";
        $msg->success($message, $base_url);
        exit(); 

    } else { // else send error if query failed

        $message = "Une erreur s'est produite pendant l'activation de votre compte.";
        $msg->error($message, $base_url);
        exit(); 
    }

} else { // if couple user + pass + is_active not found send this

    $message = "<h3>Le lien que vous avez suivi ne semble plus être valide</h3>";
    $message .= "<p>Vérifiez vos emails, ou contactez l'administrateur.</p>";
    $msg->warning($message, $base_url);
    exit(); 
}