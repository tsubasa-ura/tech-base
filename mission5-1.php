<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8;">
    <title>mission5-1-2.php</title>
</head>
<body>
<?php
    $edit_name="";
    $edit_comment="";
    $edit_num="";

    //DB接続設定
    $dsn='データベース名';
    $user='ユーザー名';
    $password='パスワード';
    $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


    //CREATE文：テーブル作成
    //SOL文
    $sql="CREATE TABLE IF NOT EXISTS mission51"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."comment TEXT,"
    ."date TEXT,"
    ."password1 TEXT"
    .");";
    $stmt = $pdo->query($sql);
    

    //入力フォーム
    if(empty($_POST["edit_do"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password1"])){
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $date=date("Y年m月d日 H時i分s秒");
        $pass=$_POST["password1"];
    
        //insert文
        $sql = $pdo -> prepare("INSERT INTO mission51(name, comment, date, password1) VALUES(:name, :comment, :date, :password1)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password1', $pass, PDO::PARAM_STR);
        $sql -> execute();

        
    }
    
    
    //削除フォーム
    if(!empty($_POST["num_delete"]) && !empty($_POST["password2"])){
        $delete=$_POST["num_delete"];
        $del_pass=$_POST["password2"];

        //select文
        $sql = 'SELECT * FROM mission51';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            $row['id'].' ';
            $row['name'].' ';
            $row['comment'].' ';
            $row['date'].' ';
            $row['password1']."<br>";
            
            
            //データベースから取り出したidとpassを，フォームの値と比較
            if($row['id'] != $delete && $row['password1'] != $del_pass){
                $row['id'].' ';
                $row['name'].' ';
                $row['comment'].' ';
                $row['date']. "<br>";
                "<hr>";
        
            }elseif($row['id'] == $delete && $row['password1'] != $del_pass){
                $row['id'].' ';
                $row['name'].' ';
                $row['comment'].' ';
                $row['date']. "<br>";
                "<hr>";
           
            }elseif($row['id'] != $delete && $row['password1'] == $del_pass){
                $row['id'].' ';
                $row['name'].' ';
                $row['comment'].' ';
                $row['date']. "<br>";
                "<hr>";
            }
                
            if($row['id'] == $delete && $row['password1'] == $del_pass){
            //DELETE文：データレコードを削除
            $id = $delete;
            $sql = 'delete from mission51 where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            }
        }
    }
        
    
    //編集フォーム
    //送信された番号に合う書き込みの内容をフォームに表示する編集選択機能
    if(!empty($_POST["num_edit"]) && !empty($_POST["password3"])){
        $edit_num=$_POST["num_edit"];
        $edit_pass=$_POST["password3"];    
    
        //SELECT文：データレコードを取得
        $sql = 'SELECT * FROM mission51';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            $row['id'].' ';
            $row['name'].' ';
            $row['comment'].' ';
            $row['date'].' ';
            $row['password1']."<br>";

            //投稿番号と編集対象番号を比較
            //パスワードが一致した時のみ入力フォームに投稿内容を表示
            if($row['id'] == $edit_num && $row['password1'] == $edit_pass){
                $edit_num=$row['id'];
                $edit_name=$row['name'];
                $edit_comment=$row['comment'];
                $edit_pass=$row['password1'];
            }
        }
    }
                //上書きする編集実行機能        
                if(!empty($_POST["edit_do"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password1"])){
                    $id=$_POST["edit_do"];
                    $name=($_POST["name"]);
                    $comment=($_POST["comment"]);
                    $date=date("Y年m月d日 H時i分s秒");
                    $pass=($_POST["password1"]);
                    
                        //UPDATE文：データレコードの編集
                        $sql = 'UPDATE mission51 SET name=:name,comment=:comment,date=:date WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                        $stmt->execute();
                        
                        //SELECT文で取得
                        $sql = 'SELECT * FROM mission51';
                        $stmt = $pdo->query($sql);
                        $results = $stmt->fetchAll();
                        foreach($results as $row){
                            //$rowの中にはテーブルのカラム名が入る
                            $row['id'].' ';
                            $row['name'].' ';
                            $row['comment'].' ';
                            $row['date']. "<br>";
                            "<hr>";
                        }
                }

?>
<!--投稿-->
<form action="" method="post">
    夏休み、遊びの予定などありますか？？<br>
    <label>入力フォーム<br></label>
    <input type="text" name="name" placeholder="名前"  value="<?php echo $edit_name; ?>"><br>
    <input type="text" name="comment" placeholder="コメント"  value="<?php echo $edit_comment; ?>"><br>
    <input type="hidden" name="edit_do"  value="<?php echo $edit_num; ?>">
    <input type="text" name="password1" placeholder="パスワード" >
    <input type="submit" name="submit" value="送信"><br><br>
    <label>削除フォーム<br></label>
    <input type="num" name="num_delete" placeholder="削除対象番号" ><br>
    <input type="text" name="password2" placeholder="パスワード" >
    <input type="submit" name="delete" value="削除"><br><br>
    <label>編集フォーム<br></label>
    <input type="num" name="num_edit" placeholder="編集対象番号" ><br>
    <input type="text" name="password3" placeholder="パスワード" >
    <input type="submit" name="edit" value="編集"><br><br>
</form>
<?php
//select文
        $sql = 'SELECT * FROM mission51';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].' ';
            echo $row['name'].' ';
            echo $row['comment'].' ';
            echo $row['date']. "<br>";
            echo "<hr>";
        }
?>
</body>
</html>