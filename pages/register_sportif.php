<?php 
session_start();
include '../includes/db.php';

$msg = '';

if(isset($_POST['submit'])){
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO sportif (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nom, $prenom, $email, $pass);

    if($stmt->execute()){
        header("Location: login_sportif.php");
        exit;
    } else {
        $msg = "Erreur lors de l'inscription";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Inscription Sportif</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<section class="hero">
<h2>Inscription Sportif</h2>

<?php if($msg): ?>
    <p style="color:red;"><?php echo $msg; ?></p>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label>Nom</label>
        <input type="text" name="nom" required>
    </div>

    <div class="form-group">
        <label>Prénom</label>
        <input type="text" name="prenom" required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>

    <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="password" required>
    </div>

    <button type="submit" name="submit" class="btn btn-primary">
        S'inscrire
    </button>
</form>
</section>

</body>
</html>
