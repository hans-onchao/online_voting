<?php
session_start(); // Start a session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_vote"])) {
        // Check if the user has already voted in this session
        if (isset($_SESSION['has_voted']) && $_SESSION['has_voted'] === true) {
            echo "You have already voted in this session.";
            // You can redirect the user or display an appropriate message here.
        } else {
            $candidate_id = $_POST["candidate_id"];
            $stmt = $conn->prepare("UPDATE candidates SET votes = votes + 1 WHERE id = :candidate_id");
            $stmt->bindParam(':candidate_id', $candidate_id);

            if ($stmt->execute()) {
                $_SESSION['has_voted'] = true; // Set the 'has_voted' session variable to true after successful vote processing
                header("Location: home.html"); // Redirect back to the voting page after voting
                exit;
            } else {
                echo "Failed to submit the vote. Please try again later.";
            }
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
