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

    <div id="success-message" class="success-message" style="display: none;"></div>
    <div id="error-message" class="error-message" style="display: none;"></div>

    <form id="loginForm" class="login-form" method="POST">
        <input type="text" name="login" placeholder="Username" class="input-field" required>
        <input type="password" name="password" placeholder="Password" class="input-field" required>
        <button type="submit" class="login-button">Login</button>
    </form>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(event.target);

        fetch('/login', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.message === 'Login successful') {
                    window.location.href = '/dashboard.php';
                } else {
                    displayErrorMessage('Wrong Login Data!');
                }
            })
            .catch(error => {
                document.getElementById('message').textContent = 'An error occurred: ' + error.message;
            });
    });

    function displayErrorMessage(message) {
        const errorMessage = document.getElementById('error-message');
        errorMessage.textContent = message;
        errorMessage.style.display = 'block';

        const successMessage = document.getElementById('success-message');
        successMessage.style.display = 'none';
    }
</script>
</body>
</html>