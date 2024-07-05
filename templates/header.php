<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/scripts.js" defer></script>
    <title>Sklep</title>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <h1>Sklep Internetowy</h1>
            </div>
            <div class="search-bar">
                <form action="search.php" method="get">
                    <input type="text" name="query" placeholder="Szukaj...">
                    <input type="submit" value="Szukaj">
                </form>
            </div>
            <nav>
                <a href="index.php">Home</a>
                <a href="view_offers.php">Oferty</a>
                <a href="cart.php">Koszyk</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="add_offer.php">Dodaj Ofertę</a>
                    <a href="logout.php">Wyloguj się</a>
                <?php else: ?>
                    <a href="login.php">Zaloguj się</a>
                    <a href="register.php">Zarejestruj się</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
