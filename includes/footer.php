<head>    <link rel="stylesheet" href="assets/css/style.css"></head>
<footer>
    <div class="footer-content">
        <div class="footer-section">
            <h3>Platforme Sport</h3>
            <p>Connectez-vous avec les meilleurs coachs pour améliorer vos performances.</p>
        </div>
        <div class="footer-section">
            <h3>Liens rapides</h3>
            <a>Accueil</a>
            <a>Coachs</a>
            <?php if(isset($_SESSION['id_sportif'])): ?>
                <li><a href="pages/mes_reservations.php">Mes Réservations</a></li>
            <?php elseif(isset($_SESSION['id_coach'])): ?>
                <li><a href="pages/dashboard_coach.php">Dashboard Coach</a></li>
            <?php endif; ?>        </div>
        <div class="footer-section">
            <h3>Contact</h3>
            <p>Email: support@platformesport.com</p>
            <p>Téléphone: +212 600 000 000</p>
        </div>
    </div>
    <div class="social-icons">
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
    <div class="footer-bottom">
        &copy; 2025 Platforme Sport. Tous droits réservés.
    </div>
</footer>
<script src="../assets/js/script.js"></script>