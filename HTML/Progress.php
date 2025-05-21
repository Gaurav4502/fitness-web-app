<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login_page.php");
    exit();
}

include 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Fetch workout progress for the chart
if (isset($_GET['chart_data'])) {
    $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS date, muscle_group, exercise_name, 
                   SUM(`set`) AS total_sets, SUM(reps) AS total_reps, AVG(weight) AS avg_weight
            FROM workout_progress 
            WHERE user_id = ?
            GROUP BY date, muscle_group, exercise_name
            ORDER BY created_at ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $chartData = [];
    while ($row = $result->fetch_assoc()) {
        $chartData[] = $row;
    }
    echo json_encode($chartData);
    exit(); // Stop further execution
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $muscle_group = $_POST['muscle_group'];
    $exercise = $_POST['exercise'];
    $set = $_POST['set'];
    $reps = $_POST['reps'];
    $weight = $_POST['weight'];

    if (!filter_var($set, FILTER_VALIDATE_INT, ["options" => ["min_range" => 3, "max_range" => 6]]) || 
        !filter_var($reps, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 39]]) ||
        !preg_match('/^\d{1,2}(\.\d{1,2})?$/', $weight) || $weight > 100) {
        echo "Invalid input: Set must be a single integer between 3 and 6, Reps must be a single integer between 1 and 39, and Weight must be a number up to two decimal places not exceeding 100.";
        exit();
    }

    $sql = "INSERT INTO workout_progress (user_id, muscle_group, exercise_name, `set`, reps, weight) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issiii", $user_id, $muscle_group, $exercise, $set, $reps, $weight);

    if ($stmt->execute()) {
        header("Location: progress.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

$sql = "SELECT id, muscle_group, exercise_name, `set`, reps, weight, DATE_FORMAT(date, '%d/%m/%Y') AS formatted_date 
        FROM workout_progress WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Notes</title>
    <link rel="stylesheet" href="../CSS/progress_tracking.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="container">
    <h2>Workout Progress Notes</h2>

    <form action="progress.php" method="POST">
        <label for="muscle_group">Muscle Group:</label>
        <select name="muscle_group" id="muscle_group" required>
            <option value="">Select Muscle Group</option>
            <option value="Chest">Chest</option>
            <option value="Back">Back</option>
            <option value="Legs">Legs</option>
            <option value="Biceps">Biceps</option>
            <option value="Triceps">Triceps</option>
            <option value="Shoulders">Shoulders</option>
        </select>

        <label for="exercise">Exercise Name:</label>
        <select name="exercise" id="exercise" required>
            <option value="">Select Exercise</option>
        </select>

        <label for="set">Set:</label>
        <input type="number" name="set" required>

        <label for="reps">Reps:</label>
        <input type="number" name="reps" required>

        <label for="weight">Weight (kg):</label>
        <input type="number" step="0.1" name="weight">

        <button type="submit">Save Progress</button>
    </form>

    <h3>Workout Progress Chart</h3>
    <canvas id="progressChart"></canvas>

    <h3>Your Workout History 
        <button id="toggle-history" style="background: none; border: none; cursor: pointer; font-size: 16px;">&#x25B2;</button>
    </h3>

    <div class="workout-history" id="workout-history">
        <?php 
            $current_exercise = "";
            $current_date = "";

            while ($row = $result->fetch_assoc()): 
                if ($row['formatted_date'] != $current_date): 
                    if ($current_date != "") echo "</div>"; 
                    $current_date = $row['formatted_date'];
                    echo "<h4>{$current_date}</h4><div class='workout-group'>";
                endif;

                if ($row['exercise_name'] != $current_exercise): 
                    $current_exercise = $row['exercise_name'];
                    echo "<h5>" . htmlspecialchars($row['muscle_group']) . " - " . htmlspecialchars($row['exercise_name']) . "</h5>";
                endif;
            ?>

                <div class="workout-card">
                    <p><strong>Set:</strong> <?php echo $row['set']; ?></p>
                    <p><strong>Reps:</strong> <?php echo $row['reps']; ?></p>
                    <p><strong>Weight:</strong> <?php echo $row['weight']; ?> kg</p>
                    <a href="delete_progress.php?id=<?php echo $row['id']; ?>" class="btn-delete">Delete</a>
                </div>

            <?php endwhile; ?>
        </div>
    </div>
    <!-- Removed "Back to Dashboard" link -->
</div>

<script>
$(document).ready(function () {
    $('#toggle-history').click(function () {
        const history = $('#workout-history');
        history.toggle();
        const icon = $(this).html() === '&#x25B2;' ? '&#x25BC;' : '&#x25B2;';
        $(this).html(icon);
    });

    $('#muscle_group').change(function () {
        var muscleGroup = $(this).val();
        $('#exercise').html('<option value="">Loading...</option>');

        $.getJSON('../HTML/exercise_data.php', function (data) {
            if (data[muscleGroup]) {
                var options = '<option value="">Select Exercise</option>';
                $.each(data[muscleGroup], function (index, value) {
                    options += '<option value="' + value + '">' + value + '</option>';
                });
                $('#exercise').html(options);
            } else {
                $('#exercise').html('<option value="">No Exercises Found</option>');
            }
        }).fail(function () {
            $('#exercise').html('<option value="">Error Loading Exercises</option>');
        });
    });

    fetchProgressData();
});

async function fetchProgressData() {
    try {
        const response = await fetch("progress.php?chart_data=true");
        const data = await response.json();

        const labels = data.map(entry => entry.date);
        const totalSets = data.map(entry => entry.total_sets);
        const totalReps = data.map(entry => entry.total_reps);
        const avgWeight = data.map(entry => entry.avg_weight);

        new Chart(document.getElementById("progressChart").getContext("2d"), {
            type: "bar",  // Changed from "line" to "bar"
            data: {
                labels: labels,
                datasets: [
                    {
                        label: "Total Sets",
                        data: totalSets,
                        backgroundColor: "blue"
                    },
                    {
                        label: "Total Reps",
                        data: totalReps,
                        backgroundColor: "green"
                    },
                    {
                        label: "Avg Weight (kg)",
                        data: avgWeight,
                        backgroundColor: "red"
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    } catch (error) {
        console.error("Error fetching chart data:", error);
    }
}

</script>

</body>
</html>
