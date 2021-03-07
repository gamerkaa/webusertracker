<?php

require_once('./config.php');
require_once('classes/Model.php');
require_once('classes/WutModel.php');

$db = new WutModel();

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