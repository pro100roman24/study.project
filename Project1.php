<link rel="stylesheet" type="text/css" href="Style.css"></link>
<?php
$ini_array = parse_ini_file("conf.ini");
$host = $ini_array['host'];
$user = $ini_array['user'];
$pass = $ini_array['password'];
$db = $ini_array['database'];
$link = mysqli_connect($host, $user, $pass, $db) or die ('Нет связи с Базой Данных');
//$link = mysqli_connect($ini_array['host'], $ini_array['user'], $ini_array['password'], $ini_array['database']) or die ('Нет связи с Базой Данных');
if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}
$tab = 'tanks15';
$table = '<table><tr><th>Название</th><th>Нация</th><th>Тип</th><th>Наличие башни</th><th>Живучесть</th></tr>';
$query1 = "SELECT * FROM $tab";
$query2 = "create table  $tab (id integer not null auto_increment primary key, tank varchar(25),  nation varchar(25), type varchar(25), turret varchar(25), durability integer)";
$query3 = "INSERT INTO $tab (tank, nation, type, turret, durability) VALUES ('".$ini_array['tank']."', '". $ini_array['nation'] . "', '". $ini_array['type'] ."', '".$ini_array['turret']."', '".$ini_array['Durability']."')";
if (mysqli_query($link, $query1)!==false){
    $test="Таблица $tab существует";
    echo $test;
    $result = mysqli_query($link, $query1); //
}
else{
    $test="Таблица $tab не существовала и была создана с параметрами по умолчанию";
    echo $test;
    mysqli_query($link, $query2);
    mysqli_query($link, $query3);
    $result = mysqli_query($link, $query1);
}
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