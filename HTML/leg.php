<?php
    // Start the session if needed
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leg Workout</title>
    <link rel="stylesheet" href="../CSS/legworkout.css">
</head>
<body>
    <header>
        <h1>Leg Workout Guide</h1>
    </header>
    <main>
        <section class="workout">
            <?php
                // Define an array of exercises
                $exercises = [
                    ["name" => "Squats", "desc" => "The king of leg exercises for overall leg strength.", "image" => "../IMAGES/Leg/squats.gif"],
                    ["name" => "Leg Press", "desc" => "Targets quads, hamstrings, and glutes.", "image" => "../IMAGES/Leg/leg press.gif"],
                    ["name" => "Romanian Deadlifts", "desc" => "Strengthens hamstrings and lower back.", "image" => "../IMAGES/Leg/deadlift.gif"],
                    ["name" => "Walking Lunges", "desc" => "Improves balance, coordination, and leg strength.", "image" => "../IMAGES/Leg/lunges.gif"],
                    ["name" => "Calf Raises", "desc" => "Builds and strengthens the calf muscles.", "image" => "../IMAGES/Leg/calf raise.gif"],
                    ["name" => "Bulgarian Split Squats", "desc" => "Focuses on quads, glutes, and balance.", "image" => "../IMAGES/Leg/split squats.gif"]
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
        <p>&copy; 2025 Leg Workout Guide</p>
    </footer>
</body>
</html>
