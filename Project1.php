<link rel="stylesheet" type="text/css" href="Style.css"></link>
<?php
$link = mysqli_connect('localhost', 'admin', 'Resto01#Test', 'php_db') or die ('Нет связи с Базой Данных');
if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}
$query = 'SELECT * FROM tanks';
$table = '<table><tr><th>Название</th><th>Нация</th><th>Тип</th><th>Наличие башни</th><th>Живучесть</th></tr>';
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
if (!empty($result)) {
    $arrData = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $arrData[] = $row;
    }
}
foreach ($arrData as $rows) {

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
?>