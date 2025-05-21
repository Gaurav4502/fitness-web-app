<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diet Plans</title>
    <link rel="stylesheet" href="../CSS/diet_page.css">
</head>
<body>
    <div class="container">
        <h1>Choose Your Diet Plan</h1>
        <?php
            $goal_type = isset($_POST['selected_goal']) ? $_POST['selected_goal'] : 'weight_loss';

            $diet_plans = [];
            if ($goal_type === 'weight_loss') {
                $diet_plans = [
                    "Weight Loss (Vegetarian)" => "../HTML/weight_loss_vegetarian.php",
                    "Weight Loss (Non-Vegetarian)" => "../HTML/weight_loss_nonVegetarian.php"
                ];
            } elseif ($goal_type === 'weight_gain') {
                $diet_plans = [
                    "Weight Gain (Vegetarian)" => "../HTML/weight_gain_vegetarian.php",
                    "Weight Gain (Non-Vegetarian)" => "../HTML/weight_gain_nonvegetarian.php"
                ];
            }
        ?>
        <ul>
            <?php foreach ($diet_plans as $name => $link): ?>
                <li><a href="<?= $link ?>" class="button-link"><?= $name ?></a></li>
            <?php endforeach; ?>
            <li><a href="../HTML/food_nutrition.html" class="button-link">Food Nutrition Chart</a></li>
        </ul>
        <!-- Removed "Back to Dashboard" link -->
    </div>
</body>
</html>
