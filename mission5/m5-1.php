<!DOCTYPE html>
<html lang = "ja">
<head>
<meta charset="UTF-8" />
</head>
<body>

   
    
<?php
    
    // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブル作成(名前はtbtest2)
    $sql = "CREATE TABLE IF NOT EXISTS tbtest2"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"//投稿番号
    . "name char(32),"//名前
    . "comment TEXT" //コメント
    //. "entry_date datetime"//日時
    //. "password char(32)"//パスワード
    .");";
    $stmt = $pdo->query($sql);
    
    $name=filter_input(INPUT_POST,'name');
    $comment=filter_input(INPUT_POST,'comment');
    $number=filter_input(INPUT_POST,'number');
    $editnum=filter_input(INPUT_POST,'editnum');
    $henshu=filter_input(INPUT_POST,'henshu');
    
    //INSERT文 で、データ（レコード）を登録
    $name = @$_POST["name"]; //入力フォームから読み取り
    $comment = @$_POST["comment"]; //入力フォームから読み取り
    $editname="";
    $editstr="";
    
    //$passward = @$_POST["passward"]; //入力フォームから読み取り
    //名前、コメントがあるとき
    if(isset($_POST["submit"]) && ($name) || ($comment)){
    $sql = $pdo -> prepare("INSERT INTO tbtest2 (name, comment) VALUES (:name, :comment)");
    //$entry_date = date("Y/m/d H:i:s");
    
    //bindParamの引数名（:name など）はテーブルのカラム名に併せるとミスが少なくなる。最適なものを適宜決める。
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    //$sql -> bindParam(':entry_date', $entry_date, PDO::PARAM_STR);
    //$sql -> bindParam(':passward', $passward, PDO::PARAM_STR);
    $sql -> execute();
    }

    //編集処理
    if(isset($_POST["edit"]) && (!empty($editnum))){
        $sql = 'SELECT * FROM tbtest2';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
             foreach ($results as $row){
                 if ($row['id'] == $editnum){
                    $editname=$row['name'];
                    $editstr=$row['comment'];
                    break; 
                 }
             }
    }
    
     //投稿編集
    if(isset($_POST["submit"]) && (!empty($name) && !empty($comment) && !empty($henshu))){
        $id = $henshu; //変更する投稿番号
        $sql = 'UPDATE tbtest2 SET name=:name,comment=:comment, WHERE id=:id';
        //$entry_date=date("Y/m/d H:i:s");
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        //$stmt->bindParam(':entry_date', $entry_date, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    //削除処理
    if(isset($_POST["delete"]) && (!empty($_POST["number"]))){
        $id = $_POST["number"];
        $sql = 'delete from tbtest2 where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
?>

     <form action = "" method = "POST">
          <!_入力フォーム_>
        <p>名　　前：<input type = "text" name = "name" autocomplete="off" value="<?php echo $editname;?>"></p>
        <p>コメント：<input type = "text" name = "comment" value="<?php echo $editstr;?>"></p>
        <!_パスワード：input type = "passward" name = "passward"_>
        <input type = "submit" value = "送信" name = "submit"></p>
        
          <!_削除フォーム_>
        <p>削除番号：<input type = "text" name = "number"></p>
        <!_パスワード：input type = "passward" name = "deletepass"_>
        <p><input type = "submit" value = "削除" name = "delete"></p>
        
          <!_編集フォーム_>
        <p>編集番号：<input type = "text" name = "editnum"></p>
        <!_パスワード：input type = "passward" name = "editpass"_>
        <input type = "submit" value = "編集" name = "edit">
        
        <p><input type = "hidden" name = "henshu" value = "<?php echo $editnum; ?>"></p>
    </form>
    
<?php
    //SELECT文で、テーブルに登録されたデータを取得し、表示
    $sql = 'SELECT * FROM tbtest2';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        //echo @$row['entry_date']. '<br>'; //パスワードは表示しない
    echo "<hr>";
    }
        
?>
</body>
</html>