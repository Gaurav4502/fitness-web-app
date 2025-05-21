<?php
session_start(); // Start the session

$exercises = [
    ["Close-Grip Bench Press", "Targets the triceps for strength and mass.", "../IMAGES/Tricep/close grip bench press.gif"],
    ["Tricep Dips", "Bodyweight exercise to build tricep size.", "../IMAGES/Tricep/dips.gif"],
    ["Overhead Dumbbell Extensions", "Stretches and strengthens the long head of the triceps.", "../IMAGES/Tricep/overhead dumbbell extension.gif"],
    ["Skull Crushers", "Targets the triceps for definition and strength.", "../IMAGES/Tricep/skull crusher.gif"],
    ["Rope Pushdowns", "Provides isolation and constant tension on the triceps.", "../IMAGES/Tricep/rope pushdown.gif"],
    ["Kickbacks", "Builds tricep definition and shape.", "../IMAGES/Tricep/tricep kickback.gif"]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tricep Workout</title>
    <link rel="stylesheet" href="../CSS/tricepworkout.css">
</head>
<body>
    <header>
        <h1>Tricep Workout Guide</h1>
    </header>
    <main>
        <section class="workout">
            <?php foreach ($exercises as $index => $exercise) : ?>
                <div class="exercise">
                    <h2><?php echo ($index + 1) . ". " . $exercise[0]; ?></h2>
                    <p><?php echo $exercise[1]; ?></p>
                    <img src="<?php echo $exercise[2]; ?>" alt="<?php echo $exercise[0]; ?>">
                </div>
            <?php endforeach; ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 Tricep Workout Guide</p>
    </footer>
</body>
</html>
