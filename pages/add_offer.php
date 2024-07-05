<?php
session_start();
include("../config/db.php");
include("../includes/functions.php");

$user_data = check_login($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    $error_message = '';

    if (!empty($title) && !empty($description) && !empty($price) && !empty($image)) {
        if (!is_numeric($price)) {
            $error_message = "Cena musi być liczbą.";
        } else {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Sprawdź, czy plik jest obrazem
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                $error_message = "Plik nie jest obrazem.";
            }

            // Sprawdź, czy plik już istnieje
            if (file_exists($target_file)) {
                $error_message = "Plik o tej nazwie już istnieje.";
            }

            // Sprawdź rozmiar pliku
            if ($_FILES["image"]["size"] > 5000000) {
                $error_message = "Plik jest za duży.";
            }

            // Dopuszczalne formaty plików
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $error_message = "Dozwolone są tylko pliki JPG, JPEG, PNG i GIF.";
            }

            // Sprawdź, czy jest błąd
            if (empty($error_message)) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $offer_id = uniqid();
                    $user_id = $user_data['id'];
                    $query = "INSERT INTO offers (id, user_id, title, description, price, image) VALUES ('$offer_id', '$user_id', '$title', '$description', '$price', '$image')";
                    if ($conn->query($query) === TRUE) {
                        header("Location: view_offers.php");
                        die;
                    } else {
                        $error_message = "Błąd: " . $conn->error;
                    }
                } else {
                    $error_message = "Wystąpił błąd podczas przesyłania pliku.";
                }
            }
        }
    } else {
        $error_message = "Wszystkie pola są wymagane!";
    }
}
include("../templates/header.php");
?>

<h2>Dodaj ofertę</h2>
<form method="post" enctype="multipart/form-data" class="auth-form">
    <label for="title">Tytuł:</label>
    <input type="text" name="title" id="title"><br><br>
    <label for="description">Opis:</label>
    <textarea name="description" id="description"></textarea><br><br>
    <label for="price">Cena:</label>
    <input type="number" name="price" id="price" step="0.01" min="0"><br><br>
    <label for="image">Zdjęcie:</label>
    <input type="file" name="image" id="image"><br><br>
    <input type="submit" value="Dodaj ofertę">
    <?php if (!empty($error_message)): ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>
</form>

<?php
include("../templates/footer.php");
?>
