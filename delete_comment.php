<?
require_once 'inc/session.inc.php';
require_once 'inc/header.inc.php';

// filter GET & POST
$pid = filter_input(INPUT_GET, 'pid', FILTER_VALIDATE_INT) ?? FALSE;
$cid = filter_input(INPUT_GET, 'cid', FILTER_VALIDATE_INT) ?? FALSE;

// check if user own this comment
$query = $db->prepare("SELECT * FROM comments WHERE users_id = :user_id AND proposals_id = :pid AND id = :cid;");
$query->execute(array(
    'user_id' => $_SESSION['user_id'],
    'pid' => $pid,
    'cid' => $cid,
));
$comment = $query->fetchAll();

if (count($comment) !== 1) {
    $msg->error("Vous ne pouvez pas supprimer ce commentaire.", "proposal.php?id=$pid");
    exit();
} else {
    $query = $db->prepare("DELETE FROM comments WHERE users_id = :user_id AND proposals_id = :pid AND id = :cid;");
    $query->execute(array(
        ':user_id' => $_SESSION['user_id'],
        ':pid' => $pid,
        ':cid' => $cid,
    ));
    $msg->success("Votre commentaire ($cid) du " . strftime("%d/%m/%Y à %R", strtotime($comment[0]->creation_date)) . " a bien été supprimé.", "proposal.php?id=$pid");
    exit();
}