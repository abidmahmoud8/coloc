<?php

class Database
{
	private $pdo;
  // Nom de la hote
	private $host;
  // Nom de la base de donné
	private $dbname;
  // Nom d'utilisteur
	private $user;
  // Mot de passe 
  private $password;

	public function __construct()
	{
    $this->host = 'localhost';
    $this->dbname = 'coloc';
    $this->user = 'root';
    $this->password = '';
		$this->pdo = new PDO('mysql:host='.$this->host.';
      dbname='.$this->dbname.';
      charset=utf8', 
      $this->user, 
      $this->password,
      [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
		$this->pdo->exec('SET NAMES UTF8');
	}

	public function executeSql($sql, $values) {
		$query = $this->pdo->prepare($sql);
		$query->execute($values);
		return $this->pdo->lastInsertId();
	}

  public function query($sql, array $criteria = array()){
    $query = $this->pdo->prepare($sql);
    $query->execute($criteria);
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  public function queryOne($sql, array $criteria = array()){
    $query = $this->pdo->prepare($sql);
    $query->execute($criteria);
    return $query->fetch(PDO::FETCH_ASSOC);
  }
}