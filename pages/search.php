<?php
session_start();
include("../config/db.php");
include("../templates/header.php");

$query = $_GET['query'];
$sql = "SELECT * FROM offers WHERE title LIKE '%$query%' OR description LIKE '%$query%'";
$result = $conn->query($sql);
?>

<h2>Wyniki wyszukiwania</h2>
<div class="offers-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="offer-card">
                <a href="offer_details.php?id=<?php echo $row['id']; ?>" class="offer-link">
                    <?php if ($row['image']): ?>
                        <img src="../uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>" class="offer-image">
                    <?php endif; ?>
                    <div class="offer-details">
                        <h3><?php echo $row['title']; ?></h3>
                        <p><?php echo $row['description']; ?></p>
                        <p class="price"><?php echo $row['price']; ?> PLN</p>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Brak wyników wyszukiwania dla zapytania "<?php echo htmlspecialchars($query); ?>"</p>
    <?php endif; ?>
</div>

<?php
include("../templates/footer.php");
?>
