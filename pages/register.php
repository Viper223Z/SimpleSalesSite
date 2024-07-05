<?php
session_start();
include("../config/db.php");

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_repeat = $_POST['password_repeat'];
    $nick = $_POST['nick'];

    if (!empty($user_name) && !empty($email) && !empty($password) && !empty($password_repeat) && !empty($nick)) {
        if ($password !== $password_repeat) {
            $error_message = "Hasła nie są zgodne.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Nieprawidłowy format adresu email.";
        } else {
            $user_id = uniqid();
            $query = "INSERT INTO users (id, user_name, email, password, nick) VALUES ('$user_id', '$user_name', '$email', '$password', '$nick')";
            if ($conn->query($query) === TRUE) {
                header("Location: login.php");
                die;
            } else {
                $error_message = "Błąd: " . $conn->error;
            }
        }
    } else {
        $error_message = "Wszystkie pola są wymagane!";
    }
}
include("../templates/header.php");
?>

<h2>Rejestracja</h2>
<form method="post" class="auth-form">
    <label for="user_name">Nazwa użytkownika:</label>
    <input type="text" name="user_name" id="user_name"><br><br>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email"><br><br>
    <label for="nick">Nick:</label>
    <input type="text" name="nick" id="nick"><br><br>
    <label for="password">Hasło:</label>
    <input type="password" name="password" id="password"><br><br>
    <label for="password_repeat">Powtórz hasło:</label>
    <input type="password" name="password_repeat" id="password_repeat"><br><br>
    <input type="submit" value="Zarejestruj się">
    <?php if (!empty($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>
</form>

<?php
include("../templates/footer.php");
?>
