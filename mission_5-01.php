<!DOCTYPE html>
  <html lang="ja">
    <head>
     <meta charset="UTF-8">
     <title>misssion_5-01</title>
    </head> 
    <body>
<?php 
  //データベース接続設定
  $dsn='データベース名';
  $user='ユーザー名';
  $password='パスワード';
  $pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
 
 
  //テーブル作成
 $sql="CREATE TABLE IF NOT EXISTS tbtest"
 ."("
 ."id INT AUTO_INCREMENT PRIMARY KEY,"
//idの型は"INT(整数型、連番、個数など)"  
//属性は"AUTO INCREMENT_PRIMARY(整数の型に付与できる属性で、自動採取を表す）"
//      "PRIMARY KEY(主キーであることを表す）"
."name char(32),"
//"char"は固定長文字列型。桁数が決まっているもの。1から255文字までであらかじめ桁数を決める。
//()で文字列長を指定する。
."comment TEXT,"
//"TEXT"は文字列型。本文などの長い文章
."pass TEXT,"
."date TEXT"
.");";
$stmt=$pdo->query($sql);
//↑データベース受信

 //変数
 $name=$_POST["name"];
 $comment=$_POST["comment"];
 $pass1=$_POST["pass1"];
 $pass2=$_POST["pass2"];
 $pass3=$_POST["pass3"];
 $date=date("Y/m/d H:i:s");
 $editnum=$_POST["editnum"];
 $deletenum=$_POST["deletenum"];
 $editname=$_POST["editname"];
 $editcomment=$_POST["editcomment"];
 
 
//編集番号
 if(!empty($_POST["editnum"]) && !empty($_POST["pass3"] )){
     $sql='SELECT * FROM tbtest';
  //WHERE …（条件）…
  $stmt=$pdo->query($sql);
  $results=$stmt->fetchALL();
  //"fetchallメソッド→結果データを全件まとめて"配列で取得する
     foreach ($results as $row){
         if($row['id']==$editnum && $row['pass']==$pass3){
             $editname=$row['name'];
             $editcomment=$row['comment'];
            
         }
      }
    }
    ?>
    
     <form action="" method="post">
            <input type="text" name="name" placeholder="名前" 
            value="<?php if(isset($editname)){echo($editname);}?>"><br>
            <input type="text" name="comment" placeholder="コメント"
            value="<?php if(isset($editcomment)){echo($editcomment);}?>"><br>
            <input type="hidden" name="judge" value="<?php if(isset($editnum)){echo($editnum);}?>">
            <input type="text" name="pass1" placeholder="パスワード">
            <input type="submit" name="submit">
        </form>
        <br>
        <form action="" method="post">
            <input type="text" name="deletenum" placeholder="削除対象番号"><br>
            <input type="text" name="pass2" placeholder="パスワード">
            <input type="submit" name="submit">
            <br>※削除対象番号は半角数字です<br>
            
         </form>
            <br>
        <form action="" method="post">
            <input type="text" name="editnum" placeholder="編集対象番号"><br>
            <input type="text" name="pass3" placeholder="パスワード">
            <input type="submit" name="submit">
            <br>※編集対象番号は半角数字です<br>
        </form><br>
  <?php 
  //新規投稿
  if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass1"]) && empty($_POST["judge"])){
      $sql=$pdo->prepare("INSERT INTO tbtest (name,comment,pass,date) VALUES (:name, :comment, :pass, :date)");
  //プリペアドステートメント→実行したいクエリのテンプレートのようなもの。
  //[:名前]の部分に、bihindParamメソッドで後から値を埋め込む。
  //後から埋め込む[:~]の部分のことをプレースホルダーと呼ぶ。
  //列をカラム(Column),行をレコード(Record)と呼ぶ
  //"INSERT INTO テーブル名 (カラム1, カラム2, カラム3・・・) VALUES(値1,値2,値3・・・)
	$sql->bindParam(':name', $name, PDO::PARAM_STR);
	$sql->bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql->bindParam(':pass', $pass1, PDO::PARAM_STR);
	$sql->bindParam(':date', $date, PDO::PARAM_STR);
	//bihindParamメソッド→テンプレートのプレースホルダに変数を埋め込む。
	$sql->execute();
  }
  //編集
   if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass1"]) && !empty($_POST["judge"])){
      $name=$_POST["name"];
      $comment=$_POST["comment"];
      $date=date("Y/m/d H:i:s");
  $id=$_POST["judge"];  //変更する投稿番号
  
  $sql='UPDATE tbtest SET name=:name,comment=:comment,pass=:pass1,date=:date WHERE id=:id';
  $stmt=$pdo->prepare($sql);
  $stmt->bindParam(':name',$name,PDO::PARAM_STR);
  $stmt->bindParam('comment',$comment,PDO::PARAM_STR);
  $stmt->bindParam(':pass1', $pass1, PDO::PARAM_STR);
  $stmt->bindParam(':date', $date, PDO::PARAM_STR);
  $stmt->bindParam(':id',$id,PDO::PARAM_INT);
  $stmt->execute();
   
   }
  //削除
  if(!empty($_POST["deletenum"]) && !empty($_POST["pass2"])){
     
      
  $id = $_POST["deletenum"];
	$sql = 'delete from tbtest where id=:id && pass=:pass2';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id',$id, PDO::PARAM_INT);
	$stmt->bindParam(':pass2',$pass2, PDO::PARAM_STR);
	$stmt->execute();
  }
  
  
  
  
  
 //表示
   $sql='SELECT * FROM tbtest';
 //WHERE …（条件）…
  $stmt=$pdo->query($sql);
  $results=$stmt->fetchALL();
  //"fetchallメソッド→結果データを全件まとめて"配列で取得する
     foreach ($results as $row){
         echo $row['id'].',';
         echo $row['name'].',';
         echo $row['comment'].',';
         echo $row['date'].'<br>';
        echo "<hr>"; 
     }
     
	?>
	</body>
	</html>