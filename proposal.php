<?
require_once 'inc/session.inc.php';
$title = "Proposition | Votation électronique";
require_once 'inc/header.inc.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// prepare query to get submitted proposals
$query = $db->prepare("SELECT * FROM proposals WHERE submitted = 1 AND id = :id;");
$query->execute(array(
    ':id' => $id,
));
$proposal = $query->fetch();

// check if current proposal is submited
if ($proposal === FALSE) {
    $msg->warning("Vous ne pouvez pas voir cette proposition", 'dashboard.php');
    exit();
}

// prepare query to check if user has voted current proposal
$query = $db->prepare("SELECT * FROM votes WHERE users_id = {$_SESSION['user_id']} AND proposals_id = $id;");
$query->execute();
$user_has_voted = $query->fetch();

// prepare query to get comments of current proposal
$query = $db->prepare("SELECT comments.id, users_id, username, body, creation_date FROM comments, users WHERE users_id=users.id AND proposals_id = $id;");
$query->execute();
$comments = $query->fetchAll();

// manage posted votes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $user_has_voted == FALSE) {
    $user_vote = TRUE;
    $vote = NULL;
    $vote = array_key_exists('approved', $_POST) ? 'approved' : (array_key_exists('rejected', $_POST) ? 'rejected' : NULL);

    if (!isset($vote)) {
        $msg->error("Ce vote ne peut etre pris en compte, veuillez réessayer", 'dashboard.php');
        exit();
    } else {
        // save user vote in votes table
        $save_user_vote = $db->prepare("INSERT INTO votes (users_id, proposals_id) VALUES ({$_SESSION['user_id']}, $id)");
        $save_user_vote->execute();
        
        // increment user vote in proposals table
        $save_vote = $db->prepare("UPDATE proposals SET $vote=:vote WHERE id = $id");
        $save_vote->execute(array(
        ':vote' => $proposal->$vote + 1,
        ));

        $msg->success("Votre vote sur la proposition n° $id a bien été bien pris en compte.", 'dashboard.php');
        exit();
    }

}
?>
<main class="bg-light py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-md-row flex-column">
            <h2>Proposition n° <?=$proposal->id?></h2>
            <div><a class="btn btn-sm btn-outline-info" href="dashboard.php"
                    title="Retourner au tableau de bord">Retourner au tableau de bord</a></div>
        </div>
        <hr class="col-6 mx-auto shadow-sm border">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h3 class="h4 m-0"><?=$proposal->title?></h3>
                </div>
                <div class="card-body text-center lead">
                    <p><?=$proposal->body?></p>
                </div>
                <div class="card-footer d-flex justify-content-around p-1">
                    <h4 class="h5 m-0 align-self-center">Votes</h4>
                    <ul class="m-0">
                        <li>Pour : <?=$proposal->approved?></li>
                        <li>Contre : <?=$proposal->rejected?></li>
                    </ul>
                </div>
            </div>
            <div class="form-group text-center my-2">
                <?if ($user_has_voted !== FALSE) {?>
                <div class="col-sm-auto col-md-6 mx-auto alert alert-info" role="alert">Vous avez déjà voté pour cette
                    proposition.
                </div>
                <?} else {?>
                <form action="<?=$_SERVER['REQUEST_URI']?>" method="post">
                    <input class="btn btn btn-success" type="submit" name="approved" value="Voter pour">
                    <input class="btn btn btn-danger" type="submit" name="rejected" value="Voter contre">
                </form>
                <?}?>
            </div>
            <hr class="col-6 mx-auto shadow-sm border">
            <div>
                <div class="container col-sm-auto col-md-6 my-1">
                    <?$msg->display();?>
                </div>
                <div class="card-body border rounded shadow-sm">
                    <?if (count($comments) == 0) {?>
                    <div class="text-muted"><small>Il n'y aucun commentaire pour l'instant.</small></div>
                    <?}?>
                    <form action="post_comment.php?pid=<?=$proposal->id?>" method="post">
                        <label class="h5" for="body">Ajouter un commentaire</label>
                        <textarea class="form-control my-2" id="body" name="body" cols="100" rows="5"
                            placeholder="saisissez votre commentaire"></textarea>
                        <button class="btn btn btn-outline-success" type="submit">Publier</button>
                    </form>
                </div>
                <hr class="col-6 mx-auto shadow-sm border">
                <div class="container">
                    <?if (count($comments) > 0) {?>
                    <h4>Liste des commentaires</h4>
                    <div class="">
                        <?foreach ($comments as $comment) {?>
                        <div class="card my-2 shadow-sm">
                            <div class="card-body py-2">
                                <div class="card-title h5 mb-1">
                                    De : <span class="font-weight-normal"><?=$comment->username?></span>
                                    <small class="font-weight-light">(<?=$comment->users_id?>)</small>
                                </div>
                                <p class="card-text font-italic text-secondary mb-1 mx-2"><?=$comment->body?></p>
                                <div class="d-flex justify-content-around flex-md-row flex-column">
                                    <p class="card-text m-0"><small
                                            class="text-muted"><?=strftime("Le %d/%m/%Y à %R", strtotime($comment->creation_date))?></small>
                                    </p>
                                    <?if ($comment->users_id === $_SESSION['user_id']) {?>
                                    <a href="delete_comment.php?cid=<?=$comment->id?>&pid=<?=$proposal->id?>"
                                        title="Supprimer mon commentaire">
                                        Supprimer mon commentaire
                                    </a>
                                    <?}?>
                                </div>
                            </div>
                        </div>
                        <?}?>
                    </div>
                    <?}?>
                </div>
            </div>
        </div>
    </div>
</main>
<?require_once 'inc/footer.inc.php'?>