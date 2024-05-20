document.addEventListener("DOMContentLoaded", () => {

    //Выход из лк
    let ExitButtonFromLk = document.getElementById("exit-button-lk");
    //Изменение данных лк
    let ChangeData = document.getElementById("change-data");

    //Элементы сайдбара в лк
    let ChangeDataOption = document.getElementById("changeId");
    let GetCustom = document.getElementById("getCustomId");
    let GetCurrentOnline = document.getElementById("currentOnline");

    //блоки в зависимости от выбранного <p> сайдбара
    let BlockOptionChange = document.getElementById("SectionChangeData");
    let BlockGetCustom = document.getElementById("SectionGetCustom");
    let BlockGetCurrentOnline = document.getElementById("SectionGetCurrentOnline");

    //Далее селект и инпут в лк для кастомной выборки
    let SelectChange = document.getElementById("SearchSelect");
    let InputChange = document.getElementById("SearchInput");
    //Кнопка отправки кастомной выборки
    let CustomDoButton = document.getElementById("customSort");


    SelectChange.addEventListener("change", function (e) {
        let select_value = SelectChange.value;
        if (select_value === "full_name") {
            InputChange.placeholder = 'Введите имя';
        }
        if (select_value === "login") {
            InputChange.placeholder = 'Введите логин';
        }
    })

    //Блок изменения данных
    ChangeDataOption.addEventListener("click", (e) => {
        BlockOptionChange.style.display = "block";
        BlockGetCustom.style.display = "none";
        BlockGetCurrentOnline.style.display = "none";
    })
    //Блок кастомной выборки
    GetCustom.addEventListener("click", (e) => {
        BlockOptionChange.style.display = "none";
        BlockGetCustom.style.display = "block";
        BlockGetCurrentOnline.style.display = "none";
    })
    //Блок онлайна
    GetCurrentOnline.addEventListener("click", (e) => {
        BlockOptionChange.style.display = "none";
        BlockGetCustom.style.display = "none";
        BlockGetCurrentOnline.style.display = "block";
        let data = {
            playload: 'select-who-online'
        }
        $('#tableContainerForOnline').empty();
        $.ajax({
            type: "POST",
            url: '/DB/Calls.php',
            contentType: 'application/json; charset=UTF-8',
            data: JSON.stringify(data),
            success: function (response) {
                // Создание таблицы
                let table = $('<table>').addClass('table');

                // Проверка, что у нас есть данные в ответе
                if (response.length > 0) {

                    // Создание заголовков таблицы на основе ключей первого объекта в массиве response
                    let headers = Object.keys(response[0]);

                    // Создание строки для заголовков таблицы
                    let headerRow = $('<tr>');
                    headers.forEach(function (header) {
                        if (header === 'password') {
                            return;
                        }
                        headerRow.append($('<th>').text(header));
                    });

                    // Добавление строки заголовков в thead таблицы
                    $('<thead>').append(headerRow).appendTo(table);

                    // Создание тела таблицы
                    let tbody = $('<tbody>');

                    // Добавление строк данных в таблицу
                    response.forEach(function (rowData) {
                        let row = $('<tr>');
                        headers.forEach(function (header) {
                            if (header === 'password') {
                                return;
                            }
                            row.append($('<td>').text(rowData[header]));
                        });
                        tbody.append(row);
                    });

                    // Добавление тела таблицы в таблицу
                    table.append(tbody);

                    let button = $("<button>", {
                        text: "Выгрузить в csv",
                        type: "button",
                        id: "PutInCsv"
                    });

                    // Добавление таблицы в контейнер
                    $('#tableContainerForOnline').append(table).append(button);
                }
            }
        });
    })


    //Кнопка выгрузки в CSV
    $(document).on("click", "#PutInCsv", function (e) {
        let data = {playload: 'put-in-csv'};
        $.ajax({
            type: "POST",
            url: '/DB/Calls.php',
            contentType: 'application/json; charset=UTF-8',
            data: JSON.stringify(data),
            success: function () {
                alert('Данные успешно загружены');
            }
        })
    })

//Запуск кастомной выборки
    CustomDoButton.addEventListener("click", (e) => {
        let select_value = SelectChange.value;
        let input_value = InputChange.value;

        let data = {
            playload: 'custom-select',
            data: {}
        }
        data.data[select_value] = input_value;
        // Очистка содержимого контейнера таблицы
        $('#TableContainer').empty();
        $.ajax({
            type: "POST",
            url: '/DB/Calls.php',
            contentType: 'application/json; charset=UTF-8',
            data: JSON.stringify(data),
            success: function (response) {

                // Создание таблицы
                let table = $('<table>').addClass('table');

                // Проверка, что у нас есть данные в ответе
                if (response.length > 0) {

                    // Создание заголовков таблицы на основе ключей первого объекта в массиве response
                    let headers = Object.keys(response[0]);

                    // Создание строки для заголовков таблицы
                    let headerRow = $('<tr>');
                    headers.forEach(function (header) {
                        if (header === 'password') {
                            return;
                        }
                        headerRow.append($('<th>').text(header));
                    });

                    // Добавление строки заголовков в thead таблицы
                    $('<thead>').append(headerRow).appendTo(table);

                    // Создание тела таблицы
                    let tbody = $('<tbody>');

                    // Добавление строк данных в таблицу
                    response.forEach(function (rowData) {
                        let row = $('<tr>');
                        headers.forEach(function (header) {
                            if (header === 'password') {
                                return;
                            }
                            row.append($('<td>').text(rowData[header]));
                        });
                        tbody.append(row);
                    });

                    // Добавление тела таблицы в таблицу
                    table.append(tbody);
                    // Добавление таблицы в контейнер
                    $('#TableContainer').append(table);
                }
            }
        })
    })

//Выход
    ExitButtonFromLk.addEventListener("click", () => {
        let qwe = {playload: 'exit-from-lk'}
        $.ajax({
            url: '/DB/Calls.php',
            type: 'POST',
            contentType: 'application/json; charset=UTF-8',
            data: JSON.stringify(qwe),
            success: function () {
                window.location.href = '/index.php';
            }
        })
    })
//Изменение данных
    ChangeData.addEventListener("click", (e) => {

        let name = $("#ChangeName").val();
        let date = $("#ChangeDate").val();
        let pass1 = $("#ChangePass").val();
        let pass2 = $("#ChangePass2").val();

        if (pass1 !== pass2) {
            alert("Введите корректный пароль");
        } else {
            let data = {
                full_name: name,
                date_of_birth: date,
                password: pass1,
            }
            let completeData = {
                playload: 'DataChange',
                data: {}
            };

            $.each(data, function (key, value) {
                if (value !== '') {
                    completeData.data[key] = value;
                }
            });
            $.ajax({
                type: 'POST',
                url: '/DB/Calls.php',
                contentType: 'application/json; charset=UTF-8',
                data: JSON.stringify(completeData),
                success: function () {
                    if (confirm("Данные успешно обновлены")) {
                        location.reload();
                    } else {
                        location.reload();
                    }
                }
            });
        }

    })
})
