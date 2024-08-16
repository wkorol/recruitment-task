<?php
//TODO

declare(strict_types=1);
?>

<!--SAMPLE-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
<h1>Login</h1>
<form action="/authenticate" method="post">
    <label for="login">Login:</label>
    <input type="text" id="login" name="login" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Login</button>
</form>
</body>
</html>