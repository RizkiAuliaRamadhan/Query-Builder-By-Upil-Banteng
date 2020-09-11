<?php
/**
 * @Package     Clickane - Ad Network
 * @Version     v1.0.0
 * @Author      https://github.com/UpilBanteng
 * @Created on  27/08/2020
 */
ini_set('display_errors', 0);
session_start();

try {
  class CA_Database {
    private $MySQL_Host;
    private $MySQL_User;
    private $MySQL_Pass;
    private $MySQL_Name;
    private $MySQL_Port;
    public  $connection;

    public function __construct($host, $user, $pass, $name, $port = 3306) {
      $this->MySQL_Host = $host;
      $this->MySQL_User = $user;
      $this->MySQL_Pass = $pass;
      $this->MySQL_Name = $name;
      $this->MySQL_Port = $port;

      $this->connection = new PDO('mysql:host='.$this->MySQL_Host.';port='.$this->MySQL_Port.';dbname='.$this->MySQL_Name.';charset=utf8mb4', $this->MySQL_User, $this->MySQL_Pass);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

      // if($_SERVER['REQUEST_SCHEME'] != 'https' || $_SERVER['REQUEST_SCHEME'] == 'http') {
      //   echo '<b>Fatal Error</b>: Your connection is not secure.';
      //   exit();
      // }
    }

    public function __destruct() {
      $this->connection = null;
    }

    public function base_url() {
      return ($_SERVER['REQUEST_SCHEME'] == 'https' ? 'https://'.$_SERVER['HTTP_HOST'] : 'http://'.$_SERVER['HTTP_HOST']);
    }

    public function insert($table, $vars, $where) {
      $code = 'INSERT INTO '.$table.' (';
      $Keys = array();
      $Vars = array();

      foreach($vars as $key => $value) {
				$Keys[] = $key;
				$Vars[] = "'$value'";
      }

      if(count($Keys) > 1) {
				$code .= ''.implode(',', $Keys).') VALUES ('.implode(',', $Vars).')';
			} else {
        $code .= ''.$Keys[0].') VALUES ('.$Vars[0].')';
      }

      if(is_array($where)) {
        $Where = array();

				foreach($where as $key => $value) {
          $Where[] = "$key='$value'";
        }

        if(count($Where) > 1) {
					$code .= ' WHERE '.implode(' AND ', $Where).'';
				} else {
          $code .= ' WHERE '.$Where[0].'';
        }
      }

      return $this->connection->query($code);
    }

    public function select($table, $fields, $where, $order = '', $limit = '') {
      if($fields == '') {
				$fields = '*';
      }

      $code = 'SELECT '.$fields.' FROM '.$table.'';
      if(is_array($where)) {
        $Where = array();

        foreach($where as $key => $value) {
          $Where[] = "$key='$value'";
        }

        if(count($Where) > 1) {
          $code .= ' WHERE '.implode(' AND ', $Where).'';
        } else {
          $code .= ' WHERE '.$Where[0].'';
        }

        if($order) {
          $code .= ' ORDER BY `'.$order.'`';
        }

        if($limit) {
          $code .= ' LIMIT '.$limit.'';
        }
      }

      return $this->connection->query($code);
    }

    public function update($table, $set, $where) {
      $code = 'UPDATE '.$table.' SET';
      $Set = array();
      
      foreach($set as $key => $value) {
				$Set[] = "$key='$value'";
      }
      
      if(count($Set) > 1) {
				$code .= ' '.implode(',', $Set).'';
			} else {
        $code .= ' '.$Set[0].'';
      }

      if(is_array($where)) {
        $Where = array();

				foreach($where as $key => $value) {
          $Where[] = "$key='$value'";
        }

        if(count($Where) > 1) {
          $code .= ' WHERE '.implode(' AND ', $Where).'';
        } else {
          $code .= ' WHERE '.$Where[0].'';
        }
      }

      return $this->connection->query($code);
    }

    public function delete($table, $where) {
      $code = 'DELETE FROM '.$table.'';

      if(is_array($where)) {
        $Where = array();

        foreach($where as $key => $value) {
          $Where[] = "$key='$value'";
        }

        if(count($Where) > 1) {
          $code .= ' WHERE '.implode(' AND ', $Where).'';
        } else {
          $code .= ' WHERE '.$Where[0].'';
        }
      }

      return $this->connection->query($code);
    }
  }
  
  $db = new CA_Database('localhost', 'root', 'root', 'clickane');
} catch(PDOException $e) {
  echo '<b>Database Error</b>: '.$e->getMessage().'; (<b>Trace</b>: '.$e->getCode().')';
  exit();
}
?>
