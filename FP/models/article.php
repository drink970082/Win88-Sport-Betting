<?php
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/models/db_check.php";

class ArticleClass
{
  public function insertArticle($query)
  {
    $result = '';
    $user_id = $query['user_id'];
    $username =  $query['username'];
    $title = $query['title'];
    $category = $query['category'];
    $content = $query['content'];
    $conn = db_check();
    $sql = "INSERT INTO user_article (User_id, Username, Title, Category, Content, Reg_date)
    VALUES ('$user_id', '$username', '$title', '$category','$content','{$_COOKIE['time']}')";
    if (mysqli_query($conn, $sql)) {
      $result = "文章新增成功";
    } else {
      $result = "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
    return $result;
  }

  public function deleteArticle($id)
  {
    $result = '';
    $conn = db_check();
    $sql = "SELECT Img FROM user_article WHERE ID=$id";
    $img_result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($img_result) > 0) {
      $row = mysqli_fetch_assoc($img_result);
      unlink($_SERVER["DOCUMENT_ROOT"]. $row['Img']);  // 刪除server端的圖片
    }
    $article_sql = "DELETE FROM user_article WHERE ID=$id";
    $message_sql = "DELETE FROM user_message WHERE Article_ID=$id";
    if (mysqli_query($conn, $article_sql) && mysqli_query($conn, $message_sql)) {
      $result = "文章刪除成功";
    } else {
      $result = "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
    return $result;
  }

  public function insertMessage($query)
  {
    $result = '';
    $article_id = $query['article_id'];
    $username =  $query['username'];
    $content = $query['content'];
    $conn = db_check();
    $sql = "INSERT INTO user_message (Article_ID, Username, Content,Reg_date)
    VALUES ('$article_id', '$username', '$content','{$_COOKIE['time']}')";
    if (mysqli_query($conn, $sql)) {
      $last_id = mysqli_insert_id($conn);
      $result = $last_id;
    } else {
      $result = "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
    return $result;
  }

  public function deleteMessage($query)
  {
    $result = '';
    $sql = '';
    $id = $query['message_id'];
    $username = $query['username'];
    $conn = db_check();
    $sql = "DELETE FROM user_message WHERE ID='$id' AND Username='$username'";
    if (mysqli_query($conn, $sql)) {
      $result = "留言刪除成功";
    } else {
      $result = "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
    return $result;
  }
}
?>