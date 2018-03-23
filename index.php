
<!DOCTYPE html>
<html lang="ko">
<head>
<link rel='stylesheet' href='./marasong.css'  type=text/css>
<title>Mara Prediction</title>
<script type="text/javascript">
function formSubmit(f) {
	var extArray = new Array('jpg', 'JPG', 'gif', 'GIF', 'png', 'PNG', 'jpeg', 'JPEG');
	var path = document.getElementById("upfile").value;
	if(path == "") {
		alert("파일을 선택해 주세요.");
		return false;
	}
	
	var pos = path.indexOf(".");
	if(pos < 0) {
		alert("확장자가 없는 파일 입니다.");
		return false;
	}
	
	var ext = path.slice(path.indexOf(".") + 1).toLowerCase();
	var checkExt = false;
	for(var i = 0; i < extArray.length; i++) {
		if(ext == extArray[i]) {
			checkExt = true;
			break;
		}
	}

	if(checkExt == false) {
		alert(ext."업로드 할 수 없는 파일 확장자 입니다.");
	    return false;
	}
	
	return true;
}
</script>
<style>
input[type="submit"] {background: #6666FF;}
</style>
</head>

<body>

<table>

<tr>
<td colspan=3> 
	<form name="uploadForm" id="uploadForm" method="post" action="upload_process.php" enctype="multipart/form-data" onsubmit="return formSubmit(this);">
	<div>
	<br><br>
	<label for="upfile">파일 첨부하기</label>
	<br><br>
	1. <input type="file" name="upfile" id="upfile" />
	</div>
	<br>
	2. <input type="submit" value="업로드 하기" />
	<br><br>
	</form>
</td>
</tr>


<?php
$p['p']="";
$p=$_GET;
if ( $p['p'] == '' or $p['p']<0 )
{
	$p="0";
}
else 
{
	$p=$p['p'];
}
$start_num = $p*5;
$page_before = $p-1;
$page_next   = $p+1;

require_once ('inc.data.inc');

$query = "SELECT file_id, name_orig, name_save, pred_desc FROM upload_file WHERE pred_desc is not null ORDER BY reg_time DESC limit ".$start_num.",5;";
$stmt = mysqli_prepare($db_conn, $query);
$exec = mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while($row = mysqli_fetch_assoc($result)) 
{
?>
<tr>
	<td colspan=3 align=center><img width=600 src=<?=$file_dir?>/<?=$row['name_save']?>></td>
</tr>
<tr>
	<td align=center><b>Rank</b></td>
	<td align=center><b>Name</b></td>
	<td align=center><b>Accuracy</b></td>
</tr>

<?php
//print_r($data3);
$data3 = $row['pred_desc'];
$data4 = preg_split('/[\s,]+/', $data3, -1, PREG_SPLIT_NO_EMPTY);

while(list($key, $val) = each($data4))
{
	//echo $key."=".$val."\n<br>";
	$val2=explode(" ", $val);
	$val2[2] = $val2[2]*100;
	//echo "key:".$key.", val:".$val."<br><br>";
	if ($key<3) { $td_color="#9999FF"; } # for Rank 1
	else        { $td_color="#AEAEAE"; }

	if ($key<15) 
	{ 
		switch ($key%3)
		{
			case "0" : // Rank NO
				echo "<tr>";
				echo "<td align=center><font color='".$td_color."'>".substr($val, 0, -1)."</font></td>";
			break;
			case "1" : // Prediction Name
				echo "<td align=left><a href=https://search.daum.net/search?w=tot&q=".substr($val, 0, -1)."><font color='".$td_color."'>".substr($val, 0, -1)."</font></a></td>"; 
			break;
			case "2" : // Prediction Accuracy % 
				$val *= 100;
				echo "<td align=center><font color='".$td_color."'>".$val."%</font></td>"; 
				echo "</tr>"; 
			break;
		}
	}
} // while list
echo "<tr height=10><td colspan=3 width=10> </td></tr>";

} // while row

echo "<tr height=20><td colspan=3 width=20> </td></tr>";
if ( $p==0 )
{
	echo "<tr><td>First Page</td>";	
}
else
{
	echo "<tr><td><a href=index.php?p=".$page_before."><< Before</a></td>";	
}

echo "<td></td>";
echo "<td><a href=index.php?p=".$page_next.">Next >></a></td></tr>";
echo "</table>";
mysqli_free_result($result); 
mysqli_stmt_close($stmt);
mysqli_close($db_conn);

?>
