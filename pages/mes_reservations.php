<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['id_sportif'])) {
    header("Location: login.php");
    exit;
}

$id_sportif = $_SESSION['id_sportif'];

$stmt = $conn->prepare("SELECT * FROM sportif WHERE id_sportif=?");
$stmt->bind_param("i", $id_sportif);
$stmt->execute();
$sportif = $stmt->get_result()->fetch_assoc();

$msg = "";
if (isset($_POST['update_profile'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];

    $up = $conn->prepare("UPDATE sportif SET nom=?, prenom=?, email=? WHERE id_sportif=?");
    $up->bind_param("sssi", $nom, $prenom, $email, $id_sportif);
    $up->execute();
    $msg = "Profil mis à jour avec succès";

    $sportif['nom'] = $nom;
    $sportif['prenom'] = $prenom;
    $sportif['email'] = $email;
}

if (isset($_POST['annuler'])) {
    $id_res = $_POST['id_reservation'];
    $conn->query("UPDATE reservation SET statut='annulée' 
                  WHERE id_reservation=$id_res AND id_sportif=$id_sportif");
}

$coachs = $conn->query("SELECT * FROM coach");
$reservations = $conn->query("
    SELECT r.*, c.nom AS coach_nom, c.photo AS coach_photo
    FROM reservation r
    JOIN coach c ON r.id_coach = c.id_coach
    WHERE r.id_sportif = $id_sportif
    ORDER BY r.date_reservation DESC
");
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

<h1 class="page-title">Bienvenue <?php echo $sportif['prenom']; ?></h1>
<div class="profile-section">
<h2>Mes informations</h2>
<?php if($msg) echo "<p style='color:green'>$msg</p>"; ?>
<form method="POST">
    <input type="hidden" name="update_profile">
    <div class="form-group">
        <label>Nom</label>
        <input type="text" name="nom" value="<?php echo $sportif['nom']; ?>">
    </div>
    <div class="form-group">
        <label>Prénom</label>
        <input type="text" name="prenom" value="<?php echo $sportif['prenom']; ?>">
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?php echo $sportif['email']; ?>">
    </div>
    <button class="btn btn-primary">Modifier</button>
</form>
</div>
<h2 class="section-title">Choisir un coach</h2>
<div class="coaches-grid">
<?php while($c = $coachs->fetch_assoc()): ?>
<div class="coach-card">
    <div class="coach-image">
        <img src="../assets/images/<?php echo htmlspecialchars($c['photo'] ?: 'default.png'); ?>" alt="Photo de <?php echo htmlspecialchars($c['nom']); ?>">
    </div>
    <div class="coach-info">
        <h3 class="coach-name"><?php echo $c['nom']; ?></h3>
        <p class="coach-specialty"><?php echo $c['disciplines']; ?></p>
        <a href="reservation.php?id=<?php echo $c['id_coach']; ?>" class="btn btn-primary btn-full">
            Réserver
        </a>
    </div>
</div>
<?php endwhile; ?>
</div>
<h2 class="section-title">Mes réservations</h2>
<div class="bookings-container">
<?php if($reservations->num_rows > 0): ?>
<?php while($r = $reservations->fetch_assoc()): ?>
<div class="booking-card">
    <div class="booking-info">
        <h3>Coach : <?php echo $r['coach_nom']; ?></h3>
        <p><?php echo date("d/m/Y H:i", strtotime($r['date_reservation'])); ?></p>
        <span class="status-badge status-<?php echo $r['statut']; ?>">
            <?php echo $r['statut']; ?>
        </span>
    </div>
    <?php if($r['statut'] == 'en attente'): ?>
    <form method="POST">
        <input type="hidden" name="id_reservation" value="<?php echo $r['id_reservation']; ?>">
        <button name="annuler" class="btn btn-danger">Annuler</button>
    </form>
    <?php endif; ?>
</div>
<?php endwhile; ?>
<?php else: ?>
<p class="no-data">Aucune réservation</p>
<?php endif; ?>
</div>
</div>
<?php include '../includes/footer.php'; ?>

</body>
</html>
