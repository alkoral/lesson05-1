<?php
require __DIR__ . '/vendor/autoload.php';
$i=0;

function get_param($param_name) {
  if (isset($_REQUEST[$param_name]) and !empty($_REQUEST[$param_name])) {
    return strip_tags(trim($_REQUEST[$param_name]));
  }
  else {
    return "";
  }
}

$action=get_param('action');
$address=get_param('address');
$addressID=get_param('addressID');
if (empty($addressID)) {
	$addressID=1;
}
$getplacemark = "55.663568, 37.770537";
$getaddress = "Такого адреса не существует. Может, лучше в Москву?";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Домашнее задание к лекции 5.1 «Менеджер зависимостей Composer»</title>
	<link rel="stylesheet" type="text/css" href="style.css" >
	<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript">
	</script>
</head>
<body>
<article>
<h1>Определение географических координат по адресу</h1>
<form method="POST" action="index.php">
	<input type="text" name="address" size="70" placeholder="Введите адрес. Например: г. Москва, ул. Белореченская, 30">
	<input type="submit" name="search" value="Найти">
	<input type="hidden" name="action" value="search">
</form>
<br>

<?php
$api = new \Yandex\Geo\Api();

// Можно искать по точке
//$api->setPoint(	37.770537, 55.663568);

// Или можно искать по адресу
if ($action!=='search' or empty($address)) {
	die;
}
?>
	<h2>Результат поиска</h2>
	<table>
	<tr>
		<th>Адрес</th>
		<th>Широта</th>
		<th>Долгота</th>

<?php
	$api->setQuery($address);

// Настройка фильтров
$api
    ->setLimit(10) // кол-во результатов
    ->setLang(\Yandex\Geo\Api::LANG_RU) // локаль ответа
    ->load();

$response = $api->getResponse();
$response->getFoundCount(); // кол-во найденных адресов
$response->getQuery(); // исходный запрос
$response->getLatitude(); // широта для исходного запроса
$response->getLongitude(); // долгота для исходного запроса

$i=1;
// Список найденных точек
$collection = $response->getList();
foreach ($collection as $item) {
    $item->getAddress(); // вернет адрес
    $item->getLatitude(); // широта
    $item->getLongitude(); // долгота
    $item->getData(); // необработанные данные
    		if($i==$addressID) {
			$getaddress=$item->getAddress();
    	$getplacemark = $item->getLatitude().", " .$item->getLongitude();
    		}
    echo"
    	<tr>
    	<td><a href='?address=".$address."&addressID=".$i++."&action=search'>".$item->getAddress()."</a></td>
    	<td>".$item->getLatitude()."</td>
    	<td>".$item->getLongitude()."</td>
    	</tr>";
}
?>
</table>
<br>

<?php include "map.php"; ?>

</article>>

</body>
</html>