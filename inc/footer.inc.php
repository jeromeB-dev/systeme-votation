<?
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    die('Direct access not allowed');
    exit();
};

$stats_users = $db->query("SELECT is_active FROM users;");
?>
<footer>
    <div class="container mt-3 text-center">
        <p class="my-1">Actuellement <span class="font-weight-bolder text-info"><?=$stats_users->rowcount();?></span>
            personnes nous font
            confiance.
            Merci a vous tous !</p>
        <p class="my-1">&copy;
            <a href="http://cv.jerome-bor.fr" title="cv.jerome-bor.fr">
                cv.jerome-bor.fr
            </a>
            - <?=date('Y')?>
        </p>
        <div>
            <hr>Crédit image :
            <br>Icons made by <a href="https://www.flaticon.com/authors/freepik" title="Freepik">Freepik</a> from <a
                href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a>
            <!-- <br><a href='https://fr.freepik.com/photos/main'>Main photo créé par wavebreakmedia_micro -
                fr.freepik.com</a>
            <br><span>Photo by <a
                    href="https://unsplash.com/@mr_williams_photography?utm_source=unsplash&amp;utm_medium=referral&amp;utm_content=creditCopyText">Micah
                    Williams</a> on <a
                    href="https://unsplash.com/?utm_source=unsplash&amp;utm_medium=referral&amp;utm_content=creditCopyText">Unsplash</a></span> -->
        </div>
    </div>
</footer>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
</body>

</html>