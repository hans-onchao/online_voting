<?php
session_start(); // Start a session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

// Define the test_input function before using it
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $name = $password = $nameErr = $passwordErr = $Error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["name"]) && !empty(test_input($_POST["name"]))) {
            $name = test_input($_POST["name"]);
        } else {
            $nameErr = "Enter Username";
        }
        if (isset($_POST["password"]) && !empty(test_input($_POST["password"]))) {
            $password = test_input($_POST["password"]);
        } else {
            $passwordErr = "Enter password";
        }
    }

    if (empty($nameErr) && empty($passwordErr)) {
        $stmt = $conn->prepare("SELECT id FROM `user` WHERE name=:name AND password=:password");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                $_SESSION["user_id"] = $name; // Store the user's identifier in the session
                header("Location: home.html");
                exit; // Add exit to stop the script after redirecting
            } else {
                $Error = "Username or password didn't match";
            }
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="login-box">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <div class="user-box">
                <input type="text" name="name" required="">
                <label>Username</label>
            </div>

            <div class="user-box">
                <input type="password" name="password" required="">
                <label>Password</label>
            </div>

            <button type="submit" class="floating-btn" onclick="breakglass()">Submit</button>

        </form>
        <?php
        if (!empty($Error)) {
            echo '<p style="color: red;">' . $Error . '</p>';
        }
        ?>
    </div>
    <script src="script.js"></script>

</body>

</html>
