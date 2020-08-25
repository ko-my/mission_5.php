<?php

    //DB接続設定↓
  $dsn='データベース名';
  $user='ユーザー名';
  $password='パスワード';
  $pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
  
  
  

	
	$henum="";
	$hename="";
	$heco="";
	
	
	if(!empty($_POST["submit_he"])){
      $he=$_POST["he"];
      
      //データベースから名前などを取得
      $sql='SELECT * FROM mission_5';
      $stmt=$pdo->query($sql);
      $result=$stmt->fetchAll();
      foreach($result as $row){
          
          //パスワードと一致したときのみ代入する
          if($he==$row['id'] && $_POST["send_Password"]==$row['password']){
              $henum=$row['id'];
              $hename=$row['name'];
              $heco=$row['comment'];
              
              break;
          }
      }
  }elseif(!empty($_POST["name"]) && !empty($_POST["co"]) && !empty($_POST["submit"])){
      $he_post=$_POST["henum"];
      
      //データベースから名前などを取得
      $sql='SELECT * FROM mission_5';
      $stmt=$pdo->query($sql);
      $result=$stmt->fetchAll();
      if(!empty($he_post) && !empty($_POST["send_Password"])){
          
        //foreachとexplodeを使って、パスワード,投稿番号を取得。一致したときのみ作動
        foreach($result as $row){
            
            if($row['id']==$he_post && $row['password']==$_POST["send_Password"]){
            $co=$_POST["co"];
            $get_Name=$_POST["name"];
            
            $id = $row['id']; //変更する投稿番号
	        $name = $get_Name;
	        $comment = $co;
	        $date=date("Y/m/d H:m:s");//変更したい名前、変更したいコメントは自分で決めること
	        $sql = 'UPDATE mission_5 SET name=:name,comment=:comment,date=:date WHERE id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->execute();
           }
            
          
        }
       
       
       }elseif(empty($he_post) && !empty($_POST["send_Password"])){
           
           
           
      $name=$_POST["name"];
      $co=$_POST["co"];
      $date=date("Y/m/d H:m:s");
      $password=$_POST["send_Password"];     
           
           
      $sql=$pdo->prepare("INSERT INTO mission_5 (name,comment,date,password) VALUES (:name,:comment,:date,:password)");
      $sql->bindParam(':name',$name,PDO::PARAM_STR);
      $sql->bindParam(':comment',$comment,PDO::PARAM_STR);
      $sql->bindParam(':date',$date,PDO::PARAM_STR);
      $sql->bindParam(':password',$password,PDO::PARAM_STR);
      
      
     
     
     
     //postで受け取った文字列をテーブル内に記述。投稿番号は自動的に記録される。
     $name=$_POST["name"];
     $comment=$_POST["co"];
     $date=date("Y/m/d H:m:s");
     $password=$_POST["send_Password"];
     $sql->execute();
      
    }
  }
  
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
</head>
<body>



<form method="post" >
    <input name="henum" type="hidden" value=<?php  echo $henum;?>>
    <input type="text" placeholder="名前" name="name" value=<?php  echo $hename;?>  ><br>
    <input type="text" name="co" placeholder="コメント" value=<?php  echo $heco;?>><br>
    <input type="password" name="send_Password" placeholder="パスワード">
    <input type="submit" name="submit">
</form>

<form method="post">
    <input type="number" placeholder="消去番号" name="de"><br>
        <input type="password" name="send_Password" placeholder="パスワード">
    <input value="消去" type="submit" name="delete_Btn">
</form>

<form  method="post">
    <input type="number" placeholder="編集対象番号" name="he" ><br>
    <input type="password" name="send_Password" placeholder="パスワード">
    <input type="submit" value="編集" name="submit_he">
</form>



</body>
</html>



<?php


    //DB接続設定↓
  $dsn='mysql:dbname=tb220309db;host=localhost';
  $user='tb-220309';
  $password='3N3AEBmEyn';
  $pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
  

  
  if(!empty($_POST["delete_Btn"])){
      $sql='SELECT * FROM mission_5';
      $stmt=$pdo->query($sql);
      $result=$stmt->fetchAll();
      foreach($result as $row){
          if($row['id']==$_POST["de"] && $row['password']==$_POST["send_Password"]){
              $id=$row['id'];
              $sql='delete from mission_5 where id=:id';
              $stmt=$pdo->prepare($sql);
              $stmt->bindParam(':id',$id, PDO::PARAM_INT);
              $stmt->execute();
          }
      }
  }
  
  

  
  //記録した内容を表示させている
  $sql='SELECT * FROM mission_5';
  $stmt=$pdo->query($sql);
  $result=$stmt->fetchAll();
  foreach($result as $row){
      //$rowの中にはテーブルのカラム名が入る
      echo $row['id'].',';
      echo $row['name'].',';
      echo $row['comment'].',';
      echo $row['date']."<br>";
      echo "<hr>";
  }
?>