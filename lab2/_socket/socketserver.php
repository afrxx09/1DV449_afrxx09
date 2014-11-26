#!/usr/bin/env php
<?php

require_once('websockets.php');
class echoServer extends WebSocketServer {
  //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.
  
  protected function process ($user, $message) {
    $post = json_decode($message, true);
    $action = $post['action'];
    $r = array();

    switch($action){
      case 'get':
        $r = array('error' => false, 'messages' => $this->getLastMessages(0));
        $this->send($user, json_encode($r));
        break;
      case 'post':
        $r = $this->addMessage($post);
        foreach($this->users as $u){
          $this->send($u, json_encode($r));
        }
        break;
    }
  }
  
  protected function connected ($user) {
    // Do nothing: This is just an echo server, there's no need to track the user.
    // However, if we did care about the users, we would probably have a cookie to
    // parse at this step, would be looking them up in permanent storage, etc.
  }
  
  protected function closed ($user) {
    // Do nothing: This is where cleanup would go, in case the user had any sort of
    // open files or other objects associated with them.  This runs after the socket 
    // has been closed, so there is no need to clean up the socket itself here.
  }

  private function addMessage($post){
    $db = null;
  
    try {
      $db = new PDO("sqlite:db.db");
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOEception $e) {
      die("Something went wrong -> " .$e->getMessage());
    }
    
    $message = htmlentities($post['message']);
    $user = 'alltid samma';
    $created = time();
    
    $q = "INSERT INTO messages (message, name, created) VALUES('$message', '$user', '$created')";
    
    try{
      $stm = $db->prepare($q);
      $r = $stm->execute();
      $id = $db->lastInsertId();
      return array('error' => false, 'messages' => array(array('id' => $id, 'name' => $user, 'message' => $message, 'created' => $created)));
    }
    catch(PDOException $e) {}
    return array('error' => true, 'messages' => array());
  }

  private function getLastMessages($lastId){
    $db = null;

    try {
      $db = new PDO("sqlite:db.db");
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOEception $e) {
      die("Del -> " .$e->getMessage());
    }
    
    $q = "SELECT * FROM messages WHERE id > $lastId";
    try {
      $stm = $db->prepare($q);
      $stm->execute();
      return $stm->fetchAll(PDO::FETCH_ASSOC);
    }
    catch(PDOException $e) {}
    return false;
  }
}

$echo = new echoServer("localhost","9000");

try {
  $echo->run();
}
catch (Exception $e) {
  $echo->stdout($e->getMessage());
}
