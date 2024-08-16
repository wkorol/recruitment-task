<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="resources/styles.css">
</head>
<body>
<div class="login-container">
    <img src="resources/svg/logo.svg" alt="Logo" class="logo">
    <form id="loginForm" class="login-form" method="POST">
        <input type="text" name="login" placeholder="Username" class="input-field" required>
        <input type="password" name="password" placeholder="Password" class="input-field" required>
        <button type="submit" class="login-button">Login</button>
    </form>
    <div id="message"></div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(event.target);

        fetch('/login', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (response.status === 200) {
                    window.location.href = '/dashboard.php';
                }
            })
            .catch(error => {
                document.getElementById('message').textContent = 'An error occurred: ' + error.message;
            });
    });
</script>
</body>
</html>