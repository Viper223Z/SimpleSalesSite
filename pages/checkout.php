<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../config/db.php");
include("../templates/header.php");

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$offers = [];
$total_price = 0.0;

if (!empty($cart)) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $conn->prepare("SELECT * FROM offers WHERE id IN ($placeholders)");
    $types = str_repeat('s', count($cart)); // Typy parametrów (wszystkie jako 's' - string)
    $stmt->bind_param($types, ...array_keys($cart)); // Przekazywanie parametrów jako lista
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
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $postal_code = isset($_POST['postal_code']) ? $_POST['postal_code'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
    $delivery_method = isset($_POST['delivery_method']) ? $_POST['delivery_method'] : '';

    if ($user_id && !empty($first_name) && !empty($last_name) && !empty($phone) && !empty($postal_code) && !empty($address) && !empty($payment_method) && !empty($delivery_method) && !empty($cart)) {
        $query = "INSERT INTO orders (user_id, total_price, first_name, last_name, phone, postal_code, address, payment_method, delivery_method) VALUES ('$user_id', '$total_price', '$first_name', '$last_name', '$phone', '$postal_code', '$address', '$payment_method', '$delivery_method')";
        if ($conn->query($query) === TRUE) {
            $order_id = $conn->insert_id;
            foreach ($cart as $offer_id => $quantity) {
                $query = "INSERT INTO order_items (order_id, offer_id, quantity) VALUES ('$order_id', '$offer_id', '$quantity')";
                $conn->query($query);
            }
            unset($_SESSION['cart']);
            header("Location: order_confirmation.php");
            exit();
        } else {
            echo "Błąd: " . $conn->error;
        }
    } else {
        echo "Wszystkie pola są wymagane.";
    }
}
?>

<h2>Dane do wysyłki</h2>
<form method="post" class="checkout-form">
    <label for="first_name">Imię:</label>
    <input type="text" name="first_name" id="first_name" required><br><br>
    <label for="last_name">Nazwisko:</label>
    <input type="text" name="last_name" id="last_name" required><br><br>
    <label for="phone">Nr. telefonu:</label>
    <input type="text" name="phone" id="phone" required><br><br>
    <label for="postal_code">Kod pocztowy:</label>
    <input type="text" name="postal_code" id="postal_code" required><br><br>
    <label for="address">Adres:</label>
    <textarea name="address" id="address" required></textarea><br><br>
    <label for="payment_method">Metoda płatności:</label>
    <select name="payment_method" id="payment_method" required>
        <option value="credit_card">Karta kredytowa</option>
        <option value="paypal">PayPal</option>
        <option value="bank_transfer">Przelew bankowy</option>
    </select><br><br>
    <label for="delivery_method">Metoda dostawy:</label>
    <select name="delivery_method" id="delivery_method" required>
        <option value="courier">Kurier</option>
        <option value="post">Poczta</option>
    </select><br><br>
    <input type="submit" value="Złóż zamówienie">
</form>

<?php
include("../templates/footer.php");
?>
