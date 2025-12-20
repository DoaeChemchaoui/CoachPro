<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['id_coach'])){
    header("Location: login_sportif.php");
    exit;
}

$id_coach = $_SESSION['id_coach'];

$stmt = $conn->prepare("SELECT * FROM coach WHERE id_coach = ?");
$stmt->bind_param("i", $id_coach);
$stmt->execute();
$coach = $stmt->get_result()->fetch_assoc();

$nb_en_attente = $conn->query("SELECT COUNT(*) as cnt FROM reservation WHERE id_coach=$id_coach AND statut='en attente'")->fetch_assoc()['cnt'];
$nb_aujourdhui = $conn->query("SELECT COUNT(*) as cnt FROM reservation WHERE id_coach=$id_coach AND statut='validée' AND DATE(date_reservation)=CURDATE()")->fetch_assoc()['cnt'];
$nb_demain = $conn->query("SELECT COUNT(*) as cnt FROM reservation WHERE id_coach=$id_coach AND statut='validée' AND DATE(date_reservation)=CURDATE()+INTERVAL 1 DAY")->fetch_assoc()['cnt'];
$next_res = $conn->query("SELECT r.*, s.nom as nom_sportif, s.prenom as prenom_sportif FROM reservation r JOIN sportif s ON r.id_sportif=s.id_sportif WHERE r.id_coach=$id_coach AND r.statut='validée' AND r.date_reservation>NOW() ORDER BY r.date_reservation ASC LIMIT 1")->fetch_assoc();

$msg = "";
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])){
    $photo = $_POST['photo'];
    $biographie = $_POST['biographie'];
    $disciplines = $_POST['disciplines'];
    $certifications = $_POST['certifications'];

    $update = $conn->prepare("UPDATE coach SET photo=?, biographie=?, disciplines=?, certifications=? WHERE id_coach=?");
    $update->bind_param("ssssi", $photo, $biographie, $disciplines, $certifications, $id_coach);
    if($update->execute()){
        $msg = "Profil mis à jour avec succès!";
        $coach['photo']=$photo;
        $coach['biographie']=$biographie;
        $coach['disciplines']=$disciplines;
        $coach['certifications']=$certifications;
    } else {
        $msg = "Erreur lors de la mise à jour.";
    }
}
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_action'])){
    $id_res = intval($_POST['id_reservation']);
    $action = $_POST['action'];
    $stmt_res = $conn->prepare("UPDATE reservation SET statut=? WHERE id_reservation=? AND id_coach=?");
    $stmt_res->bind_param("sii", $action, $id_res, $id_coach);
    $stmt_res->execute();
    header("Location: dashboard_coach.php");
    exit;
}

$reservations = $conn->query("SELECT r.*, s.nom as nom_sportif, s.prenom as prenom_sportif FROM reservation r JOIN sportif s ON r.id_sportif=s.id_sportif WHERE r.id_coach=$id_coach AND r.statut='en attente' ORDER BY r.date_reservation ASC");

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_dispo'])){
    $date = $_POST['date'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];
    $stmt_dispo = $conn->prepare("INSERT INTO disponibilite (id_coach, date, heure_debut, heure_fin) VALUES (?, ?, ?, ?)");
    $stmt_dispo->bind_param("isss", $id_coach, $date, $heure_debut, $heure_fin);
    $stmt_dispo->execute();
    header("Location: dashboard_coach.php");
    exit;
}

if(isset($_GET['delete_dispo'])){
    $id_dispo = intval($_GET['delete_dispo']);
    $conn->query("DELETE FROM disponibilite WHERE id_disponibilite=$id_dispo AND id_coach=$id_coach");
    header("Location: dashboard_coach.php");
    exit;
}

$dispos = $conn->query("SELECT * FROM disponibilite WHERE id_coach=$id_coach ORDER BY date ASC");
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
<li><a href="dashboard_coach.php"class="active">Dashboard Coach</a></li>
</ul>
<div class="auth-buttons">
<a href="logout.php" class="btn btn-secondary">Déconnexion</a>
</div>
</nav>
</header>

<div class="dashboard container">

<h1 class="page-title">Bienvenue, <?php echo htmlspecialchars($coach['nom']); ?></h1>

<div class="stats-grid">
<div class="stat-card">
<div class="stat-info">
<h3><?php echo $nb_en_attente; ?></h3>
<p>Demandes en attente</p>
</div></div>
<div class="stat-card">
<div class="stat-info">
<h3><?php echo $nb_aujourdhui; ?></h3>
<p>Séances validées aujourd'hui</p>
</div></div>
<div class="stat-card">
<div class="stat-info">
<h3><?php echo $nb_demain; ?></h3>
<p>Séances validées demain</p>
</div></div>
<div class="stat-card next-session">
<div class="stat-info">
<?php if($next_res): ?>
<h3>Prochaine séance</h3>
<p><?php echo htmlspecialchars($next_res['nom_sportif'].' '.$next_res['prenom_sportif']); ?></p>
<p><?php echo date("d/m/Y H:i", strtotime($next_res['date_reservation'])); ?></p>
<?php else: ?>
<p class="no-data">Aucune prochaine séance</p>
<?php endif; ?>
</div></div>
</div>

<div class="profile-section">
<h2>Profil Coach</h2>
<?php if($msg) echo "<p style='color:var(--success)'>$msg</p>"; ?>
<form method="POST">
<input type="hidden" name="update_profile">
<div class="form-group">
<label>Photo (chemin)</label>
<input type="text" name="photo" value="<?php echo htmlspecialchars($coach['photo']); ?>">
<div class="photo-preview">
<img src="<?php echo htmlspecialchars($coach['photo']); ?>" alt="Photo Coach">
</div>
</div>
<div class="form-group">
<label>Biographie</label>
<textarea name="biographie"><?php echo ($coach['biographie']); ?></textarea>
</div>
<div class="form-group">
<label>Disciplines</label>
<input type="text" name="disciplines" value="<?php echo htmlspecialchars($coach['disciplines']); ?>">
</div>
<div class="form-group">
<label>Certifications</label>
<input type="text" name="certifications" value="<?php echo htmlspecialchars($coach['certifications']); ?>">
</div>
<button type="submit" class="btn btn-primary btn-full">Modifier le profil</button>
</form>
</div>

<div class="bookings-container">
<h2 class="section-title">Réservations en attente</h2>
<?php if($reservations->num_rows > 0): ?>
<?php while($r = $reservations->fetch_assoc()): ?>
<div class="booking-card">
<div class="booking-info">
<h3><?php echo htmlspecialchars($r['nom_sportif'].' '.$r['prenom_sportif']); ?></h3>
<p>Le <?php echo date("d/m/Y H:i", strtotime($r['date_reservation'])); ?></p>
</div>
<div class="booking-actions">
<form method="POST">
<input type="hidden" name="id_reservation" value="<?php echo $r['id_reservation']; ?>">
<input type="hidden" name="reservation_action" value="1">
<button type="submit" name="action" value="validée" class="btn btn-success">Accepter</button>
<button type="submit" name="action" value="refusée" class="btn btn-danger">Refuser</button>
</form>
</div>
</div>
<?php endwhile; ?>
<?php else: ?>
<p class="no-data">Aucune réservation en attente.</p>
<?php endif; ?>
</div>

<div class="availability-manager">
<h2 class="section-title">Gérer mes disponibilités</h2>
<div class="time-slots-manager">
<form method="POST">
<input type="date" name="date" required>
<input type="time" name="heure_debut" required>
<input type="time" name="heure_fin" required>
<button type="submit" name="add_dispo" class="btn btn-primary btn-full">Ajouter</button>
</form>

<?php while($d=$dispos->fetch_assoc()): ?>
<div class="availability-slot">
<?php echo $d['date'].' '.$d['heure_debut'].'-'.$d['heure_fin']; ?>
<a href="?delete_dispo=<?php echo $d['id_disponibilite']; ?>" class="btn btn-danger">X</a>
</div>
<?php endwhile; ?>
</div>
</div>

</div>
<?php include '../includes/footer.php'; ?>
</body>
</html>
