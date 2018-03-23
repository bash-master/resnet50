<?php
date_default_timezone_set('Asia/Seoul');

require_once ('inc.data.inc');

$file_name['file_name']="";
$file_name=$_GET;

#$file_dir = "up/data";

if ( $file_name['file_name'] == '' )
{
	# lastest 5 image
	$file_name="1cfda7d0620ee168877557472668a4fc.jpg";
}
else 
{
	$file_name=$file_name['file_name'];
}

$query = "SELECT file_id, name_orig, name_save, pred_desc FROM upload_file WHERE name_save='$file_name' limit 1; ";
$stmt = mysqli_prepare($db_conn, $query);
$exec = mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$pred_desc = $row['pred_desc'];

if ($pred_desc == null)
{
	#echo "prediction is null";
	$data1 = shell_exec("echo $file_dir/$file_name | python pred_request.py");
	$data2 = explode("\n", explode(" ", $data1));
	$data2 = explode("\n", $data1);
	$data3 = ($data2[0]." ".$data2[1]." ".$data2[2]." ".$data2[3]." ".$data2[4]);

	$query = "UPDATE upload_file SET pred_desc='$data3' where name_save='$file_name'";
	$stmt = mysqli_prepare($db_conn, $query);
	$exec = mysqli_stmt_execute($stmt);
}
else
{
	#echo "prediction is NOT null";
	$data3 = $pred_desc;
}

mysqli_free_result($result); 
mysqli_stmt_close($stmt);
mysqli_close($db_conn);

echo "<html>
	<head>
	<title>Mara Prediction</title>
	<link rel='stylesheet' href='./marasong.css'  type=text/css>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	<!--<META HTTP-EQUIV='refresh' CONTENT='300'>-->
	</head>

	<body>";

echo "<table>";
echo "<tr height=30><td colspan=3 width=30> </td></tr>";
echo "<tr>";
echo "<td colspan=3 align=center><img width=600 src=./$file_dir/$file_name></td>";
echo "</tr>";
echo "<tr height=10><td colspan=3 width=10> </td></tr>";
echo "<tr>";
echo "<td align=center><b>Rank</b></td>";
echo "<td align=center><b>Name</b></td>";
echo "<td align=center><b>Accuracy</b></td>";
echo "</tr>";

# space split
$data4 = preg_split('/[\s,]+/', $data3, -1, PREG_SPLIT_NO_EMPTY);

while(list($key, $val) = each($data4))
{
	#echo $key."=".$val."\n<br>";
	$val2=explode(" ", $val);
	$val2[2] = $val2[2]*100;
	#echo "key:".$key.", val:".$val."<br><br>";
	if ($key<15) 
	{ 
		switch ($key%3)
		{
			case "0" : # Rank
				echo "<tr>";
				if ($key<3) {echo "<td align=center><font color='#9999FF'>".substr($val, 0, -1)."</font></td>";} # for Rank 1
				else {echo "<td align=center><font color='#AEAEAE'>".substr($val, 0, -1)."</font></td>";} 
			break;
			case "1" : # Prediction Name
				if ($key<3) {echo "<td align=left><font color='#9999FF'><a href=https://search.daum.net/search?w=tot&q=".substr($val, 0, -1).">".substr($val, 0, -1)."</a></font></td>";} # for Rank 1
				else {echo "<td align=left><a href=https://search.daum.net/search?w=tot&q=".substr($val, 0, -1)."><font color='#AEAEAE'>".substr($val, 0, -1)."</font></a></td>";} 
			break;
			case "2" : # Prediction Accuracy % 
			$val *= 100;
				if ($key<3) {echo "<td align=center><font color='#9999FF'>".$val."%</font></td>";} # for Rank 1
				else {echo "<td align=center><font color='#AEAEAE'>".$val."%</font></td>";} 
				echo "</tr>"; 
			break;
		}
	}
}


echo "<tr height=20><td colspan=3 width=20> </td></tr>";
echo "<tr><td><a href=index.php><< Index</a></td>";
echo "<td></td>";
echo "<td align=center><a href=upload.php>Upload...</a></td></tr>";
echo "</table>";

?>