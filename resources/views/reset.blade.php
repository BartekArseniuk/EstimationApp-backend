<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resetowanie hasła</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #333333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            color: #666666;
        }

        input[type="email"],
        input[type="password"] {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #cccccc;
            border-radius: 10px;
            font-size: 14px;
        }

        input[type="submit"] {
            background-color: #424242;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 10px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #555555;
        }

        #successMessage {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        #closeButton {
            background-color: #555555;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        #closeButton:hover {
            background-color: #777777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Resetowanie hasła</h2>
        <form id="resetForm">
            <input type="hidden" name="token" value="{{ $token }}">

            <label for="email">Adres email</label>
            <input type="email" id="email" name="email">

            <label for="password">Nowe hasło</label>
            <input type="password" id="password" name="password">

            <label for="password-confirm">Potwierdź nowe hasło</label>
            <input type="password" id="password-confirm" name="password_confirmation">

            <input type="submit" value="Zresetuj hasło">
        </form>

        <div id="successMessage">
            <p>Twoje hasło zostało prawidłowo zmienione.</p>
            <button id="closeButton">Zamknij</button>
        </div>
    </div>

    <script>
        document.getElementById('resetForm').addEventListener('submit', function (event) {
            event.preventDefault();

            var formData = new FormData();
            formData.append('email', document.getElementById('email').value);
            formData.append('password', document.getElementById('password').value);
            formData.append('password_confirmation', document.getElementById('password-confirm').value);
            formData.append('token', document.querySelector('input[name="token"]').value);

            if (formData.get('password').length < 8 || formData.get('password_confirmation').length < 8) {
                alert('Hasło musi mieć co najmniej 8 znaków.');
                return;
            }

            if (formData.get('password') !== formData.get('password_confirmation')) {
                alert('Podane hasła nie są identyczne.');
                return;
            }

            fetch('/api/reset-password', {
                method: 'POST',
                body: formData,
            })
                .then(response => {
                    if (response.ok) {
                        document.getElementById('resetForm').style.display = 'none';

                        document.getElementById('successMessage').style.display = 'block';

                        document.getElementById('closeButton').addEventListener('click', function () {
                            window.close();
                        });
                    } else {
                        alert('Wystąpił problem podczas resetowania hasła. Spróbuj ponownie później.');
                    }
                })
                .catch(error => {
                    alert('Wystąpił błąd podczas wysyłania żądania.');
                    console.error(error);
                });
        });
    </script>
</body>

</html>