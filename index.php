<?php
  require("php/conn.php");
  $res = mysqli_fetch_assoc($conn->query("SELECT * FROM Companies LIMIT 1"));

  $company_id = $res['id'];

  if(isset($_GET['company_id']))
  {
    $company_id = base64_decode($_GET['company_id']);
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Анализ эмоциональных состояний</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="icon" href="favicon.ico">
</head>
<body>
    <div class="container">
    <?php if(isset($_GET['company_id'])): ?>
        <div class="test-intro bg-light p-1 rounded border my-1 mx-auto w-75">
            <h1>Тестирование по шкале эмоциональных тонов</h1>
            <p>
            Время заполнения теста не ограничено, но обычно это занимает 20-30 минут<br><br>
            Тест состоит из группы утверждений, которые либо соответствуют вашему отношению к чему-то, либо нет.<br><br> 
            Отвечайте так, как это происходит в вашей жизни сейчас, а не происходило в пошлом.<br><br>
            На любой из утверждений можно ответить:<br><br>
            <b>«Да»</b>- означает точно «да» или «в основном да»,<br>
            <b>«Нет»</b>- означает точно «нет» или «в основном нет».

            </p>
            <button class='btn btn-primary' data-bs-toggle="modal" data-bs-target="#exampleModal">Начать тест</button>
        </div>
        <!--Modal start-->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Данные сотрудника</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="php/addPerson.php" method="POST">
                    <div class="mb-3">
                      <label for="exampleInputName" class="form-label">Имя</label>
                      <input type="text" name='first_name' class="form-control" id="exampleInputName" aria-describedby="nameHelp">
                    </div>
                    <div class="mb-3">
                      <label for="exampleInputPassword1" class="form-label">Фамилия</label>
                      <input type="text" name='second_name' class="form-control" id="exampleInputPassword1">
                    </div>
                    <div class="mb-3">
                      <input type="hidden" name="company_id" class="company_id" value="<?php echo $company_id ?>">
                    </div>
                    <div class="btn-submit text-center">
                        <button type="submit" class="btn btn-primary">Начать тест</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!--Modal end-->
        <script>
          var myModal = document.getElementById('myModal')
          var myInput = document.getElementById('myInput')

          myModal.addEventListener('shown.bs.modal', function () {
            myInput.focus()
          })
        </script>
        <?php else :?>
        <div class="container">
          <h2 class='text-center'>Получите ссылку от руководства</h2>
        </div>
    <?php endif; ?>
    </div>
    <script>localStorage.clear()</script>
</body>
</html>