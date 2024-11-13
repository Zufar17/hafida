<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <style>
        :root {
            --body-color: <?php echo htmlspecialchars($bodyColor); ?>;
            --text-body: <?php echo htmlspecialchars($textBodyColor); ?>;
            --header-color: <?php echo htmlspecialchars($headerColor); ?>;
            --text-header: <?php echo htmlspecialchars($textHeaderColor); ?>;
            --sidebar-color: <?php echo htmlspecialchars($sidebarColor); ?>;
            --sidebarText-color: <?php echo htmlspecialchars($sidebarTextColor); ?>;
        }

        body {
            background-color: var(--body-color);
            color: var(--text-body);
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
        }

        .form-toggle {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .form-toggle button {
            background-color: var(--header-color);
            color: var(--text-header);
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 49%;
            box-sizing: border-box;
        }

        .form-toggle button.active {
            background-color: var(--text-header);
            color: var(--header-color);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: var(--header-color);
            color: var(--text-header);
            border: none;
            border-radius: 4px;
            cursor: pointer;
            box-sizing: border-box;
        }

        button[type="submit"]:hover {
            background-color: darken(var(--header-color), 10%);
        }

        .forgot-password {
            margin-top: 10px;
            text-align: center;
        }

        .forgot-password a {
            color: var(--header-color);
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-toggle">
            <button id="loginButton" class="active" onclick="showLogin()">LOGIN</button>
            <button id="registerButton" onclick="showRegister()">REGISTER</button>
        </div>
        
        <form id="loginForm" action="/login" method="POST">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="text" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <div class="forgot-password">
            </div>
        </form>

        <form id="registerForm" action="/register" method="POST" style="display: none;">
            <input type="hidden" name="action" value="register">
            <div class="form-group">
                <label for="register-username">Username:</label>
                <input type="text" id="register-username" name="username" required>
            </div>
            <div class="form-group">
                <label for="register-email">Email:</label>
                <input type="email" id="register-email" name="email" required>
            </div>
            <div class="form-group">
                <label for="register-password">Password:</label>
                <input type="password" id="register-password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>

    <script>
        function showLogin() {
            document.getElementById('loginForm').style.display = 'block';
            document.getElementById('registerForm').style.display = 'none';
            document.getElementById('loginButton').classList.add('active');
            document.getElementById('registerButton').classList.remove('active');
        }

        function showRegister() {
            document.getElementById('loginForm').style.display = 'none';
            document.getElementById('registerForm').style.display = 'block';
            document.getElementById('loginButton').classList.remove('active');
            document.getElementById('registerButton').classList.add('active');
        }
    </script>
</body>
</html>
