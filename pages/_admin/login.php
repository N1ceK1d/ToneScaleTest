<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="icon" href="../../favicon.ico">
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container p-5">
        <?php
            if(isset($_GET['error']))
            {
                if($_GET['error'] == 1)
                {
                    echo "<p class='text-center text-danger'>Введены неверные данные</p>";
                }
            }
        ?>
        <form class='form w-25 mx-auto' action="../../php/admin_login.php" method="POST">
            <div class="mb-3">
              <label for="exampleInputName" class="form-label">Логин</label>
              <input type="text" name='admin_login' class="form-control">
            </div>
            <div class="mb-3">
              <label for="exampleInputName" class="form-label">Пароль</label>
              <input type="password" name='password' class="form-control">
            </div>
            <div class="btn-submit text-center">
                <button type="submit" class="btn btn-primary">Войти</button>
            </div>
        </form>
    </div>
</body>
</html>