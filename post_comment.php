<?
require_once 'inc/session.inc.php';
require_once 'inc/header.inc.php';

// filter GET & POST
$pid = filter_input(INPUT_GET, 'pid', FILTER_VALIDATE_INT);
$body = filter_input(INPUT_POST, 'body', FILTER_DEFAULT);

// check if proposal is submitted
function proposal_is_submitted($proposal_id) {
    foreach ($_SESSION['submitted_proposals'] as $sp) {
        if ($sp->id == $proposal_id) {return TRUE;}
    }
}

// security check
if (!isset($pid) || empty($pid) || !proposal_is_submitted($pid) || !isset($body)) {
    $msg->error("Ce commentaire ne peut etre pris en compte, veuillez réessayer.", 'dashboard.php');
    exit();
} elseif (empty($body)) {
    $msg->warning("Vous ne pouvez pas poster de commentaire vide.", "proposal.php?id=$pid");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Save comment
    $query = $db->prepare("INSERT INTO comments (users_id, proposals_id, body) VALUES (:users_id, :proposals_id, :body)");
    $query->execute(array(
    ':users_id' => $_SESSION['user_id'],
    ':proposals_id' => $pid,
    ':body' => $body
    ));
    // redirect to proposal 
    $msg->success("Vous commentaire a bien été enregistré.", "proposal.php?id=$pid");
    exit();
}