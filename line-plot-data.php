<?php
require_once('mysql_login.php');

$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);

$requestData= $_REQUEST;

$startDate = date('Y-m-d');
$endDate = date('Y-m-d');
if($requestData['startDate'] && $requestData['endDate']) {
    $startDate = $requestData['startDate'].' 00:00:00';
    $endDate = $requestData['endDate'].' 23:59:59';
}
$series = array();
$data = array();
$series[] = array("label" => "admin_count", "highlighter" => array("formatString" =>"admin count %s %s"));
$series[] = array("label" => "player_count", "highlighter" => array("formatString" =>"player count %s %s"));

$query = $conn->prepare("SELECT playercount,admincount,time FROM legacy_population WHERE time > ? AND time < ?");
$query->execute(array($startDate, $endDate));

if($query->rowCount() <= 0) {
    // Empty dataset
    die('{"data":[[null]],"series":[],"axes":{"xaxis":{"min":"'.$startDate.'","max":"'.$endDate.'"}}}');
}

while($row=$query->fetch(PDO::FETCH_ASSOC)) {  // preparing an array
    if($row["admincount"]) {
        $data[0][] = array($row["time"], (int)$row["admincount"]);
    }
    if($row["playercount"]) {
        $data[1][] = array($row["time"], (int)$row["playercount"]);
    }
}

$json_data = array(
    "data" => $data,
    "series" => $series,
    "axes" => array("xaxis" => array("min" => $startDate, "max" => $endDate)),
    "resetAxes" => 1
);

echo json_encode($json_data);

?>