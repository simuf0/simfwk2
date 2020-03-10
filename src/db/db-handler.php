<?php

namespace SimFwk2\Db;

/**
 * Database access handler
 * 
 * @author Simon Cabos
 * @version 1.0.1
 * @copyright 2020 Simon Cabos
 * @licence GPL - http://www.gnu.org/licenses/gpl-3.0.html
 */
final class DbHandler {

  use \SimFwk2\Factory\Singleton;

  /** @var string The data source name. */
  const DSN = "mysql:host=%s;dbname=%s";

  /** @var \PDO The php data object instance. */
  private $pdo;

  /** @var \PDOStatement The pdo statement. */
  private $query;

  /**
   * @param string $dbhost The database host.
   * @param string $dbname The database name.
   * @param string $dbuser The database username.
   * @param string $dbpwd The database password.
   */
  private function __construct (
    string $dbhost,
    string $dbname,
    string $dbuser,
    string $dbpwd
  ) {
    $this->connect($dbhost, $dbname, $dbuser, $dbpwd);
  }

  /**
   * Create a database connection.
   * @param string $dbhost The database host.
   * @param string $dbname The database name.
   * @param string $dbuser The database username.
   * @param string $dbpwd The database password.
   */
  public function connect (
    string $dbhost,
    string $dbname,
    string $dbuser,
    string $dbpwd
  ): void {
    $dsn = sprintf(self::DSN, $dbhost, $dbname);
    $options = [
      \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
      \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
      \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    ];
    try {
      $this->pdo = new \PDO($dsn, $dbuser, $dbpwd, $options);
    } catch (\PDOException $e) {
      exit($e->getMessage());
    }
  }

  /**
   * Create a pdo statement with the given query.
   * @param string $query The query to prepare.
   * @param mixed[] $replacements (optional) The replacements array.
   */
  public function query (string $query, array $replacements = []): void {
    foreach ($replacements as $key => $value) {
      $query = str_ireplace("@$key", $value, $query);
    }
    $this->query = $this->pdo->prepare($query);
  }

  /**
   * Bind a value to the query.
   * @param string $param The identifier of the parameter to bind.
   * @param mixed $value The value to bind.
   */
  public function bind (string $param, $value): void {
    switch(gettype($value)) {
      case "integer" :
        $type =\PDO::PARAM_INT;
        break;
      case "boolean" :
        $type =\PDO::PARAM_BOOL;
        break;
      default :
        $type =\PDO::PARAM_STR;
        break;
    }
    $this->query->bindValue(":$param", $value, $type);
  }

  /**
   * Bind multiple values to the query.
   * @param mixed[] $params The parameters array.
   */
  public function bindArray (array $params): void {
    foreach ($params as $key => $value)
      $this->bind($key, $value);
  }

  /**
   * Close the statement cursor.
   */
  private function close (): void {
    $this->query->closeCursor();
    $this->query = null;
  }

  /**
   * Execute a prepared query.
   * @param boolean $select (optional) Returns the query's result if TRUE.
   * @param boolean $lastIndex (optional) Returns the last insert id if TRUE.
   * @return mixed[]|int|boolean Returns the query's result or the last insert id.
   */
  public function execute (bool $select = false, bool $lastIndex = false) {
    try {
      $out = $this->query->execute();
      if($select === true) {
        $out = $this->query->fetchAll();
      } elseif ($lastIndex) {
        $out = (int) $this->pdo->lastInsertId();
      }
      $this->close();
      return $out;
    } catch (\PDOException $e) {
      $message = $e->getMessage() . "\n";
      $message .= "QUERYSTRING: {$this->query->queryString}";
      exit($message);
    }
  }
}