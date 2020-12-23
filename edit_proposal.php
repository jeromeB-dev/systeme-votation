<?
require_once 'inc/session.inc.php';
$title = "Edition | Votation électronique";
require_once 'inc/header.inc.php';

// filter GET & POST
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
$body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_SPECIAL_CHARS);
$post = filter_input(INPUT_POST, 'post', FILTER_SANITIZE_SPECIAL_CHARS);


// check if user own this proposal
$query = $db->prepare("SELECT * FROM proposals WHERE id = :id AND users_id = :user_id AND submitted = 0;");
$query->execute(array(
 'id' => $id,
 'user_id' => $_SESSION['user_id'],
));
$proposal = $query->fetch();

if (empty($proposal)) {
    $msg->error("Cette proposition ne peut etre modifier.", "dashboard.php");
    exit();
}

// form check
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($title) || empty($body)) {
        $empty_fields = "Tout les champs doivent etre renseigner.";
    } elseif ($post == 'edit') {
        // update proposal
        $query = $db->prepare("UPDATE proposals SET title=:title, body=:body WHERE id = :id");
        $query->execute(array(
          ':id' => $id,
          ':title' => $title,
          ':body' => $body
        ));

        // then redirect
        $msg->success("Vos modifications ont été enregistées", "edit_proposal.php?id=$id");
        exit();

    } elseif ($post =='submit') {
        // submit proposal
        $query = $db->prepare("UPDATE proposals SET submitted = 1 WHERE id = :id");
        $query->execute(array(
          ':id' => $id
        ));

        // then redirect
        $msg->success("Votre proposition a bien été soumise au vote.", "dashboard.php");
        exit();
    }
} else {
    $title = $proposal->title;
    $body = $proposal->body;
}
?>
<main class="bg-light py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h2>Editer la proposition n°<?=$proposal->id?></h2>
            <div><a class="btn btn-sm btn-outline-info" href="dashboard.php"
                    title="Retourner au tableau de bord">Retourner au tableau de bord</a></div>
        </div>
        <hr class="col-6 mx-auto shadow-sm border">
        <div class="container col-6 my-1">
            <?$msg->display();?>
        </div>
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
                        <div class="form-group">
                        </div>
                        <label class="h5" for="body">Détails</label>
                        <textarea class="form-control" id="body" name="body" cols="100" rows="10"
                            placeholder="Détaillez votre proposition ici" required><?=$body?></textarea>
                    </div>
                    <p class="my-2">
                        Une proposition soumise au vote, <b>ne peut plus</b> etre modifiée ou supprimée.
                    </p>
                    <button class="btn btn btn-outline-success" type="submit" name="post" value="edit">Modifier la
                        proposition</button>
                    <button class="btn btn btn-outline-warning" type="submit" name="post" value="submit">Soumettre au
                        vote</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?require_once 'inc/footer.inc.php'?>