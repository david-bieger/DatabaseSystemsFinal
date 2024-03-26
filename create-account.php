<?php
//haven't done much with this yet at all
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
</head>
<body>
    <h2>Create Account</h2>
    <form action="process_account.php" method="post">
        <label for="new_username">New Username:</label><br>
        <input type="text" id="new_username" name="new_username"><br>
        <label for="new_password">New Password:</label><br>
        <input type="password" id="new_password" name="new_password"><br><br>
        <input type="submit" value="Create Account">
    </form>
</body>
</html>
