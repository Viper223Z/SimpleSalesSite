<?php
session_start();
include("../config/db.php");
include("../templates/header.php");

$offer_id = $_GET['id'] ?? '';

if (empty($offer_id)) {
    echo "Nie podano ID oferty.";
    exit();
}

// Używam `JOIN` do połączenia tabeli ofert z tabelą użytkowników
$stmt = $conn->prepare("SELECT offers.*, users.user_name FROM offers JOIN users ON offers.user_id = users.id WHERE offers.id = ?");
$stmt->bind_param('s', $offer_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $offer = $row;
} else {
    echo "Oferta nie istnieje.";
    exit();
}
$stmt->close();
?>

<div class="offer-details-page">
    <?php if (!empty($offer['image'])): ?>
        <img src="../uploads/<?php echo htmlspecialchars($offer['image']); ?>" alt="<?php echo htmlspecialchars($offer['title']); ?>">
    <?php endif; ?>
    <div class="offer-description">
        <h2><?php echo htmlspecialchars($offer['title']); ?></h2>
        <p><?php echo htmlspecialchars($offer['description']); ?></p>
        <p class="price"><?php echo htmlspecialchars($offer['price']); ?> PLN</p>
        <p class="user-nick">Użytkownik: <?php echo htmlspecialchars($offer['user_name']); ?></p>
        <form action="add_to_cart.php" method="post" class="add-to-cart-form">
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($offer['id']); ?>">
            <label for="quantity">Ilość:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1" required>
            <input type="submit" value="Dodaj do koszyka">
        </form>
    </div>
</div>

<?php
include("../templates/footer.php");
?>
