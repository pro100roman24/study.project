<link rel="stylesheet" type="text/css" href="Style.css"></link>
<?php
$iniArray = parse_ini_file("conf.ini");
$host = $iniArray['host'];
$user = $iniArray['user'];
$pass = $iniArray['password'];
$db = $iniArray['database'];
$link = mysqli_connect($host, $user, $pass, $db) or die ('Нет связи с Базой Данных');
//$link = mysqli_connect($ini_array['host'], $ini_array['user'], $ini_array['password'], $ini_array['database']) or die ('Нет связи с Базой Данных');
if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}
$tab = 'tanks2';
$table = '<table><tr><th>Название</th><th>Нация</th><th>Тип</th><th>Наличие башни</th><th>Живучесть</th></tr>';
$querySelect = "SELECT * FROM $tab";
$queryCreate = "create table IF NOT EXISTS $tab (id integer not null auto_increment primary key, tank varchar(25),  nation varchar(25), type varchar(25), turret varchar(25), durability integer)";
$queryInsert = "INSERT INTO $tab (tank, nation, type, turret, durability) VALUES ('".$iniArray['tank']."', '". $iniArray['nation'] . "', '". $iniArray['type'] ."', '".$iniArray['turret']."', '".$iniArray['Durability']."')";
if (mysqli_query($link, $querySelect)!==false){
    $test="Таблица $tab существует";
    echo $test;
    $result = mysqli_query($link, $querySelect);
}
else{
    $test="Таблица $tab не существовала и была создана с параметрами по умолчанию";
    echo $test;
    mysqli_multi_query($link, $queryCreate);
    mysqli_query($link, $queryInsert);
    $result = mysqli_query($link, $querySelect);
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
mysqli_close($link);
?>