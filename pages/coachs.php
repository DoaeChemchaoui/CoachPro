<?php
session_start();
include '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Coachs - Platforme Sport</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<header>
    <nav>
        <div class="logo"><i class="fas fa-dumbbell"></i> Platforme Sport</div>
        <ul class="nav-links">
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="coachs.php" class="active">Coachs</a></li>
            <?php if(isset($_SESSION['id_sportif'])): ?>
                <li><a href="mes_reservations.php">Mes Réservations</a></li>
            <?php elseif(isset($_SESSION['id_coach'])): ?>
                <li><a href="dashboard_coach.php">Dashboard Coach</a></li>
            <?php endif; ?>
        </ul>
        <div class="auth-buttons">
            <?php if(isset($_SESSION['id_sportif']) || isset($_SESSION['id_coach'])): ?>
                <a href="logout.php" class="btn btn-secondary">Déconnexion</a>
            <?php else: ?>
                <a href="login_sportif.php" class="btn btn-primary">Se connecter</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<section class="coaches-section">
    <h2 class="section-title">Tous les Coachs</h2>
    <div class="coaches-grid">
        <?php
        $sql = "SELECT * FROM coach";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            while($coach = $result->fetch_assoc()):
        ?>
        <div class="coach-card">
            <div class="coach-image">
                <img src="../assets/images/<?php echo htmlspecialchars($coach['photo'] ?: 'default.png'); ?>" alt="Photo de <?php echo htmlspecialchars($coach['nom']); ?>">
            </div>
            <div class="coach-info">
                <h3 class="coach-name"><?php echo htmlspecialchars($coach['nom'].' '.$coach['prenom']); ?></h3>
                <p class="coach-specialty">Discipline : <?php echo htmlspecialchars($coach['disciplines']); ?></p>
                <a href="reservation.php?id_coach=<?php echo intval($coach['id_coach']); ?>" class="btn btn-primary">Réserver</a>
            </div>
        </div>
        <?php endwhile; } else { ?>
            <p>Aucun coach trouvé.</p>
        <?php } ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
</body>
</html>
