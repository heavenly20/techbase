<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5テスト</title>
</head>
<body>
    <body>

<?php
    //データベースに接続
    $dsn = 'データベース名'; 
    $user = 'ユーザー名'; 
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    // テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS mission5"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "entry_date datetime"
    .");";
    $stmt = $pdo->query($sql);
    
    $name=filter_input(INPUT_POST,'name');
    $comment=filter_input(INPUT_POST,'comment');
    $delnum=filter_input(INPUT_POST,'delnum');
    $editnum=filter_input(INPUT_POST,'editnum');
    $henshunum=filter_input(INPUT_POST,'henshunum');
    
    
    $editname="";
    $editstr="";
    
    //編集行取り出し//
    if(isset($_POST["edit"]) && (!empty($editnum))){
        $sql = 'SELECT * FROM mission5';
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
    if(isset($_POST["sub"]) && (!empty($name) && !empty($comment) && !empty($henshunum))){
        $id = $henshunum; //変更する投稿番号
        $sql = 'UPDATE mission5 SET name=:name,comment=:comment,entry_date=:entry_date WHERE id=:id';
        $entry_date=date("Y/m/d H:i:s");
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':entry_date', $entry_date, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
        
        
    // 新規フォームの書き込み
    if(isset($_POST["sub"]) && (!empty($name && $comment)) && (empty($henshunum))){
        $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, entry_date) VALUES (:name, :comment, :entry_date)");
        $entry_date=date("Y/m/d H:i:s");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':entry_date', $entry_date, PDO::PARAM_STR);
        $sql -> execute();
    }
    
    // 削除フォーム
    if(isset($_POST["delete"]) && (!empty($delnum))){
    $id = $delnum;
    $sql = 'delete from mission5 where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    }
    
    
?>   

    <form action="" method="post">
        <p><!_送信フォーム_></p>
        名　　前：<input type="text" name="name" autocomplete="off" value="<?php echo $editname;?>"><br>
        コメント：<input type="text" name="comment"  autocomplete="off" value="<?php echo $editstr;?>"><br>
        <input type="submit" name="sub"><br>
        <p><!_削除フォーム_></p>
        削除番号：<input type="text" name="delnum" ><br>
        <input type="submit" name="delete" value="削除">
        <p><!_編集フォーム_></p>
        編集番号：<input type="text" name="editnum" ><br>
        <input type="submit" name="edit" value="編集"><br>
        
        <input type="hidden" name="henshunum" value="<?php 
        echo $editnum;?>"><br>
    </form>
    
    
     <?php
        //表示
        #テーブルの中身を表示#
            $sql = 'SELECT * FROM mission5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['entry_date'];
                echo "<hr>";
            }
        ?>
</body>
</html>