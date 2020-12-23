<?
session_start();
require_once 'inc/header.inc.php';
require_once 'inc/send-mail.inc.php';

// init var from post
$user_email = filter_input(INPUT_POST, 'newUserEmail');
$user_name = filter_input(INPUT_POST, 'newUsername');
$user_password = filter_input(INPUT_POST, 'newUserPassword');

// define some displays for user
$empty_inputs = "Tout les champs doivent être renseigner, veuillez réessayer.";
$registration_failed = "Nom d'utilisateur ou email est déjà utilisé, veuillez réessayer.";

// prepare query for email / username comparaison
$query = $db->prepare("SELECT * FROM `users` WHERE (email = :email OR username = :username)");
$query->execute(array(
    ':email' => $user_email,
    ':username' => $user_name,
));
$user_exist = $query->fetch();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($user_email == '' || $user_name == '' || $user_password == '')) {

    $msg->warning($empty_inputs, $base_url);// if get method or empty inputs
    exit(); 

} elseif (!is_object($user_exist)) { // if email or username don't exist in DB then register user

    $create_user = $db->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
    $user_password_hashed = password_hash($user_password, PASSWORD_DEFAULT);
    $create_user->execute(array(
        ':email' => $user_email,
        ':username' => $user_name,
        ':password' => $user_password_hashed,
    ));

    if ($create_user->errorCode() == '00000') { // check query result

        $id = $db->lastInsertId();
        $validation_link = "{$_SERVER['HTTP_REFERER']}email_validation.php?id=$id&token=$user_password_hashed";

        // message body start
        $message = "<p>Bonjour $user_name et bienvenue sur notre site : <a href='$base_url' title='$base_url' target='_blank'>$base_url</a></p>";
        $message .= "<p>Pour finaliser votre inscription, merci de cliquer sur le lien suivant : <br>";
        $message .= "<a href='$validation_link' title='Activer mon compte' target='_blank'>Activer mon compte</a>";
        $message .= "</p><p>Si vous ne voyez pas le lien ci-dessus vous pouvez copier-coller celui-ci après dans votre barre d'adresse : ";
        $message .= "<br>$validation_link<br></p><p>Merci pour votre inscription :)</p>";
        // message body end

        $result_sendmail = sendmail('[vote 2.0] Finalisez votre inscription', $message, $user_email);

        $success = $result_sendmail;
        $success .= "Bienvenue chez nous <b>$user_name</b>, votre compte à bien été créer. <br>";
        $success .= "Pensez à l'activer à l'aide du lien reçu par mail (vérifiez vos spams)";
        $msg->success($success, $base_url);// if get method or empty inputs
        exit(); 


    } else { // else send error if query failed

        $msg->error("Une erreur s'est produite pendant l'enregistrement.", $base_url);
        exit();
    }

} else { // else if email or username already exist in DB send fail msg

    $msg->warning($registration_failed, $base_url);// if get method or empty inputs
    exit(); 

}