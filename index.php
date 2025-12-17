<?php
include 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportConnect - Trouvez votre coach</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<section class="hero">
    <h1>Trouvez votre coach sportif idéal</h1>
    <p>Réservez des séances personnalisées avec des coachs professionnels certifiés</p>
    <a href="pages/coachs.php" class="btn btn-primary">Découvrir les Coachs</a>
</section>

<section class="coaches-section" id="coachs">
    <h2 class="section-title">Nos Coachs Professionnels</h2>
    <div class="coaches-grid">
        <?php
        $sql = "SELECT * FROM coach";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            while($coach = $result->fetch_assoc()):
        ?>
        <div class="coach-card">
            <div class="coach-image">
                <img src="assets/images/<?php echo $coach['photo'] ?: 'default.png'; ?>" alt="Photo Coach">
            </div>
            <div class="coach-info">
                <h3 class="coach-name"><?php echo $coach['nom'].' '.$coach['prenom']; ?></h3>
                <p class="coach-specialty">Discipline : <?php echo $coach['disciplines']; ?></p>
                <p>Expérience : <?php echo $coach['annees_experience']; ?> ans</p>
                <a href="pages/reservation.php?id_coach=<?php echo $coach['id_coach']; ?>" class="btn btn-primary">Réserver</a>
            </div>
        </div>
        <?php endwhile; } else { echo "<p>Aucun coach disponible.</p>"; } ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
</body>
</html>
