<?php
session_start();
include("../config/db.php");
include("../templates/header.php");

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$offers = [];
$total_price = 0.0;

if (!empty($cart)) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $conn->prepare("SELECT * FROM offers WHERE id IN ($placeholders)");
    $stmt->bind_param(str_repeat('s', count($cart)), ...array_keys($cart));
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $cart[$row['id']];
        $row['total'] = $row['price'] * $row['quantity'];
        $offers[] = $row;
        $total_price += $row['total'];
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    if (isset($cart[$product_id])) {
        unset($cart[$product_id]);
        $_SESSION['cart'] = $cart;
        header("Location: cart.php");
        exit();
    }
}
?>

<h2>Twój koszyk</h2>
<div class="cart-container">
    <?php if (!empty($offers)): ?>
        <?php foreach ($offers as $offer): ?>
            <div class="cart-item">
                <?php if ($offer['image']): ?>
                    <img src="../uploads/<?php echo $offer['image']; ?>" alt="<?php echo $offer['title']; ?>" class="cart-image">
                <?php endif; ?>
                <div class="cart-details">
                    <h3><?php echo $offer['title']; ?></h3>
                    <p><?php echo $offer['description']; ?></p>
                    <p>Ilość: <?php echo $offer['quantity']; ?></p>
                    <p>Łączna cena: <?php echo $offer['total']; ?> PLN</p>
                    <form method="post" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $offer['id']; ?>">
                        <input type="submit" value="Usuń z koszyka">
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="cart-summary">
            <p>Łączna cena: <span class="total-price"><?php echo $total_price; ?> PLN</span></p>
            <form action="checkout.php" method="post">
                <input type="submit" value="Kup">
            </form>
        </div>
    <?php else: ?>
        <p>Twój koszyk jest pusty.</p>
    <?php endif; ?>
</div>

<?php
include("../templates/footer.php");
?>
