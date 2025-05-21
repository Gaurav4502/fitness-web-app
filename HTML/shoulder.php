<?php
    // Start the session if needed
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoulder Workout</title>
    <link rel="stylesheet" href="../CSS/shoulderWorkout.css">
</head>
<body>
    <header>
        <h1>Shoulder Workout Guide</h1>
    </header>
    <main>
        <section class="workout">
            <?php
                // Define an array of exercises
                $exercises = [
                    ["name" => "Overhead Barbell Press", "desc" => "Builds strength and mass in the deltoid muscles.", "image" => "../IMAGES/Shoulder/Barbell-Standing-Military-Press.gif"],
                    ["name" => "Dumbbell Lateral Raises", "desc" => "Targets the side delts for width and definition.", "image" => "../IMAGES/Shoulder/lateral raise.gif"],
                    ["name" => "Front Dumbbell Raises", "desc" => "Focuses on the front deltoids for shoulder definition.", "image" => "../IMAGES/Shoulder/front raise.gif"],
                    ["name" => "Arnold Press", "desc" => "A complete shoulder exercise for strength and mobility.", "image" => "../IMAGES/Shoulder/arnold press.gif"],
                    ["name" => "Rear Delt Flyes", "desc" => "Targets the rear delts for balanced shoulder development.", "image" => "../IMAGES/Shoulder/bent over lateral raise.gif"],
                    ["name" => "Face Pulls", "desc" => "Improves shoulder health and rear delt activation.", "image" => "../IMAGES/Shoulder/face pull.gif"],
                    ["name" => "Upright Rows", "desc" => "Works the traps and delts for shoulder width.", "image" => "../IMAGES/Shoulder/upright row.gif"]
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
        <p>&copy; 2025 Shoulder Workout Guide</p>
    </footer>
</body>
</html>
