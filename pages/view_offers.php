<?php
session_start();
include("../config/db.php");
include("../templates/header.php");

$query = "SELECT * FROM offers";
$result = $conn->query($query);
?>

<h2>Oferty</h2>
<div class="offers-container">
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
</div>

<?php
include("../templates/footer.php");
?>
