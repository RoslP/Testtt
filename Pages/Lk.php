<?php require '../DB/IsOnline.php' ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unlimited nested structure</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!--    подключение к кастомным иконкам font awesome-->
    <script src="https://kit.fontawesome.com/7329f6dda8.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    <!--    скрипты необходимые для карусели и других элементов bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
            integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
            crossorigin="anonymous"></script>
    <!--    подключение стрилей-->
    <!--    js-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/Assets/JS/Process.js"></script>
    <!--    подключение шрифтов от google fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Assets/css/style.css">
</head>
<body class="body">
<header>
    <div class="container">
        <div class="row">
            <div class="col-2"><h1>Тестовое задание</h1></div>
        </div>
    </div>

</header>
<div class="container lk-container">
    <div class="row">
        <h2 class="lkHeader">Личный кабинет</h2>
        <div class="col-4">
            <div class=" lkElement1">
                <h5>Панель управления</h5>
                <p id="changeId">Изменить данные</p>
                <p id="getCustomId">Получить кастомную выборку</p>
                <p id="currentOnline">Кто на сайте сейчас</p>
            </div>
        </div>
        <div id="SectionChangeData" class="col-4" style="display: block">
            <div class="container  lkElement2">
                <div>
                    <label for="ChangeName">Изменить имя</label>
                    <div class="w-100"></div>
                    <input id="ChangeName" type="text" placeholder="Введите новое имя">
                </div>
                <div>
                    <label for="ChangeDate">Изменить дату рождения</label>
                    <div class="w-100"></div>
                    <input id="ChangeDate" type="date" placeholder="Введите новую дату">
                </div>
                <div>
                    <label for="ChangePass">Изменить пароль</label>
                    <div class="w-100"></div>
                    <input id="ChangePass" type="password" placeholder="Новый пароль">
                </div>
                <div>
                    <label for="ChangePass2">Подтвердите пароль</label>
                    <div class="w-100"></div>
                    <input id="ChangePass2" type="password" placeholder="Подтверждение">
                </div>
                <div>
                    <button id="change-data" type="button">Применить изменения</button>
                </div>
            </div>
        </div>
        <div id="SectionGetCustom" class="col-4" style="display: none">
            <div class="container lkElement">
                <p>Кастомная выборка</p>
                <select id="SearchSelect" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
                    <option value="full_name">Поиск по имени</option>
                    <option value="login">Поиск по логину</option>
                </select>
                <input id="SearchInput" placeholder="Введите данные">
                <button id="customSort" type="button">Вывести список пользователей</button>
            </div>
            <div id="TableContainer" class="container">

            </div>
        </div>
        <div id="SectionGetCurrentOnline" class="col-4" style="display: none">
            <div class="container lkElement">
                <p>Вывод онлайна</p>
                <div id="tableContainerForOnline" class="container"></div>
            </div>
        </div>
        <div class="col-4">
            <div class="exit-button  lkElement4">
                <p><b>Имя:</b> <?php echo $_SESSION['full_name'] ?></p>
                <p><b>Логин:</b> <?php echo $_SESSION['login'] ?></p>
                <p><b>День рождения:</b> <?php echo $_SESSION['date_of_birth'] ?></p>
                <p></p>
                <button id="exit-button-lk" type="button">Выход</button>
            </div>
        </div>
    </div>
</div>
</body>

</html>