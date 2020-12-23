<?
require_once 'inc/session.inc.php';
$title = "Création | Votation électronique";
require_once 'inc/header.inc.php';

// filter GET & POST
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
$body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_SPECIAL_CHARS);

// form check
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($title) || empty($body)) {
        $empty_fields = "Tout les champs doivent etre renseigner.";
    } else {
        // save proposal
        $query = $db->prepare("INSERT INTO proposals (users_id, title, body) VALUES (:user_id, :title, :body)");
        $query->execute(array(
            ':user_id' => $_SESSION['user_id'],
            ':title' => $title,
            ':body' => $body
        ));

        // get new proposal ID
        $proposal_id = $db->lastInsertId();

        // register user vote
        $query = $db->query("INSERT INTO votes (users_id, proposals_id) VALUES ({$_SESSION['user_id']}, $proposal_id)");

        // increment user vote
        $save_vote = $db->prepare("UPDATE proposals SET approved = 1 WHERE id = $proposal_id");
        $save_vote->execute();

        // then redirect
        $msg->success("Votre proposition est enregistrée", "edit_proposal.php?id=$proposal_id");
        exit();
    }
}
?>
<main class="bg-light py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-md-row flex-column">
            <h2>Poster une nouvelle proposition</h2>
            <div><a class="btn btn-sm btn-outline-info" href="dashboard.php" title="Retourner au tableau de bord">Retourner au tableau de bord</a></div>
        </div>
        <hr class="col-6 mx-auto shadow-sm border">
        <div class="container">
            <?if (isset($empty_fields)) {?>
            <div class="alert alert-warning" role="alert"><?=$empty_fields?></div>
            <?}?>
            <div class="card-body border rounded shadow-sm">
                <form action="<?=$_SERVER['REQUEST_URI']?>" method="post">
                    <div class="form-group">
                        <label class="h5" for="title">Titre</label>
                        <input class="form-control" id="title" type="text" name="title"
                            placeholder="Indiquez un titre pour votre proposition" required value="<?=$title?>">
                    </div>
                    <label class="h5" for="body">Détails</label>
                    <textarea class="form-control" id="body" name="body" cols="100" rows="8"
                        placeholder="Détaillez votre proposition ici" required><?=$body?></textarea>
                    <p class="my-2">Lorque vous créez une proposition, vous votez automatiquement <b>pour</b> elle.</p>
                    <button class="btn btn btn-outline-primary" type="submit">Créer la proposition</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?require_once 'inc/footer.inc.php'?>