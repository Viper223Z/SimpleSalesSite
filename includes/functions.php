<?php
function check_login($conn) {
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
        $query = "SELECT * FROM users WHERE id = '$id' LIMIT 1";
        
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            return $user_data;
        }
    }
    header("Location: login.php");
    die;
}
?>
