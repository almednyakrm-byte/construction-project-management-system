<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen bg-gray-500 flex justify-center items-center">
    <div class="bg-white p-10 rounded shadow-md w-80">
        <h2 class="text-orange-500 text-2xl font-bold mb-4">Register</h2>
        <form id="register-form">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <div class="text-red-500 text-xs" id="username-error"></div>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <div class="text-red-500 text-xs" id="email-error"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <div class="text-red-500 text-xs" id="password-error"></div>
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Register</button>
        </form>
        <div class="text-green-500 text-xs" id="success-message"></div>
    </div>

    <script>
        const registerForm = document.getElementById('register-form');
        registerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Validation
            let isValid = true;
            if (!username.match(/[A-Za-z\u0600-\u06FF0-9\s]+/)) {
                document.getElementById('username-error').innerText = 'Invalid username';
                isValid = false;
            } else {
                document.getElementById('username-error').innerText = '';
            }

            if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                document.getElementById('email-error').innerText = 'Invalid email';
                isValid = false;
            } else {
                document.getElementById('email-error').innerText = '';
            }

            if (password.length < 8) {
                document.getElementById('password-error').innerText = 'Password must be at least 8 characters';
                isValid = false;
            } else {
                document.getElementById('password-error').innerText = '';
            }

            if (isValid) {
                const formData = new FormData();
                formData.append('username', username);
                formData.append('email', email);
                formData.append('password', password);

                fetch('../backend/auth.php?action=register', {
                    method: 'POST',
                    body: formData
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        document.getElementById('success-message').innerText = 'Registration successful';
                        document.getElementById('register-form').reset();
                    } else {
                        document.getElementById('success-message').innerText = 'Registration failed';
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
            }
        });
    </script>
</body>
</html>