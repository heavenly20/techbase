<?php

    // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //SHOW TABLES：データベースのテーブル一覧を表示
    //データベースに現在、どのようなテーブルが作成されているかを確認
    //4-1で書いた「// DB接続設定」のコードの下に続けて記載する。
    $sql ='SHOW TABLES';
    $result = $pdo -> query($sql);
    foreach ($result as $row){
        echo $row[0];
        echo '<br>';
    }
    echo "<hr>";
    
    //SHOW CREATE TABLE文：作成したテーブルの内容・構成詳細を確認する
    //SHOW CREATE TABLE 文を使うと、そのテーブルを作成するためのSQL（ CREATE文 ）が確認できる
    //4-1で書いた「// DB接続設定」のコードの下に続けて記載する
    $sql ='SHOW CREATE TABLE tbtest2';
    $result = $pdo -> query($sql);
    foreach ($result as $row){
        echo $row[1];
    }
    echo "<hr>";
?>