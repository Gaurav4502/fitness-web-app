<?php
    // Start the session if needed
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bicep Workout</title>
    <link rel="stylesheet" href="../CSS/bicepworkout.css">
</head>
<body>
    <header>
        <h1>Bicep Workout Guide</h1>
    </header>
    <main>
        <section class="workout">
            <?php
                // Define an array of exercises
                $exercises = [
                    ["name" => "Barbell Curls", "desc" => "Builds overall bicep mass and strength.", "image" => "../IMAGES/Bicep/barbell curl.gif"],
                    ["name" => "Dumbbell Hammer Curls", "desc" => "Targets the brachialis and forearms.", "image" => "../IMAGES/Bicep/hammer curl.gif"],
                    ["name" => "Concentration Curls", "desc" => "Isolates the biceps for peak contraction.", "image" => "../IMAGES/Bicep/concentration curl.gif"],
                    ["name" => "Preacher Curls", "desc" => "Focuses on the lower bicep for better definition.", "image" => "../IMAGES/Bicep/preacher curl.gif"],
                    ["name" => "Incline Dumbbell Curls", "desc" => "Stretches and targets the long head of the biceps.", "image" => "../IMAGES/Bicep/incline dumbbell curl.gif"],
                    ["name" => "Cable Curls", "desc" => "Provides constant tension on the biceps.", "image" => "../IMAGES/Bicep/cable curl.gif"]
                ];

                // Loop through exercises and display them
                $count = 1;
                foreach ($exercises as $exercise) {
                    echo "<div class='exercise'>";
                    echo "<h2>{$count}. {$exercise['name']}</h2>";
                    echo "<p>{$exercise['desc']}</p>";
                    echo "<img src='{$exercise['image']}' alt='{$exercise['name']}'>";
                    echo "</div>";
                    $count++;
                }
            ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 Bicep Workout Guide</p>
    </footer>
</body>
</html>
