<?php
include ("db.php");
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location : sign_in.php");
    exit;
}

$isUserId = $conn->execute_query("SELECT * FROM users where id=?", [$_SESSION["user_id"]]);

if ($isUserId->num_rows === 0) {
    header("Location: sign_in.php");
    die("Auth error!");
}

$email = $isUserId->fetch_assoc()["email"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 flex flex-col justify-center items-center h-screen gap-20">
    <h1 class="text-5xl text-white text-center">Welcome <?php echo $email ?></h1>
    <a class="text-2xl text-white bg-blue-500 py-2 px-5 rounded hover:outline hover:bg-transparent outline-blue-500"
        href="sign_out.php">Sign
        out</a>
</body>

</html>