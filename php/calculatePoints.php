<?php
require('conn.php');
session_start();

function moreYes($list)
{
    $yes = 0;
    $no = 0;

    foreach ($list as $answer) {
        if ($answer == 1) {
            $yes++;
        } else {
            $no++;
        }
    }
    if($yes > $no) {
        return 1;
    } else {
        return 0;
    }
}

if(isset($_POST['user_id']))
{
    $user_id = $_POST['user_id'];
} else {
    $user_id = $_SESSION['user_id'];
}

$answers = isset($_POST['answer']) ? $_POST['answer'] : 0;

$sql = "SELECT * FROM Questions";
$questions = $conn->query($sql);

$types = array();

// Проходим по каждому вопросу
foreach ($questions as $question) {
    // Получаем тип вопроса
    $type = $question['question_type'];

    // Если тип еще не существует в массиве, создаем его
    if (!isset($types[$type])) {
        $types[$type] = array();
    }

    // Добавляем значение ответа для текущего вопроса в соответствующий список типов
    $types[$type][] = $answers[$question['id']];
}
$res = 0;

if(moreYes($types['1'])) {
    if(moreYes($types['2'])) {
        if(moreYes($types['4'])) {
            $res = '3.6-4.0';
        } else {
            $res = '3.1-3.5';
        }
    } else {
        if(moreYes($types['6'])) {
            $res = '2.6-3.0';
        } else {
            $res = '2.1-2.5';
        }
    }
} else {
    if(moreYes($types['3'])) {
        if(moreYes($types['5'])) {
            $res = '1.5-2.0';
        } else {
            $res = '1.1-1.4';
        }
    } else {
        if(moreYes($types['7'])) {
            $res = '0.6-1.0';
        } else {
            $res = '0.1-0.5';
        }
    }
}

if($conn->query("INSERT INTO UsersResults (user_id, points) VALUES ($user_id, '$res')"))
{
    $add_time = "UPDATE Users SET test_time = NOW() WHERE id = $user_id";
    $conn->query($add_time);
    header("Location: ../pages/endTest.php");
}
?>
