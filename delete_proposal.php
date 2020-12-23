<?
require_once 'inc/session.inc.php';
require_once 'inc/header.inc.php';

// filter GET
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// check if user own this unsubmitted proposal
$query = $db->prepare("SELECT * FROM proposals WHERE id = :id AND users_id = :user_id AND submitted = 0;");
$query->execute(array(
 'id' => $id,
 'user_id' => $_SESSION['user_id'],
));
$proposal = $query->fetch();

if (empty($proposal)) {
    $msg->error("Cette proposition ne peut etre supprimer.", "dashboard.php");
    exit();
}

// delete own user vote and proposal
$query = $db->prepare("DELETE FROM votes WHERE users_id = :user_id AND proposals_id = :proposals_id");
$query->execute(array(
    'user_id' => $_SESSION['user_id'],
    ':proposals_id' => $id
));

$query = $db->prepare("DELETE FROM proposals WHERE users_id = :user_id AND id = :id");
$query->execute(array(
    'user_id' => $_SESSION['user_id'],
    ':id' => $id
));

// then redirect
$msg->error("Votre proposition a bien été supprimée.", "dashboard.php");
exit();