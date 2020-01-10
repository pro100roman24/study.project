<link rel="stylesheet" type="text/css" href="Style.css"></link>
<?php
$link = mysqli_connect('localhost', 'admin', 'Resto01Test', 'php_db') or die ('Нет связи с Базой Данных');
if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}
$query = 'SELECT * FROM tanks';
$table = '<table><tr><th>Название</th><th>Нация</th><th>Тип</th><th>Наличие башни</th><th>Живучесть</th></tr>';
$tr = '<tr>';
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
if (!empty($result)) {
    $arrData = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $arrData[] = $row;
    }
}
// теперь с помощью foreach перебираешь массив
//echo $table;
foreach ($arrData as $key => $rows) {
    //echo $tr;
    $table .= '<tr>';
    foreach ($rows as $colName => $value) {
        if ($colName != 'id') {
            if ($colName = 'Durability' and $value > 100) {
                $table .= "<td class=redStyle>$value</td>";
            } else {
                $table .= "<td>$value</td>";
            }

        }
    }
}
echo $table;
//звони
//записываешь всё в переменную , а потом делаешь одно ечо
/// сам ещё поковыряй, потом исправлю, сейчас очень неудобно
/// //PSR-1, PSR-2
//?>