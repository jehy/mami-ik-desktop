<?
mysql_connect("localhost", "root", "2212420") or
    die("Ошибка соединения: " . mysql_error());
mysql_select_db("ikstand");

if($_REQUEST['action']=='add')
{
	$distance=(int)$_REQUEST['distance'];
	if(!$distance)
	{
		echo 'No distance specified, data won`t be added';
	}
	else
	{
		$thumb=file("arduino.txt");
		if(!sizeof($thumb))
			die('no data found');
		$val=$thumb[sizeof($thumb)-1];
		 $ln = explode(" ",$val);
		if(!sizeof($val))
			echo '<br>no sensors';
		 mysql_query('INSERT INTO groups (id,distance) VALUES (NULL,"'.$distance.'")');
		$id=mysql_insert_id();
		if(!$id)
			die('No group id!'.mysql_error());
		foreach($ln as $sensor)
		{
			mysql_query("INSERT INTO data(id, group_id,sensor_id) VALUES (NULL,'".$id."','".$sensor."')");
		}
	}
}
?><form action="index.php" method="post">
Add data (from last record):<br>
Distance (cm): <input type="text" name="distance">
<input type="submit" value="add">
<input type="hidden" name="action" value="add">
</form><?
if($_REQUEST['delete'])
{
	$id=(int)$_REQUEST['delete'];
	$sql='DELETE from data where group_id='.$id;
	mysql_query($sql);
	$sql='DELETE from groups where id='.$id;
	mysql_query($sql);
}
$result = mysql_query("SELECT * from groups order by id desc");
$a=true;
while($row=mysql_fetch_array($result))
{
	if($a)
		$color='grey';
	else
		$color="green";
	echo '<div style="background-color: '.$color.'; padding:20px;">';
	echo 'Distance: '.$row['distance'].' cm<br><img src="plots.php?group='.$row['id'].'"><br>';
	echo '<a href="index.php?delete='.$row['id'].'">Delete</a>';
	echo '</div>';
	$a=!$a;
}
?>
