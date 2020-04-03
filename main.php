<?php
class SQL{
  private $db;
  private $sql;
  function __construct(){
    $username = "root";
    $password = "";
    $host = "localhost";
    $dbname = "kosan";
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    try{
      $this->db = new PDO("mysql:host={$host};dbname={$dbname};port=3306;charset=utf8", $username, $password, $options);
    }catch(PDOException $ex){
      die("SQL Error: ". $ex);
      exit;
    }
    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  }
  private function cleanup($bind){
  	if(!is_array($bind))
      if(!empty($bind))
  			$bind = array($bind);
  		else
  			$bind = array();
  	return $bind;
  }
  function run($sql, $bind=""){
    $this->sql = trim($sql);
    $this->bind = $this->cleanup($bind);
    try {
      $stmt = $this->db->prepare($this->sql);
      if($stmt->execute($this->bind) !== false){
        if(preg_match("/^(" . implode("|", array("select", "describe", "pragma")) . ") /i", $this->sql))
          return $stmt->fetchAll(PDO::FETCH_ASSOC);
        elseif(preg_match("/^(" . implode("|", array("delete", "insert", "update")) . ") /i", $this->sql))
          if(strpos(strtolower($this->sql), "insert") !== false)
            return $this->db->lastInsertId();
          else
            return $stmt->rowCount();
      }
    }catch(PDOException $ex){
      die("SQL Error: ". $ex);
      return false;
    }
  }
}
?>
