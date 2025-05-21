<?php
    $exercises = [
        ["Pull-Ups", "Builds overall back strength and width.", "pull up.gif"],
        ["Deadlifts", "Strengthens the lower back and core.", "deadlift.gif"],
        ["Bent-Over Rows", "Targets the lats and rhomboids.", "bent over row.gif"],
        ["T-Bar Rows", "Great for overall back thickness.", "T-bar row.gif"],
        ["Lat Pulldowns", "Focuses on the lats for width.", "Lat pulldown.gif"],
        ["Seated Cable Rows", "Targets the middle back and traps.", "Seated cable row.gif"],
        ["Shrugs", "Builds and strengthens the traps.", "shrug.gif"]
    ];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Workout</title>
    <link rel="stylesheet" href="../CSS/backWorkout.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <header>
                <h1>Back Workout Guide</h1>
            </header>
            <main>
                <section class="workout form-wrapper">
                    <?php foreach ($exercises as $index => $exercise) : ?>
                        <div class="exercise form-column">
                            <h2><?php echo ($index + 1) . ". " . $exercise[0]; ?></h2>
                            <p><?php echo $exercise[1]; ?></p>
                            <img src="../IMAGES/Back/<?php echo $exercise[2]; ?>" alt="<?php echo $exercise[0]; ?>">
                        </div>
                    <?php endforeach; ?>
                </section>
            </main>
            <footer>
                <p>&copy; 2025 Back Workout Guide</p>
            </footer>
        </div>
    </div>
</body>
</html>
