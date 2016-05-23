<?
mysql_connect("localhost", "root", "2212420") or
    die("Ошибка соединения: " . mysql_error());
mysql_select_db("ikstand");

$image = imagecreatefromjpeg('fff.jpg');

// choose a color for the ellipse
$ellipseColor = imagecolorallocate($image, 0, 0, 255);
$labelColor = imagecolorallocate($image, 255, 0, 0);

// draw the blue ellipse
$group=(int)$_REQUEST['group'];
$result = mysql_query("SELECT sensors.x,sensors.y,sensors.id FROM sensors,data where data.sensor_id=sensors.id and data.group_id=".$group);
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    //printf( $row["distance"], $row["sensor_id"]);
	imagefilledellipse($image, $row['x'], $row['y'], 10, 10, $ellipseColor);
	imagestring ($image , 5 , $row['x'] , $row['y'] , $row['id'] , $labelColor );
}

mysql_free_result($result);
//imagefilledellipse($image, 435, 50, 10, 10, $ellipseColor);
//imagefilledellipse($image, 610, 50, 10, 10, $ellipseColor);

// Output the image.
header("Content-type: image/jpeg");
imagejpeg($image);

?>
