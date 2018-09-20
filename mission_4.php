<?php
header("Content-Type: text/html; charset=UTF-8");
//文字化け対策;
?>
		<!DOCTYPE html>
		<html lang = "ja">
		<head>
		<meta charset="UTF-8">
		</head>
		<body>
<?php
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
//データベースに接続する;
$pdo = new PDO($dsn,$user,$password);
//テーブルを作成する;
$sql= "CREATE TABLE mission4"
."("
."id INT AUTO_INCREMENT PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."password char(32),"
."date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP"
.");";
$stmt = $pdo->query($sql);
		//新規投稿、insert into テーブル名、()内にカラム名、valuesに入れる値;
		//名前、コメント、パスワードは値があり、隠し項目は空という条件;
		if(!empty($_POST["comment"]) and !empty($_POST["name"]) and !empty($_POST["password"]) and empty($_POST["edit_num"])){
		//パスワードの条件;
		if(preg_match("/^[a-zA-Z0-9]+$/",$password)){
		$sql = $pdo -> prepare("INSERT INTO mission4(name,comment,password) VALUES(:name,:comment,:password)");
		$sql-> bindParam(':name',$name,PDO::PARAM_STR);
		$sql-> bindParam(':comment',$comment,PDO::PARAM_STR);
		$sql-> bindParam(':password',$password,PDO::PARAM_STR);
		$name = $_POST["name"];
		$comment = $_POST["comment"];
		$password = $_POST["password"];
		$sql-> execute();
		}else{
		echo"パスワードは半角英数字で入力してください";
		}
		}
	//編集対象の投稿を選択;
	$edit = $_POST["edit"];
	$password_edit = $_POST["password_edit"];
	$sql = 'SELECT * FROM mission4';
	$results = $pdo -> query($sql);
	foreach ($results as $row){
	if(!empty($_POST["edit"]) and $row['id'] == $edit and $row['password'] == $password_edit){
	$edit_name = $row['name'];
	$edit_comment = $row['comment'];
	}
	}
//編集を実行;
if(!empty($_POST["edit_num"]) and !empty($_POST["comment"]) and !empty($_POST["name"]) and !empty($_POST["password"])){
$id = $_POST["edit_num"];
$name = $_POST["name"];
$comment = $_POST["comment"];
$password =$_POST["password"];
$date = date("Y/m/d H:i:s");
$sql = "update mission4 set name='$name', comment='$comment', password='$password', date='$date' where id=$id";
$result = $pdo->query($sql);
}
?>
		<form action = "mission_4.php" method = "POST">
		<!-フォームと送信ボタン->
		名前
		<br/>
		<input type = "text" name = "name" value = "<?php
								if(!empty($_POST["edit"])){
								echo $edit_name;
								 }
								?>"
								>
		<br/>
		<br/>
		コメント
		<br/>
		<input type = "text" name = "comment" value = "<?php
								if(!empty($_POST["edit"])){
								echo $edit_comment;
								}
								?>"
								>
		<br/>
		<br/>
		パスワード
		<br/>
		<input type = "password" name = "password">
		<br/>
		<br/>
		<input type = "submit" value = "送信する">
		<br/>
		<br/>
		コメントを編集する
		<br/>
		<input type = "number" name = "edit">
		<br/>
		パスワード
		<br/>
		<input type = "password" name = "password_edit">
		<input type = "submit" value = "編集する">
		<br/>
		<br/>
		<input type = "hidden" name = "edit_num" value = "<?php
								if(!empty($_POST["edit"]) and !empty($_POST["password_edit"])){
								echo $_POST["edit"];
								}
								?>"
								>
		コメントを削除する
		<br/>
		<input type = "number" name = "delete">
		<br/>
		パスワード
		<br/>
		<input type = "password" name = "password_delete">
		<input type = "submit" value = "削除する">
		</form>
		<!-フォームここまで->
	<?php
	//deleteで削除する;
	$delete = $_POST["delete"];
	$password_delete = $_POST["password_delete"];
	//deleteが値ありという条件;
	if(!empty($_POST["delete"]) and !empty($_POST["password_delete"])){
	$sql = "delete from mission4 where id=$delete and password=$password_delete";
	$results = $pdo -> query($sql);
	}
//ブラウザ表示;
$sql = 'SELECT * FROM mission4 ORDER BY id asc';
$results = $pdo -> query($sql);
foreach ($results as $row){
//$rowの中にはテーブルのカラム名が入る;
echo $row['id'].',';
echo $row['name'].',';
echo $row['comment'].',';
echo $row['date'].'<br>';
}
?>
		</body>
		</html>