<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Form</title>
</head>
<body>
    <?php 
    
    // Vérification formulaire
    
    $errors = [];
    $uploadDir = 'public/uploads/';
    if($_SERVER['REQUEST_METHOD'] === "POST"){ 
        $uploadFile = $uploadDir . basename($_FILES['profileImage']['name']);
        $extension = pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION);
        $authorizedExtensions = ['jpg','gif','png','webp'];
        $maxFileSize = 1000000;
            // Je sécurise et effectue mes tests
    
        if( (!in_array($extension, $authorizedExtensions))){
            $errors[] = 'Veuillez sélectionner une image de type Jpg,Gif,Webp ou Png !';
        }
        if( file_exists($_FILES['profileImage']['tmp_name']) && filesize($_FILES['profileImage']['tmp_name']) > $maxFileSize)
        {
        $errors[] = "Votre fichier doit faire moins de 1Mo !";
        }
        if (empty ($errors))
        {   
            $uniqueFileName = uniqid('image_') . '.' . $extension;
            $uploadFile = $uploadDir . $uniqueFileName; 
            if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $uploadFile)) {
                echo "Fichier téléchargé avec succès.";
            } else {
                $errors[] = "Erreur lors du téléchargement du fichier.";
            }
        }
    }
    
    if(isset($_POST['delete'])) {
        $fileName = $_POST['delete']; 
        if (!empty($fileName)) { 
            $filePath = $uploadDir . $fileName; 
    
            if (file_exists($filePath)) { // Vérifie si le fichier existe
                if (unlink($filePath)) { // Supprime le fichier
                    echo "Le fichier $fileName a été supprimé avec succès.";
                } else {
                    echo "Une erreur s'est produite lors de la suppression du fichier $fileName.";
                }
            } else {
                echo "Le fichier $fileName n'existe pas.";
            }
            }
    }
    ?>
     <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <label for="imageUpload">Upload d'une image de profil</label>  </br>
    <hr> 
    <form method="post" enctype="multipart/form-data">
    <input type="file" name="profileImage" id="imageUpload" />
    <button name="send">Envoyer</button>
                </br></br>
    <label for="deleteFile">Supprime un fichier uploadé:</label> </br>
    <hr>
    <form method="post">
    <select name="delete">
            <?php
                $files = array_diff(scandir($uploadDir), array('..', '.'));
                foreach ($files as $file) {
                    echo "<option value=\"$file\">$file</option>";
                }
            ?>
        </select>
        <button type="submit">Supprimer</button>
    </form>

</form>
</body>
</html>