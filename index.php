<?
session_start();
$title = "Accueil | Votation électronique";
require_once 'inc/header.inc.php';
$referer = $_SERVER['HTTP_REFERER'] ?? '';
if ($referer == $base_url . 'dashboard.php') {
    $msg->info("Vous avez été déconnecté.");
}
?>
<main>
    <div class="container text-center">
        <p class="h2 font-weight-normal">Bienvenue cher visiteur !</p>
        <p class="lead">Voici notre systeme de votation en ligne. <br>
            Vous avez une idée, une proposition, une suggestion, un projet ou simplement un vote que aimeriez présenter
            a la communauté ? <br>
            Et où tout le monde peut débatre et voter ? Alors n'hésitez plus connectez-vous et postez vos idées ! <br>
        </p>
    </div>
    <div class="container">
        <div class="container col-6 my-1">
            <?$msg->display();?>
        </div>
        <div class="card-deck">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="text-center">Se connecter</h3>
                </div>
                <div id="connection" class="card-body">
                    <form class="d-flex flex-column h-100" action="connection.php" method="post">
                        <div class="form-group">
                            <label>Utilisateur ou email</label>
                            <input class="form-control" type="text" name="username"
                                placeholder="Saisir votre nom d'utilisateur">
                        </div>
                        <div class="form-group">
                            <label>Mot de passe</label>
                            <input class="form-control" type="password" name="password"
                                placeholder="Saisir votre mot de passe">
                        </div>
                        <div class="mt-auto">
                            <input class="btn btn-lg btn-block btn-outline-primary" type="submit" value="Connexion">
                        </div>
                    </form>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="text-center">Pas encore inscrit(e)
                        <i class="align-middle far fa-question-circle btn btn-lg p-0" data-toggle="tooltip"
                            data-html="true" title="Pour pouvoir participer et voter sur notre plateforme, vous devez d'abord
                    vous incrire.">
                        </i>
                    </h3>
                </div>
                <div id="registration" class="card-body">
                    <form action="registration.php" method="post">
                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" type="text" name="newUserEmail"
                                placeholder="votre.email@votrefournisseur.com">
                        </div>
                        <div class="form-group">
                            <label>Utilisateur</label>
                            <input class="form-control" type="text" name="newUsername"
                                placeholder="Votre nom d'utilisateur">
                        </div>
                        <div class="form-group">
                            <label>Mot de passe</label>
                            <input class="form-control" type="password" name="newUserPassword"
                                placeholder="Un mot de passe">
                        </div>
                        <div>
                            <input class="btn btn-lg btn-block btn-outline-primary" type="submit" value="S'inscrire">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?require_once 'inc/footer.inc.php'?>