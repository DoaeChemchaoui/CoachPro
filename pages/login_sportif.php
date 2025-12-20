<?php
session_start();
include '../includes/db.php';

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $pass  = $_POST['password'] ?? '';

    $sql = "SELECT * FROM coach WHERE email=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();
        if (password_verify($pass, $user['mot_de_passe'])) {
            $_SESSION['id_coach'] = $user['id_coach'];
            $_SESSION['user_name'] = $user['nom'];
            $_SESSION['role'] = 'coach';
            header("Location: ../index.php");
            exit;
        } else {
            $msg = "Mot de passe incorrect";
        }
    } else {
        $sql2 = "SELECT * FROM sportif WHERE email=? LIMIT 1";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("s", $email);
        $stmt2->execute();
        $res2 = $stmt2->get_result();

        if ($res2->num_rows === 1) {
            $user = $res2->fetch_assoc();
            if (password_verify($pass, $user['mot_de_passe'])) {
                $_SESSION['id_sportif'] = $user['id_sportif'];
                $_SESSION['user_name'] = $user['nom'];
                $_SESSION['role'] = 'sportif';
                header("Location: ../index.php");
                exit;
            } else {
                $msg = "Mot de passe incorrect";
            }
        } else {
            $msg = "Email non trouvé";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<section class="hero">
<h2>Connexion</h2>
<?php if($msg) echo "<p style='color:red;'>$msg</p>"; ?>
<form method="POST">
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="password" required>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Se connecter</button>
    <a href="register_sportif.php" class="btn btn-secondary">S'inscrire</a>
</form>
</section>
</body>
</html>
