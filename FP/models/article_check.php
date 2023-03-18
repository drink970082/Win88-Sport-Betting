<?php
require_once ($_SERVER["DOCUMENT_ROOT"]) . "/final/FP/models/article.php"; 
session_start();

$article = new ArticleClass();
if (isset($_POST['writeArticle'])) {
  $query = [
    'user_id' => $_SESSION['user_id'],
    'username' => $_SESSION['username'],
    'title' => htmlspecialchars($_POST['title']),
    'category' => htmlspecialchars($_POST['category']),
    'content' => htmlspecialchars($_POST['content']),
    'img' => $_POST['img']
  ];
  $result = $article->insertArticle($query);
  echo 'result'. $result;
  exit();
}

if (isset($_POST['deleteArticle'])) {
  $result = $article->deleteArticle($_POST['deleteArticle']);
  echo 'result'. $result;  
  exit();
}

if (isset($_POST['writeMessage'])) {
  $query = [
    'article_id' => htmlspecialchars($_POST['articleId']),
    'username' => htmlspecialchars($_POST['username']),
    'content' => htmlspecialchars($_POST['content']),
  ];
  $result = $article->insertMessage($query);
  echo $result;
  exit();
}

if (isset($_POST['deleteMessage'])) {
  $query = [
    'message_id' => htmlspecialchars($_POST['deleteMessage']),
    'username' => htmlspecialchars($_POST['username']),
  ];
  $result = $article->deleteMessage($query);
  echo 'result'. $result;  
  exit();
}