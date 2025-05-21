<?php
    // Start the session if needed
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chest Workout</title>
    <link rel="stylesheet" href="../CSS/chestworkout.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <header>
                <h1>Chest Workout Guide</h1>
            </header>
            <main>
                <section class="workout form-wrapper">
                    <?php
                        // Define an array of exercises
                        $exercises = [
                            ["name" => "Flat Barbell Bench Press", "desc" => "Builds overall chest strength and mass.", "image" => "../IMAGES/Chest/Flat barbell bench press.gif"],
                            ["name" => "Incline Barbell Bench Press", "desc" => "Focuses on the upper chest muscles.", "image" => "../IMAGES/Chest/incline barbell bench press.gif"],
                            ["name" => "Push-Ups", "desc" => "A bodyweight exercise for chest and triceps.", "image" => "../IMAGES/Chest/pushups.gif"],
                            ["name" => "Incline Dumbbell Bench Press", "desc" => "A great alternative to the barbell bench press.", "image" => "../IMAGES/Chest/Incline Dumbell press.gif"],
                            ["name" => "Decline Dumbbell Press", "desc" => "Targets the lower chest muscles effectively.", "image" => "../IMAGES/Chest/Decline Dumbell press.gif"],
                            ["name" => "Machine Press", "desc" => "Stretches and isolates the chest muscles.", "image" => "../IMAGES/Chest/Machine press.gif"],
                            ["name" => "Pec Dec Flyes", "desc" => "Stretches and isolates the chest muscles.", "image" => "../IMAGES/Chest/Pec Dec fly.gif"],
                            ["name" => "Cable Crossovers", "desc" => "Helps define and shape the inner chest.", "image" => "../IMAGES/Chest/Cable crossover.gif"]
                        ];

                        // Loop through exercises and display them
                        $count = 1;
                        foreach ($exercises as $exercise) {
                            echo "<div class='exercise form-column'>";
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
                <p>&copy; 2025 Chest Workout Guide</p>
            </footer>
        </div>
    </div>
</body>
</html>
