<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch vote counts for each candidate from the 'candidates' table
    $stmt = $conn->query("SELECT * FROM candidates");
    $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ... -->
</head>

<body>
    <h2>Voting Results</h2>
    <table>
        <tr>
            <th>Candidate</th>
            <th>Votes</th>
        </tr>
        <?php foreach ($candidates as $candidate) { ?>
        <tr>
            <td><?php echo $candidate['name']; ?></td>
            <td><?php echo $candidate['votes']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>

</html>