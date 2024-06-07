<?php
    require("../php/conn.php");
    session_start();
    $questions = $conn->query("SELECT * FROM Questions;");
    $has_answers = $conn->query("SELECT * FROM UsersResults WHERE user_id = " . $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестирование по шкале эмоциональных тонов</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
</head>
<body>
    <?= $has_answers->num_rows > 0 ? '<script>window.location.href = "endTest.php";</script>' : '' ?>
    <div class="container">
        <div id="liveAlertPlaceholder"></div>
        <form class="questions mx-auto p-1" method='POST' action='../php/calculatePoints.php'>
            <?php foreach($questions as $question):?>
                <div class="question bg-light border rounded p-1 w-75 my-1">
                    <p><b><?= $question['id'] ?>. </b><?= $question['question_text'] ?></p>
                    <div class="answers row">
                        <div class="answer-item">
                            <input type="radio" class='check_input' name="answer[<?php echo $question['id'] ?>]" value='1'>
                            <label for="">Да</label>
                        </div>
                        <div class="answer-item">
                            <input type="radio" class='check_input' name="answer[<?php echo $question['id'] ?>]" value='0'>
                            <label for="">Нет</label>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <input class='end_test_btn btn btn-primary' type="button" value="Закончить тест">
            <!-- <button >Закончить тест</button> -->
        </form>
    </div>
    <!-- <script src="../js/auto_test.js"></script> -->
    <script src="../js/test.js"></script>
</body>
</html>