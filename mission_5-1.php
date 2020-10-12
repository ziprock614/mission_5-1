<!DOCTYPE html>

<html lang="ja">

<head>

    <meta charset="UTF-8">

    <title mission_5-1></title>

</head>

<body>

<?php

//データベースへの接続設定
$dsn='データベース名';

$user='ユーザー名';

$password='パスワード';

//データベース接続確認
try{

    $pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));

    echo "接続成功\n";

}catch(PDOException $e){

    echo "接続失敗:" .$e->getMessage()."\n";

    exit();

}
//."created_datetime TIMESTAMP DEFAULT (datetime(CURRENT_TIMESTAMP,'localtime')),"
//テーブルの作成
$sql="CREATE TABLE IF NOT EXISTS forum"
."("
."id INT AUTO_INCREMENT PRIMARY KEY,"
."name char(32),"
."comment TEXT,"
."time TEXT,"
."postpass TEXT"
.");";

//SQLの実行
$stmt=$pdo->query($sql);

//テーブルの表示
$sql ='SHOW TABLES';

$result = $pdo -> query($sql);
    
foreach ($result as $row){

    echo $row[0];
        
    echo '<br>';

}
    
    echo "<hr>";

//編集機能
if(!empty($_POST["edit"])){
    
    //編集パスワードの取得
    $editpass=$_POST["編集パスワード"];

    //編集する投稿のid番号を取得
    $editnumber=$_POST["editnum"];

    //すでに書いてある全データを取得
    $sql='SELECT*FROM forum';

    $stmt=$pdo->query($sql);

    $results=$stmt->fetchAll();

    foreach($results as $row){

        if($row['id']==$editnumber){

            $ediname=$row['name'];

            $edicom=$row['comment'];

            $edinum=$row['id'];

        }

    }
    
}


if(!empty($_POST["edinum_tem"])){

    $edinum_tem=$_POST["edinum_tem"];

    $id=$edinum_tem;

    //すでに書いてある全データを取得
    $sql='SELECT*FROM forum';

    $stmt=$pdo->query($sql);

    $results=$stmt->fetchAll();

    foreach($results as $row){

        if($row['id']==$id&&$row['postpass']==$_POST["editpassword"]){

            if(!empty($_POST["名前"])&&!empty($_POST["コメント"])&&!empty($_POST["投稿パスワード"])){

                //名前の受け取り
                $name=$_POST["名前"];

                //コメントの受け取り
                $comment=$_POST["コメント"];
                
                //投稿パスワードの受け取り
                $postpass=$_POST["投稿パスワード"];

                //日付データの受け取り
                $time=date("Y/m/d H:i:s");

                $sql='UPDATE forum SET name=:name,comment=:comment,postpass=:postpass WHERE id=:id';

                $stmt=$pdo->prepare($sql);

                $stmt->bindParam(':name',$name,PDO::PARAM_STR);

                $stmt->bindParam(':comment',$comment,PDO::PARAM_STR);

                $stmt->bindParam(':postpass',$postpass,PDO::PARAM_STR);

                $stmt->bindParam(':id',$id,PDO::PARAM_INT);

                $stmt->execute();

            }
            

        }

    }

}

//名前・コメント・投稿パスワードがからでない時のみプログラムを動かす
else{

    if(!empty($_POST["名前"])&&!empty($_POST["コメント"])&&!empty($_POST["投稿パスワード"])){
    //echo "e";
    //名前の受け取り
    $name=$_POST["名前"];

    //コメントの受け取り
    $comment=$_POST["コメント"];

    //投稿パスワードの受け取り
    $postpass=$_POST["投稿パスワード"];

    //日付データの受け取り
    $time=date("Y/m/d H:i:s");

    //データの追加
    $sql=$pdo->prepare("INSERT INTO forum (name,comment,time,postpass) VALUES(:name,:comment,:time,:postpass)");

    $sql->bindParam(':name',$name,PDO::PARAM_STR);

    $sql->bindParam(':comment',$comment,PDO::PARAM_STR);

    $sql->bindParam(':time',$time,PDO::PARAM_STR);

    $sql->bindParam(':postpass',$postpass,PDO::PARAM_STR);

    //実行
    $sql->execute();
    
}
}


//削除機能
if(!empty($_POST["delete"])){

    //削除する投稿のid番号を取得
    $deletenumber=$_POST["deletenum"];

    $id=$deletenumber;

    //削除パスワードの取得
    $deletepass=$_POST["削除パスワード"];

    //入力した全データを取得
    $sql='SELECT*FROM forum';

    $stmt=$pdo->query($sql);

    $results=$stmt->fetchAll();

    //idとパスワードが一致した時のみ削除
    foreach($results as $row){

        if($row['id']==$id&&$row['postpass']==$deletepass){
        
            $sql='delete from forum where id=:id';
    
            $stmt=$pdo->prepare($sql);
    
            $stmt->bindParam(':id',$id,PDO::PARAM_INT);
    
            $stmt->execute();
    
        }
        
    }
    
    
}



?>

<!--投稿・編集フォーム-->
<form action="mission5-1.php" method="post">
    <input type="text" name="名前" placeholder="名前" value="<?php if(!empty($_POST["edit"])){echo $ediname;}?>">
    <input type="text" name="コメント" placeholder="コメント" value="<?php if(!empty($_POST["edit"])){echo $edicom;}?>">
    <input type="text" name="投稿パスワード" value="">
    <input type="submit" name="submit"><br>
    <input type="hidden" name="edinum_tem" value="<?php if(!empty($_POST["edit"])){echo $edinum;}?>">
    <input type="text" name="editnum" value="">
    <input type="hidden" name="editpassword" value="<?php if(!empty($_POST["edit"])){echo $editpass;}?>">
    <input typw="text" name="編集パスワード" value="">
    <input type="submit" name="edit" value="編集">
</form>

<!--削除フォーム-->
<form action="mission5-1.php" method="post">
    <input type="text" name="deletenum" value="">
    <input type="text" name="削除パスワード" value="">
    <input type="submit" name="delete" value="削除">
</form>

<?php 

    //テーブルの内容を表示
    $sql = 'SELECT * FROM forum';

    $stmt = $pdo->query($sql);
    
    $results = $stmt->fetchAll();
    
	foreach ($results as $row){

        echo $row['id'].',';
        
        echo $row['name'].',';

        echo $row['comment'].',';

        echo $row['time'].',';

        echo $row['postpass'].'<br>';
        
	echo "<hr>";
	}
?>

</body>

</html>

