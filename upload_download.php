<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire d'upload de fichiers</title>
</head>
<body>
<h2>Telecharger un fichier</h2>
<form action="upload_download.php" method="POST">
<input type="submit" name="download" value="Telecharger">
<?php
// Verifie que le formulaire a bien ete envoye
if($_SERVER["REQUEST_METHOD"] == "POST"):
//Pour forcer le telechargement du fichier
    $uploadfile = "/upload/chiffrement.txt";
    if(isset($_POST['download']) === true):
        //envoie requete http
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        //affiche une fenetre de telechargement
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment; filename=".basename($uploadfile).";");
        header("Content-Transfer-Encoding: binary");
        //affiche la taille du fichier
        header("Content-Length: ".filesize($uploadfile));
        readfile("$uploadfile");
    endif;
endif;
?>
</form>
<!-- Upload d'un fichier de 5mo max -->
<form action="upload_download.php" method="post" enctype="multipart/form-data">
<?php
// Vérifier si le formulaire a été soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Verifie si le fichier a été uploadé sans erreur.
    if(isset($_FILES["user_file"]) && $_FILES["user_file"]["error"] == 0){
        $filename = $_FILES["user_file"]["name"];
        $filetype = $_FILES["user_file"]["type"];
        $filesize = $_FILES["user_file"]["size"];

        // Verifie la taille du fichier - 5Mo maximum
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize) die("Error: La taille du fichier est supérieure à la limite autorisée.");
            // Vérifie si le fichier existe avant de le télécharger.
            if(file_exists("upload/" . $filename)){
                echo $filename . " existe déjà.";
                echo "<br><br><a href='upload_download.php'>Retour a la page principale</a>";
            } else{
                // Deplace le fichier dans upload
                move_uploaded_file($_FILES["user_file"]["tmp_name"], "upload/" . $filename);
                echo "Votre fichier a été téléchargé avec succès.";
                header('Refresh: 3; URL=upload_download.php');
            } 
    } else{
        // Affiche une erreur 
        echo "Error: " . $_FILES["user_file"]["error"];
        // La redirection c'est trop bien
        echo "<br><br><a href='upload_download.php'>Retour a la page principale</a>";
    }
}
?>
    <h2>Upload Fichier</h2>
        <label for="fileUpload">Fichier:</label>
        <span>Liste des fichiers</span>
        <!--Ouverture de la liste-->
        <ul>
        <?php
        //Recupere tout les elements du dossier upload
        $files = array_slice(scandir('upload/'), 2);
        foreach ($files as $key => $list):   
            Echo "<ul><li>" . $list . "</li></ul>";
        endforeach;
        ?>
    </ul>
        <input type="file" name="user_file" id="fileUpload">
        <input type="submit" name="submit" value="Upload">
        <p><strong>Note:</strong> La taille des fichiers ne doit pas excéder 5 Mo.</p>
    </form>
</body>
</html>
