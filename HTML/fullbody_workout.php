<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full Body Workouts</title>
    <link rel="stylesheet" href="../CSS/fullbody_workout.css">
</head>
<body>

<div class="container">
    <h2>Full Body Workouts</h2>

    <div class="workout-grid">
    <?php
    $workouts = [
        ["Jumping Jacks", "Great for warm-up and cardio.", "../IMAGES/Home_workout/Jumping-jack.gif"],
        ["Burpees", "Full body explosive workout.", "../IMAGES/Home_workout/burpees.gif"],
        ["Mountain Climbers", "Strengthens core and legs.", "../IMAGES/Home_workout/mountain climbers.gif"],
        ["Plank to Push-up", "Improves core and upper body strength.", "../IMAGES/Home_workout/plank pushup.gif"],
        ["Bear Crawl", "Improves mobility and strength.", "../IMAGES/Home_workout/bear cral.gif"],
        ["Jump Squats", "Strengthens legs and glutes.", "../IMAGES/Home_workout/jump squats.gif"]
    ];

    foreach ($workouts as $workout) {
        echo "<div class='workout'>
                <img src='{$workout[2]}' alt='{$workout[0]}' onerror=\"this.onerror=null; this.src='../images/placeholder.png'\">
                <div class='workout-info'>
                    <h3>{$workout[0]}</h3>
                    <p>{$workout[1]}</p>
                </div>
              </div>";
    }
    ?>
    </div>
</div>


</div>

</body>
</html>
