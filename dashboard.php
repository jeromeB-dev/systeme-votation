<?
require_once 'inc/session.inc.php';
$title = "Tableau de bord | Votation électronique";
require_once 'inc/header.inc.php';

// prepare query to get user proposals
$query = $db->prepare("SELECT * FROM proposals WHERE users_id = :users_id;");
$query->execute(array(
    ':users_id' => $_SESSION['user_id'],
));
$user_proposals = $query->fetchAll();

// prepare query to get submitted proposals
$query = $db->prepare("SELECT proposals.id, users_id, users.username, title, body, approved, rejected, creation_date FROM proposals, users WHERE users_id=users.id AND submitted = 1;");
$query->execute();
$submitted_proposals = $query->fetchAll();
$_SESSION['submitted_proposals'] = $submitted_proposals;

// prepare query to get voted proposals
$query = $db->prepare("SELECT * FROM votes;");
$query->execute();
$voted_proposals = $query->fetchAll();

// check if user already voted
function user_has_voted($proposal_id) {
    global $voted_proposals;
    foreach ($voted_proposals as $vp) {
        if ($vp->users_id == $_SESSION['user_id'] && $vp->proposals_id == $proposal_id) {
            return TRUE;
        }
    }
}
?>
<main class="bg-light py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-md-row flex-column">
            <h2>Tableau de bord</h2>
            <p>
                Bonjour <span class="text-info font-weight-bolder"><?=$_SESSION['user_login']?></span>
                (<?=$_SESSION['user_email']?>)
                <a class="btn btn-sm btn-outline-warning mx-1" href='logout.php' title="Déconnexion">Déconnexion</a>
            </p>
        </div>
        <hr class="col-6 mx-auto shadow-sm border">
        <div class="container col-6 my-1">
            <?$msg->display();?>
        </div>
        <div>
            <h3>Vos propositions</h3>
            <p>Nombre de proposition : <span class="text-info"><?=count($user_proposals)?></span></p>
        </div>
        <div>
            <?if (count($user_proposals) == 0) {?>
            <div>Vous n'avez pas encore créer de propositions.</div>
            <?} else {?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <th scope="col">#</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Soumise au vote</th>
                        <th scope="col">Pour</th>
                        <th scope="col">Contre</th>
                        <th scope="col">Modifier</th>
                        <th scope="col">Supprimer</th>
                    </thead>
                    <tbody>
                        <?foreach ($user_proposals as $proposal) {?>
                        <tr>
                            <td><?=$proposal->id?></td>
                            <td><?=$proposal->title?></td>
                            <td class="font-weight-bolder">
                                <?=($proposal->submitted) ? '<span class="text-success">oui</span>' : '<span class="text-warning">non</span>'?>
                            </td>
                            <td><?=$proposal->approved?></td>
                            <td><?=$proposal->rejected?></td>
                            <?if (!$proposal->submitted) {?>
                            <td><a class="btn btn btn-outline-success" type="button"
                                    href="edit_proposal.php?id=<?=$proposal->id?>">Modifier</a></td>
                            <td><a class="btn btn btn-outline-danger" type="button"
                                    href="delete_proposal.php?id=<?=$proposal->id?>">Supprimer</a></td>
                            <?} else {?>
                            <td></td>
                            <td></td>
                            <?}?>
                        </tr>
                        <?}?>
                    </tbody>
                </table>
            </div>
            <?}?>
            <a class="btn btn btn-outline-primary" href="post_proposal.php" title="Ajouter une proposition">
                Ajouter une proposition
            </a>
            <hr class="col-6 mx-auto shadow-sm border">
        </div>
        <div>
            <h3>Propositions soumises au vote</h3>
        </div>
        <div>
            <?if (count($submitted_proposals) == 0) {?>
            <div>Aucune proposition n'a été encore soumise au vote. </div>
            <?} else {?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <th scope="col">#</th>
                        <th scope="col">Utilisateur</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Pour</th>
                        <th scope="col">Contre</th>
                        <th scope="col">Vous avez déjà voté ?</th>
                        <th scope="col">Voir</th>
                    </thead>
                    <tbody>
                        <?foreach ($submitted_proposals as $proposal) {?>
                        <tr>
                            <td class="align-middle"><?=$proposal->id?></td>
                            <td class="align-middle"><?="$proposal->username <small>($proposal->users_id)</small>"?>
                            </td>
                            <td class="align-middle"><?=$proposal->title?></td>
                            <td class="align-middle"><?=$proposal->approved?></td>
                            <td class="align-middle"><?=$proposal->rejected?></td>
                            <td class="align-middle font-weight-bolder">
                                <?=user_has_voted($proposal->id) ? '<span class="text-success">oui</span>' : '<span class="text-warning">non</span>'?>
                            </td>
                            <td class="align-middle">
                                <a class="btn btn btn-outline-primary" href="proposal.php?id=<?=$proposal->id?>"
                                    title="Voir la proposiiton">
                                    Voir
                                </a>
                            </td>
                        </tr>
                        <?}?>
                    </tbody>
                </table>
            </div>
            <?}?>
        </div>
        <hr class="col-6 mx-auto shadow-sm border">
    </div>
</main>
<?require_once 'inc/footer.inc.php'?>