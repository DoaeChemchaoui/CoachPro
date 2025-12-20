<?php session_start(); ?>
<header>
    <nav>
        <div class="logo"><i class="fas fa-dumbbell"></i> Platforme Sport</div>
        <ul class="nav-links">
            <li><a href="../index.php" class="active">Accueil</a></li>
            <li><a href="pages/coachs.php">Coachs</a></li>
            <?php if(isset($_SESSION['id_sportif'])): ?>
                <li><a href="pages/mes_reservations.php">Mes Réservations</a></li>
            <?php elseif(isset($_SESSION['id_coach'])): ?>
                <li><a href="pages/dashboard_coach.php">Dashboard Coach</a></li>
            <?php endif; ?>
        </ul>
        <div class="auth-buttons">
            <?php if(isset($_SESSION['id_sportif']) || isset($_SESSION['id_coach'])): ?>
                <a href="pages/logout.php" class="btn btn-secondary">Déconnexion</a>
            <?php else: ?>
                <a href="pages/login_sportif.php" class="btn btn-primary">Se connecter</a>
                <a href="pages/register_sportif.php" class="btn btn-secondary">S'inscrire</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
