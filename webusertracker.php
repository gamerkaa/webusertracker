<?php

define('DB_HOST', "localhost");
define('DB_NAME', "wut_db");
define('DB_USER', "wutuser");
define('DB_PASS', "WUTuser1234");

class Model{
  protected $dbh;
  protected $stmt;

  public function __construct() {
    $this->dbh = new PDO('mysql:host='. DB_HOST . ';dbname='. DB_NAME,DB_USER,DB_PASS);
  }

  public function query($query) {
      $this->stmt = $this->dbh->prepare($query);
  }

  public function bind($param, $value, $type = null) {
      if (is_null($type)) {
          switch(true) {
              case is_int($value):
                  $type = PDO::PARAM_INT;
              break;
              case is_bool($value):
                  $type = PDO::PARAM_BOOL;
              break;
              case is_null($value):
                  $type = PDO::PARAM_NULL;
              break;
              default:
                  $type = PDO::PARAM_STR;
          }
      }
      $this->stmt->bindValue($param, $value, $type);
  }

  public function execute() {
      $this->stmt->execute();
  }

  public function resultSet() {
      $this->execute();
      return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function lastInsertId() {
      return $this->dbh->lastInsertId();
  }

  public function single() {
      $this->execute();
      return $this->stmt->fetch(PDO::FETCH_ASSOC);
  }
}

$db = new Model();

$target = $_POST['target'];
$method = $_POST['method'];
if ($target == 'trackerevent') {
  if ($method == 'add') {
    $eventid = $_POST['eventid'];
    $puser = $_POST['user'];
    $passwd = $_POST['pass'];
    $evtname = $_POST['ename'];
    $evttype = $_POST['etype'];
    $evmin = $_POST['emin'];
    $evmax = $_POST['emax'];
    $evdefault = $_POST['edefault'];
    $db->query("INSERT INTO tracker_event(eventid,puser,passwd,evtname,evttype,evtmin,evtmax,evtdefault)VALUES(:eventid,:puser,:passwd,:ename,:etype,:emin,:emax,:edef)");
    $db->bind(':eventid', $eventid);
    $db->bind(':puser', $puser);
    $db->bind(':passwd', $passwd);
    $db->bind(':ename', $evtname);
    $db->bind(':etype', $evttype);
    $db->bind(':emin', $evmin);
    $db->bind(':emax', $evmax);
    $db->bind(':edef', $evdefault);
    if ($db->single() && !$db->lastInsertId()) http_response_code(422);
    else header('Content-type: text/plain');
  } else if ($method == 'list') {
    $db->query("SELECT * FROM tracker_event");
    $rows = $db->resultSet();
    $output = NULL;
    header('Content-type: text/plain');
    if ($rows) {
      foreach ($rows as $row) {
        $output = $row['eventid'] . ',' . $row['puser'] . ',' . $row['passwd'] . ',' . $row['evtname'] . ',' . $row['evttype'] . ',' . $row['evtmin'] . ',' . $row['evtmax'] . ',' . $row['evtdefault'] . "\r\n";
        echo $output;
      }
    }
  }
} else if ($target == 'tracker') {

  /* Need to migrate save/load of trackerdata to database */

  if ($method == 'add') { 
    $eventid = $_POST['eventid'];
    $puser = $_POST['puser'];
    $pname = $_POST['pname'];
    $pvalue = $_POST['pvalue'];
    $ts = time();
    $tssec = $ts; // Previous impl got value from client
    $tsday = intval($tssec/86400);
    $db->query("INSERT INTO tracker_eventdata(eventid,puser,pname,pvalue,ts)VALUES(:eventid,:puser,:pname,:pval,:ts)");
    $db->bind(':eventid', $eventid);
    $db->bind(':puser', $puser);
    $db->bind(':pname', $pname);
    $db->bind(':pval', $pvalue);
    $db->bind(':ts', $tsday);
    if ($db->single() && !$db->lastInsertId()) http_response_code(422);
    else header('Content-type: text/plain');
  } else if ($method == 'list') {
    header('Content-type: text/plain');
    $dayindex = $_POST['dayindex'];
    $db->query("SELECT * FROM tracker_eventdata WHERE ts=:ts");
    $db->bind(':ts', $dayindex);
    $rows = $db->resultSet();
    $output = NULL;
    header('Content-type: text/plain');
    if ($rows) {
      foreach ($rows as $row) {
        $output = $row['eventid'] . ',' . $row['puser'] . ',' . $row['pname'] . ',' . $row['pvalue'] . ',' . $row['ts'] . "\r\n";
        echo $output;
      }
    }
 }
}