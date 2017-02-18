<?php
$servername = "127.0.0.1";
$username = "username";
$password = "password";
$dbname = "feedback";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

$requestData= $_REQUEST;

$startDate = date('Y-m-d');
$endDate = date('Y-m-d');
if($requestData['startDate'] && $requestData['endDate']) {
    $startDate = mysqli_real_escape_string($conn,$requestData['startDate']);
    $endDate = mysqli_real_escape_string($conn,$requestData['endDate']);
}
$series = array();
$data = array();
$series[] = array("label" => "admin_count", "highlighter" => array("formatString" =>"admin count %s %s"));
$series[] = array("label" => "player_count", "highlighter" => array("formatString" =>"player count %s %s"));

$sql = "SELECT playercount,admincount,time FROM legacy_population WHERE time > '$startDate 00:00:00' AND time < '$endDate 23:59:59';";
$query=mysqli_query($conn, $sql) or die("error getting data");

if(mysqli_num_rows($query) <= 0) {
    // Empty dataset
    die('{"data":[[null]],"series":[],"axes":{"xaxis":{"min":"'.$startDate.' 00:00:00","max":"'.$endDate.' 23:59:59"}}}');
}

while( $row=mysqli_fetch_array($query) ) {  // preparing an array
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
    "axes" => array("xaxis" => array("min" => $startDate.' 00:00:00', "max" => $endDate.' 23:59:59')),
    "resetAxes" => 1
);

echo json_encode($json_data);

?>