<!DOCTYPE html>
<html lang="ko">
<head>
<link rel='stylesheet' href='./marasong.css'  type=text/css>
<title>Mara Upload1</title>
<script type="text/javascript">
function formSubmit(f) 
{
	var extArray = new Array('jpg', 'JPG', 'gif', 'GIF', 'png', 'PNG', 'jpeg', 'JPEG');
	var path = document.getElementById("upfile").value;
	if(path == "") 
	{
		alert("파일을 선택해 주세요.");
		return false;
	}
	
	var pos = path.indexOf(".");
	if(pos < 0) 
	{
		alert("확장자가 없네요;; 확장자가 꼭 있어야 합니다.");
		return false;
	}
	
	var ext = path.slice(path.indexOf(".") + 1).toLowerCase();
	var checkExt = false;
	for(var i = 0; i < extArray.length; i++) 
	{
		if(ext == extArray[i]) 
		{
			checkExt = true;
			break;
		}
	}

	if(checkExt == false) 
	{
		alert(ext." : 이 파일은 업로드 할 수 없는 확장자 입니다.");
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
<br>
<form name="uploadForm" id="uploadForm" method="post" action="upload_process.php" enctype="multipart/form-data" onsubmit="return formSubmit(this);">
<div>
<label for="upfile">파일 첨부하기</label>
<br>
1. <input type="file" name="upfile" id="upfile" />
</div>
<br>
2. <input type="submit" value="업로드 하기" />
<br><br>
</form>
<br>
<a href="javascript:history.go(-1);">이전 페이지</a>
</body>
</html>