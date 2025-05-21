<?php
header('Content-Type: application/json');

$exercises = [
    "Chest" => ["Bench Press", "Incline Dumbbell Press", "Chest Fly", "Push-ups", "Cable Crossovers", "Dips", "Pec Deck", "Decline Bench Press", "Machine Chest Press"],
    "Back" => ["Deadlift", "Pull-ups", "Lat Pulldown", "Bent-over Rows", "Face Pulls", "Single-arm Rows", "Reverse Fly", "Seated Row", "T-bar Row"],
    "Legs" => ["Squats", "Leg Press", "Lunges", "Leg Extensions", "Hamstring Curls", "Calf Raises", "Bulgarian Split Squats", "Sumo Deadlift", "Step-ups"],
    "Biceps" => ["Bicep Curls", "Hammer Curls", "Preacher Curls", "EZ Bar Curls", "Zottman Curls"],
    "Triceps" => ["Triceps Dips", "Skull Crushers", "Triceps Rope Pushdown", "Close-grip Bench Press"],    
    "Shoulders" => ["Overhead Press", "Lateral Raises", "Front Raises", "Face Pulls", "Arnold Press", "Reverse Fly", "Shrugs", "Cable Lateral Raises", "Dumbbell Shoulder Press"],
];

echo json_encode($exercises);
?>
