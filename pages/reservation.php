<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['id_sportif'])) {
    header("Location: login.php");
    exit;
}

$id_sportif = $_SESSION['id_sportif'];
$id_coach = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM coach WHERE id_coach=?");
$stmt->bind_param("i", $id_coach);
$stmt->execute();
$coach = $stmt->get_result()->fetch_assoc();

if (!$coach) {
    die("Coach introuvable");
}
$msg = "";
if (isset($_POST['reserver'])) {

    $date = $_POST['date'];
    $heure = $_POST['heure'];

    $stmt = $conn->prepare("
        INSERT INTO reservation (id_sportif, id_coach, date_reservation, statut)
        VALUES (?, ?, ?, 'en attente')
    ");

    $date_heure = $date . " " . $heure;

    $stmt->bind_param("iis", $id_sportif, $id_coach, $date_heure);
    $stmt->execute();

    $msg = "Réservation envoyée avec succès";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Coach</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<header>
<nav>
<div class="logo"><i class="fas fa-dumbbell"></i> Platforme Sport</div>
<ul class="nav-links">
<li><a href="../index.php">Accueil</a></li>
<li><a href="coachs.php" >Coachs</a></li>
<li><a href="dashboard_coach.php"class="active">Mes Réservations</a></li>
</ul>
<div class="auth-buttons">
<a href="logout.php" class="btn btn-secondary">Déconnexion</a>
</div>
</nav>
</header>

<div class="dashboard">

<h1 class="page-title">Réserver une séance</h1>

<div class="booking-card">

    <h2><?php echo $coach['nom']; ?></h2>
    <p><strong>Discipline :</strong> <?php echo $coach['disciplines']; ?></p>

    <?php if ($msg) echo "<p style='color:green'>$msg</p>"; ?>

    <form method="POST">
        <div class="form-group">
            <label>Date</label>
            <input type="date" name="date" required>
        </div>

        <div class="form-group">
            <label>Heure</label>
            <input type="time" name="heure" required>
        </div>

        <button class="btn btn-primary" name="reserver">
            Confirmer la réservation
        </button>
    </form>

</div>
</div>
<?php include '../includes/footer.php'; ?>

</body>
</html>
