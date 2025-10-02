<?php get_header() ?>
<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

?>
<?php

/*
if (isPost()) {
    if (!empty($_POST['texte'])) {
        $texte = htmlspecialchars($_POST['texte']);
        $_SESSION['name'] = $texte; 
        
        header("Location: page1.php");
        exit;
    } else {
        header("Location: index.php");
        exit;
    }
}
 * 
 */
?>


    <div class="container text-center">
        <img   src="<?= esc_url( get_theme_file_uri('assets/images/debut-aventure.jpg') ); ?>" class="img-fluid full_div mt-5" alt="">
        <div class="border mt-3">
            <p class="mt-3">Bonjour aventurier, quelle est ton nom ?</p>
        </div>
        <form method="post">
            <div class="mt-3">
                <label for="texte" class="form-label">Entrez votre texte :</label><br>
                <textarea id="texte" name="texte" rows="4" class="form-control"></textarea>
            </div>
            <div class="mt-3 mb-5">
                <input class="btn btn-primary" type="submit" value="Valider">
            </div>
        </form>
    </div>
<?php get_footer();
