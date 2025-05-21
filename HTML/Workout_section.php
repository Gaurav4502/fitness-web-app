<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workout Section</title>
    <link rel="stylesheet" href="../CSS/Workout_section.css">
</head>
<body>

<div class="container">
    <h1>Select a Muscle Group</h1>
    
    <div class="workout-grid">
        <?php
            // Workout categories and their corresponding HTML files
            $workouts = [
                "Chest" => "../HTML/chest.php",
                "Back" => "../HTML/back.php",
                "Biceps" => "../HTML/bicep.php",
                "Triceps" => "../HTML/tricep.php",
                "Legs" => "../HTML/leg.php",
                "Shoulders" => "../HTML/shoulder.php"
            ];

            // Generate buttons dynamically
            foreach ($workouts as $name => $link) {
                echo "<a href='$link' class='muscle-group'>$name</a>";
            }
        ?>
    </div>
    <!-- Removed "Back to Dashboard" link -->
</div>

</body>
</html>
