<?php
require("connect.php");

if (!empty($_POST["data"]) && !empty($_POST["data"]["reviewer_id"])) {
    $data = $_POST["data"];
    // Udfører SQL opdatering
    sql("UPDATE reviewers SET 
        name_of_the_reviewer = :name_of_the_reviewer, 
        reviewer_age = :reviewer_age, 
        reviewer_email = :reviewer_email, 
        reviewer_bio = :reviewer_bio 
        WHERE reviewerId = :reviewerId", [
        ":name_of_the_reviewer" => $data["name_of_the_reviewer"],
        ":reviewer_age" => $data["reviewer_age"],
        ":reviewer_email" => $data["reviewer_email"],
        ":reviewer_bio" => $data["reviewer_bio"],
        ":reviewerId" => $data["reviewer_id"],
    ]);
    header("Location: my_reviews.php");
    exit;
}

// Henter bios fra databasen
$bios = sql("SELECT 
    reviewerId, reviewer_age, name_of_the_reviewer, reviewer_bio, reviewer_email
    FROM reviewers 
    WHERE name_of_the_reviewer IN ('sofie', 'Sofie', 's')");

// Henter første resultat, hvis tilgængeligt, ellers sæt $bio til null
$bio = !empty($bios) && is_array($bios) ? $bios[0] : null; // Tjekker om $bios er en array

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Dine Reviews</title>
</head>
<body>
<?php include "header.php"; ?>
<main>
    <section class="my-reviews">
        <img id="my-reviews-img" class="my-reviews-img" src="img/user.png" alt="">
        <div class="bio">
            <?php if ($bio): ?>
                <!-- Viser bio-oplysninger -->
                <p class="pnormal"><?php echo $bio->name_of_the_reviewer; ?></p>
                <p class="pnormal"><?php echo $bio->reviewer_age; ?></p>
                <p class="pnormal"><?php echo $bio->reviewer_email; ?></p>
                <p class="pregular"><?php echo $bio->reviewer_bio; ?></p>

                <!-- Redigeringsform -->
                <button id="edit" class="edit-bio-btn">Rediger</button>
                <form action="my_reviews.php" method="post">
                    <input type="hidden" class="edit-bio-input" name="data[reviewer_id]" value="<?php echo $bio->reviewerId; ?>">
                    <label>
                        <input type="text" class="edit-bio-input" name="data[name_of_the_reviewer]" value="<?php echo $bio->name_of_the_reviewer; ?>">
                    </label>
                    <label>
                        <input type="number" class="edit-bio-input" name="data[reviewer_age]" value="<?php echo $bio->reviewer_age; ?>">
                    </label>
                    <label>
                        <input type="text" class="edit-bio-input" name="data[reviewer_email]" value="<?php echo $bio->reviewer_email; ?>">
                    </label>
                    <label>
                        <input type="text" class="edit-bio-input" name="data[reviewer_bio]" value="<?php echo $bio->reviewer_bio; ?>">
                    </label>
                    <button type="submit" class="submit" name="submit">Gem</button>
                </form>
            <?php else: ?>
                <p>Ingen bio fundet.</p>
            <?php endif; ?>
        </div>
    </section>
    <!---
    $reviews = sql("SELECT * 
                FROM reviewers r 
            WHERE r.name_of_the_reviewer IN ('sofie', 'Sofie', 's')");

    foreach ($reviews as $review){
        $review->review_title;
    }
   -->
</main>
</body>
</html>

<script>
    // JavaScript for redigering af bio
    const editBtn = document.querySelector('.edit-bio-btn');
    const bioInputs = document.querySelectorAll('.edit-bio-input');

    editBtn.addEventListener('click', () => {
        bioInputs.forEach(input => {
            input.style.display = input.style.display === "none" ? "block" : "none";
        });
    });

    bioInputs.forEach(input => {
        input.style.display = "none";
    });
</script>
