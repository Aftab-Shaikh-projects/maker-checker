<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* General Page Styling */
/* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Helvetica Neue', Arial, sans-serif;
    background-color: #e9eff1;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background-color: white;
    padding: 40px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    width: 100%;
    max-width: 400px;
}

h1 {
    font-size: 2em;
    color: #4b8bf5;
    text-align: center;
    margin-bottom: 30px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-size: 1em;
    color: #333;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1em;
    color: #333;
}

input[type="email"]:focus,
input[type="password"]:focus {
    outline: none;
    border-color: #4b8bf5;
    box-shadow: 0 0 5px rgba(75, 139, 245, 0.3);
}

button[type="submit"] {
    background-color: #4b8bf5;
    color: white;
    padding: 14px;
    border: none;
    border-radius: 5px;
    font-size: 1.2em;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%;
}

button[type="submit"]:hover {
    background-color: #3578e5;
}

.forgot-password {
    text-align: center;
    font-size: 0.9em;
    color: #4b8bf5;
    margin-top: 10px;
    cursor: pointer;
}

.forgot-password:hover {
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 480px) {
    .container {
        padding: 20px;
    }

    h1 {
        font-size: 1.6em;
    }

    input[type="email"],
    input[type="password"] {
        padding: 10px;
    }

    button[type="submit"] {
        font-size: 1em;
        padding: 12px;
    }
}


    </style>
</head>
<body>
<div class="container">
        <h1>Maker-Checker Product Management System</h1>

        <!-- User Management Section -->
        <section id="user-section">
            <h2>Adim checker</h2>
            <form id="userForm" action="user.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter email" required />

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required />

                <button type="submit" id="savedata">Submit</button>
                <div id="userStatus" class="status-message"></div>
            </form>
        </section>
</body>
</html>