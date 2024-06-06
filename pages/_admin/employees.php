<?php
    session_start();
    if(!isset($_SESSION['admin_id']))
    {
        header("Location: login.php");
    }
    require("../../php/conn.php");
    $company_id = 0;
    if(isset($_GET['company_id']))
    {
        $company_id = $_GET['company_id'];
    }
    $user_type = 0;
    if(isset($_GET['user_type']))
    {
        $user_type = $_GET['user_type'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Работники</title>
    <link rel="icon" href="../../favicon.ico">
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <script src="../../bootstrap/js/bootstrap.min.js"></script>
    <script src="../../js/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js" integrity="sha384-NaWTHo/8YCBYJ59830LTz/P4aQZK1sS0SneOgAvhsIl3zBu8r9RevNg5lHCHAuQ/" crossorigin="anonymous"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="../../js/getPDF.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
    <div class="container p-1">
        <h1>Анализ эмоциональных состояний</h1> 
        <h1>Сотрудники</h1>
        <?php require("../../php/admin_header.php"); ?>
        <button class='pdf_export btn btn-primary'>Экспорт PDF</button>
        <form class="search form border w-25 p-2 m-auto text-center" action="" method="GET">
            <select name="company_id" class="form-select form-select-sm my-1 w-100" aria-label="Default select example">
                <option value="0" selected>Все компании</option>
                <?php
                    $companies = $conn->query("SELECT * FROM Companies;");
                    foreach ($companies as $row):?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php endforeach ?>
            </select>
            <input type="submit" value="Найти" class="btn btn-primary my-1">
        </form>
        <div class="diagramms">
            <?php 
                $sql = "SELECT *, Users.id as user_id,
                CONCAT(Users.second_name, ' ', Users.first_name) as fullname
                FROM Users
                INNER JOIN Companies ON Users.company_id = Companies.id ORDER BY test_time";
                if($company_id > 0)
                {
                    $sql = "SELECT *, Users.id as user_id,
                    CONCAT(Users.second_name, ' ', Users.first_name) as fullname
                    FROM Users
                    INNER JOIN Companies ON Users.company_id = Companies.id WHERE company_id = $company_id ORDER BY test_time";
                }
            ?>
            <?php foreach($users = $conn->query($sql) as $row):?>
                <div class="employee-item border my-1 w-100" id='user_<?php echo $row['user_id']; ?>'>
                    <div class="employee_header bg-primary text-white p-1">
                        <p class="name h3 mb-0"><?php echo $row['fullname']; ?></p>
                        <p class="lead"><?php echo $row['name']; ?></p>
                        <p><?php
                            $time = strtotime($row['test_time']);
                            echo date("d/m/y H:i", $time);
                        ?></p>
                    </div>
                    <div class="employee-body p-1">
                        <?php
                            $answers = $conn->query("SELECT * FROM UsersResults WHERE user_id = ".$row['user_id']." LIMIT 1");
                            foreach($answers as $answer):?>
                            <table class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <td class='text-center'><b>Уровень тона</b></td>
                                        <!-- <td class='text-center'><b>Ответ</b></td> -->
                                        <td><b>Описание</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php getUserResult($answer['points']); ?>
                                    </tr>
                                </tbody>
                            </table>
                        <?php endforeach; ?>
                    </div>
                    <div class="employee-footer p-1 bg-light">
                        <button class='btn btn-primary get_pdf'>Скачать</button>
                        <form action="../../php/deleteUser.php" method="post" class="my-1 delete_form">
                            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                            <input type="button" class="btn btn-danger" value="Удалить"
                            data-bs-toggle="modal" data-bs-target="#exampleModal2" data-bs-whatever="<?php echo $row['user_id'];?>">
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php 
            $name = "Все компании";
            $company_name = $conn->query("SELECT * FROM Companies WHERE id = $company_id");
            
            if($company_name->num_rows > 0)
            {
                $name = mysqli_fetch_assoc($company_name)['name'];
            }
        ?>
    </div>
    
    <script>
        $('.pdf_export').on('click', () => {
            $('.get_pdf').hide();
            $('.delete_form').hide();
            generatePDF2('<?php echo $name; ?>', 'PDF');
            $('.get_pdf').show();
            $('.delete_form').show();
        })
        
        $('.get_pdf').on('click', (event) => {
            $('.get_pdf').hide();
            $('.delete_form').hide();
            generateSolidPDF('<?php echo $name; ?>', 'PDF', $(event.target).parent().parent().attr('id'));
            $('.get_pdf').show();
            $('.delete_form').show();
        })
    </script>
    <!--Modal Start-->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Удаление тестируемого</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="../../php/deleteUser.php">
                        <input type="hidden" name="user_id" value="" class='user_id'>
                        <div class="mb-3">
                            <p>Вы уверены, что хотите удалить данные этого тестируемого?</p>
                        </div>
                        <button type="submit" class="btn btn-danger">Удалить</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Modal End-->
    <script>
        var exampleModal = document.getElementById('exampleModal2')
        exampleModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget
            var recipient = button.getAttribute('data-bs-whatever');

            var modalBodyInput = exampleModal.querySelector('.modal-body #recipient-name ')
            console.log(recipient);
            exampleModal.querySelector('.modal-body .user_id').value = recipient;
        })
    </script>
</body>
</html>
<?php 
function getUserResult($points)
{
    switch ($points) {
        case $points == '0.1-0.5':
            echo "<td>0.1-0.5</td>";
            // echo "<td>Апатия</td>";
            echo "<td>
            Ни на что не способен.
            Хронически болен. Отказывается от помощи, нужной для поддержания жизни.
            Глубочайшая апатия и безразличие ко всему происходящему вокруг.
            Никакого контроля над собой, другими людьми, окружающим миром. Очень серьёзная обуза, нуждается в заботе и помощи, причём со своей стороны не делает никакого вклада. Этика отсутствует полностью. Мало общается. 
            Ответственность за что-либо отсутствует. Настойчивость к достижению цели отсутствует. Воспринимает всё абсолютно буквально.
            Не испытывает удовольствие.
            Общество чаще всего его ни во что не ставит и не бывает никакой дружбы. Его в основном игнорируют
            Не прилагает никаких усилий. Совершенная неудача.
            </td>";
            break;
        case $points == '0.6-1.0':
            echo "<td>0.6-1.0</td>";
            // echo "<td>Горе/Страх</td>";
            echo "<td>
            Мало на что способен.
            Частые заболевания и очень слабый иммунитет. Часто бывает в страхе и горе, боится всего подряд. С трудом контролирует самого себя и не контролирует ничего вокруг.
            Часто является обузой для общества. Полное невнимание к другим людям.
            Готов подчиняться всем. Излагает факты, не имеет представления об их реальности.
            Говорит очень мало и только безразличным тоном. Мало к чему прислушивается: в основном к апатии или к жалости. Обращает мало внимания на общение. Не передаёт сообщения. Испытывает сильное сомнение и беспокойство. Под давлением легко принимает чужую реальность. Отсутствует способность брать на себя ответственность.
            Буквально воспринимает все замечания, сделанные со страхом.
            Может плакать, чтобы вызвать к себе жалость. Частая ложь с целью вызвать сочувствие. Любое замечание может стать для него внушительным.
            Очень большая обуза в дружбе. Не нравитесь окружению, некоторые лишь испытывают жалость. Окружающие не понимают его. 
            Шансов на успех нет.
            </td>";
            break;
        case $points == '1.1-1.4':
            echo "<td>1.1-1.4</td>";
            // echo "<td>Скрытая враждебность</td>";
            echo "<td>
            В основном способен выполнять незначительные действия.
            Низкий иммунитет и сопротивляемость болезням. Чаще находится в страхе.
            Часто может терять контроль над разумом и эмоциями. Использует коварные методы для осуществления контроля над людьми.
            Может активно создавать проблемы. Часто негативно влияет на других. Скрытые злые намерения перевешивают видимую ценность.
            Чаще всего несчастен без всякой на то причины. Якобы этичная деятельность прикрывает негативную этику.
            Изобретательное и злонамеренное искажение правды. Искусно скрывает ложь.
            Иногда действуют из под тяжка, во всех остальных случаях проявляет трусость.
            В основном нравится слушать об интригах и сплетнях.
            Колеблется при движении к любой цели, плохая концентрация. Взбалмошный и легкомысленный.
            Не воспринимает никаких замечаний. Чаще всего зло острит над другими и принижает других людей. Стремится скрыто контролировать.
            Как друг - опасная обуза. Окружение в основном его презирают.
            Чаще всего терпит неудачу.
            </td>";
            break;
        case $points == '1.5-2.0':
            echo "<td>1.5-2.0</td>";
            // echo "<td>Гнев</td>";
            echo "<td>
            Часто способен на разрушительные действия.
            Невысокий иммунитет и сопротивляемость болезням. 
            Часто бывает в гневе, крушит и уничтожает всех и все вокруг.
            Чаще всего неискренен. Может разрушать, даже если открыто заявляет о хороших намерениях. Часто совершает нечестные действия. Разрушает все этические нормы.
            Использует откровенную ложь, несущую разрушение.
            Безрассудная храбрость обычно во вред себе.
            Много говорит о смерти, разрушении, ненависти. Уничтожает коммуникационные линии, несущие позитив. Не соглашается с тем, что реально для других людей.
            Настойчивость в достижении разрушительных целей: сильно разгорается, но быстро гаснет.
            Воспринимает буквально тревожные замечания. Грубое чувство юмора.
            Использует угрозы, наказания и ложные тревожные сообщения, чтобы господствовать над людьми.
            Сильно сопротивляется замечаниям, но впитывает их.
            Редко испытывает какое-либо удовольствие. Обуза в дружбе. Большинство его не любят и не ценят. Окружение его не понимают.
            Обычно терпит неудачу.
            </td>";
            break;
        case $points == '2.1-2.5':
            echo "<td>2.1-2.5</td>";
            // echo "<td>Открытая враждебность</td>";
            echo "<td>
            Может быть способен на разрушительные и некоторые созидательные действия.
            Бывает, что время от времени тяжело болеет.
            Часто и открыто возмущается. Проявляет антагонизм (соперничество, конкуренция, борьба, противостояние, противоречия). Часто стремится командовать.
            Существует потенциальная возможность причинения вреда другим.
            Довольно часто бывает нечестен. Искажает правду в угоду антагонизму.
            Часто импульсивно, необдуманно бросается навстречу опасности.
            Иногда говорит угрозами. Бывает, что обесценивает других людей и их действия. Может открыто насмехаться над кем-то.
            Часто передаёт враждебные или угрожающие сообщений. Не часто позитивен.
            Высказывает сомнение. Попытки скрыто атаковать других людей. Не соглашается, бьётся над тем, что в его интересах.
            Использует ответственность для достижения собственных целей.
            Воспринимает в основном замечания с противоречием и несогласием.
            Часто придирается и критикует, добиваясь выполнения своих желаний.
            Не очень хороший друг. Не часто нравится окружающим.
            Личные вещи часто в запущенном состоянии. Окружающие часто его не понимают.
            Шансы на успех не очень хорошие.
            </td>";
            break;
        case $points == '2.6-3.0':
            echo "<td>2.6-3.0</td>";
            // echo "<td>Скука</td>";
            echo "<td>
            Не слишком активен и не всегда способен действовать. Время от времени болеет.
            Иногда безразличен к некоторым вещам. Чаще в скуке.
            Готов к созидательным действиям, но не всегда в большом объёме. Хорошо приспосабливается к различным ситуациям.
            Не всегда относится к этике искренне. Не всегда честен и искренен. Бывает, что проявляет безразличие относительно истинности чего-либо.
            Часто пренебрегает опасность. Любит слушать только разговоры об обыденных делах.
            Может искажать значение срочности дел. Не всегда можно доверять.
            Невысокая настойчивость к достижению цели. Часто плохая концентрация внимания.
            Не особо заботится о поддержке со стороны других людей.
            Неплохой друг. Не всегда и не всем людям нравится.
            Иногда бывает запущенность к принадлежащим ему вещам.
            Его не всегда понимают окружающие.
            Неплохие шансы на успех.
            </td>";
            break;
        case $points == '3.1-3.5':
            echo "<td>3.1-3.5</td>";
            // echo "<td>Консерватизм</td>";
            echo "<td>
            Умеренное количество действий, часто занимается спортом.
            Достаточно высокая сопротивляемость инфекция и другим болезням.
            Удовлетворение жизнью и всем окружающим.
            Хорошо развита способность разумно мыслить. Сдержано проявляет эмоции. Признает права других. Демократичен.
            Является достаточно ценным для общества. Максимально честно следует привитой ему этике. Морален.
            Чаще всего правдив, обманывает только при острой необходимости.
            Проявляет смелость. На риск идёт, если риск невелик.
            Нерешительно высказывает ограниченное количество собственных мыслей. Консервативен. Имеет склонность к умеренно консервативным действиям и творчеству. Испытывает сдержанное согласие с чем-либо
            Не всегда готов брать ответственность. Достаточное настойчив, если препятствия не слишком велики.
            Хорошая способность воспринимать смысловые различия между высказываниями.
            Вызывает поддержку других людей своим практическим суждением и достоинствами, которые ценятся в обществе. Хорошо следит за своими вещами.
            Хороший друг. Большинство людей его уважают и хорошо понимают.
            Хорошие шансы на успех
            </td>";
            break;
        case $points == '3.6-4.0':
            echo "<td>3.6-4.0</td>";
            // echo "<td>Интерес</td>";
            echo "<td>
            Хорошая способность работать над проектами и выполнять намеченное. Частые успехи в спорте. Быстрая реакция (в зависимости от возраста).
            Высокий иммунитет к болезням.
            К тому, чем занимается испытывает довольно сильный интерес.
            Хорошо развита способность разумно мыслить. Хорошо контролирует людей и предметы. Свободное проявление эмоций.
            Ценен для общества. Изменяет окружающий мир на благо себе и другим. Считается с правилами и нормами группы и совершенствует их. Очень высокий этический уровень. Почти всегда говорит правду. Проявляет смелость, если риск оправдан.
            В основном передаёт сообщение, несущие позитив. Старается дать отпор негативу
            Способен понимать и оценивать реальность других людей и изменять точку зрения. Готовность прийти к согласию с другими.
            Постоянно способен принимать и нести ответственность. Достаточно большая настойчивость. Стремление к созидательным целям.
            Хорошо понимает и воспринимает сказанное для него. Хорошее чувство юмора.
            Приобретает поддержку благодаря своему творческому мышлению и жизненной энергии. Очень хороший друг. Его ценят, любят и хорошо понимают окружающие.
            Вещи, принадлежащие ему, находятся в хорошем состоянии.
            Его шансы на успех очень хорошие.
            </td>";
            break;
    }
}
?>