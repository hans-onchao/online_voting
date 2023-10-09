<?php
// Connect to the database (replace 'your_username', 'your_password', and 'your_db_name' with your actual database credentials)
$mysqli = new mysqli("localhost", "root", "", "login");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_POST['candidate'])) {
    $candidateName = $_POST['candidate'];

    // Check if the candidate exists in the database
    $query = "SELECT votes FROM candidates WHERE name = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $candidateName);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // If the candidate does not exist, insert a new record for the candidate
        $insertQuery = "INSERT INTO candidates (name, votes) VALUES (?, 1)";
        $insertStmt = $mysqli->prepare($insertQuery);
        $insertStmt->bind_param("s", $candidateName);
        $insertStmt->execute();
        $updatedVotes = 1;
    } else {
        // If the candidate already exists, update the vote count
        $stmt->bind_result($votes);
        $stmt->fetch();
        $stmt->close();

        $updatedVotes = $votes + 1;

        $updateQuery = "UPDATE candidates SET votes = ? WHERE name = ?";
        $updateStmt = $mysqli->prepare($updateQuery);
        $updateStmt->bind_param("is", $updatedVotes, $candidateName);
        $updateStmt->execute();
    }

    // Close the database connection
    $mysqli->close();

    // Return the updated vote count
    echo $updatedVotes;
}
?>