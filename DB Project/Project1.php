<link rel="stylesheet" type="text/css" href="Style.css"></link>
<?php
$iniArray = parse_ini_file("conf.ini");
$host = $iniArray['host'];
$user = $iniArray['user'];
$pass = $iniArray['password'];
$db = $iniArray['database'];
$link = mysqli_connect($host, $user, $pass, $db) or die ('Нет связи с Базой Данных');
if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
};
$tab = 'tanks3';
$json = file_get_contents('new.json');
$jsonArray = (json_decode($json, true));
$table = '<table><tr><th>Название</th><th>Нация</th><th>Тип</th><th>Наличие башни</th><th>Живучесть</th></tr>';
$querySelect = "SELECT * FROM $tab";
$queryCreate = "create table $tab 
    (
        id integer not null auto_increment primary key, tank varchar(25),
        nation varchar(25), type varchar(25), turret varchar(25), durability integer
    )";
$insertIni = "INSERT INTO $tab 
    (tank, nation, type, turret, durability)
    VALUES ('".$iniArray['tank']."', '". $iniArray['nation'] . "', '".
    $iniArray['type'] ."', '".$iniArray['turret']."', '".$iniArray['Durability'].
    "')";

if (mysqli_query($link, $querySelect)!==false and mysqli_num_rows(mysqli_query($link, $querySelect))==0){ //если select вернул не пустое значение и в таблице 0 строк
    foreach ($jsonArray as $jrows){
        foreach ($jrows as $jvalues){
            $insertJson = "INSERT INTO $tab (tank, nation, type, turret, durability)
            VALUES ('".$jvalues['tank']."', '". $jvalues['nation'] . "', '".
                $jvalues['type'] ."', '".$jvalues['turret']."', '".$jvalues['durability'].
                "')";
            mysqli_query($link,$insertJson);       //создаем в базе строчки из json
        }
    }
    mysqli_query($link, $insertIni);                                    //тогда заполняем пустую таблицу нашей строкой из ini файла
    $result = mysqli_query($link, $querySelect);                               //результат select-а кладем в result
    $text = "Таблица $tab была пустой и заполнена параметрами по умолчанию";

}
elseif (mysqli_query($link, $querySelect)!==false){                    //если select вернул не пустое значение, но в таблице уже не 0 строк
    $text = "Таблица $tab существует ";
    $result = mysqli_query($link, $querySelect);                      //делаем select в result
}
else{                                                               //иначе таблицы не существует
    $text = "Таблица $tab не существовала и была создана с параметрами по умолчанию";
    mysqli_query($link, $queryCreate);     //создаем таблицу
    foreach ($jsonArray as $jrows){
        foreach ($jrows as $jvalues){
            $insertJson = "INSERT INTO $tab (tank, nation, type, turret, durability)
            VALUES ('".$jvalues['tank']."', '". $jvalues['nation'] . "', '".
            $jvalues['type'] ."', '".$jvalues['turret']."', '".$jvalues['durability'].
                "')";
            mysqli_query($link,$insertJson);       //создаем в базе строчки из json
        }
    }
    mysqli_query($link, $insertIni);                           //заполняем строкой из ini файла
    $result = mysqli_query($link, $querySelect);                 //результат select-а кладем в result
}

    if (!empty($result)) {                  //это наверно надо убрать, result не может быть пустым в любом случае
    $arrData = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $arrData[] = $row;                      //из всех результатов собираем массив
    }
}
foreach ($arrData as $rows) {                 //пересобираем массив $arrData в таблицу $table для вывода
    $table .= '<tr>';
    foreach ($rows as $colName => $value) {
        if ($colName != 'id') {
            if ($colName = 'Durability' and $value > 100) {    //подсвечиваим значения >100
                $table .= "<td class=redStyle>$value</td>";
            } else {
                $table .= "<td>$value</td>";
            }
        }
    }
}
echo $table;
echo $text;
mysqli_close($link);
?>