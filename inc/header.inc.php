<?
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not allowed');
    exit();
};

require_once 'inc/db-connect.inc.php';
require './vendor/Plasticbrain/FlashMessages.php';
if (!isset($msg)) {$msg = new \Plasticbrain\FlashMessages\FlashMessages();}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap part -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
    <!-- Bootstrap part end -->
    <link rel="stylesheet" href="css/systeme-votation.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <meta name="description"
        content="Votation électronique, Demo website, dev.jerome-bor.fr, démocatie 2.0, vote, proposition, idée, suggestion, projet">
    <?
    $title = isset($title) ? $title : 'Votation électronique';
    echo "<title>$title</title>";
    ?>
</head>

<body>
    <header>
        <div class="container text-center my-2">
            <img class="m-2 p-2 bg-info rounded-lg" src="img/online-voting.svg" alt="Vote en ligne">
            <h1 class="mb-0">Démocratie 2.0</h1>
            <p class="h6 text-secondary font-weight-normal">(TP pour travailler PDO en PHP)</p>
        </div>
    </header>