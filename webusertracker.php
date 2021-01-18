<?php

header('Content-type: text/plain');

$target = $_POST['target'];
$method = $_POST['method'];
if ($target == 'trackerevent') {
  if ($method == 'add') {
    $eventid = $_POST['eventid'];
    $puser = $_POST['puser'];
    $passwd = $_POST['passwd'];
    $evtname = $_POST['ename'];
    $evttype = $_POST['etype'];
    $evmin = $_POST['emin'];
    $evmax = $_POST['emax'];
    $evdefault = $_POST['edefault'];
  //  $$ts = $_POST['ts'];
    file_put_contents('../db/events.tracker', $eventid . ',' . $puser . ',' . $passwd . ',' . $evtname . ',' . $evttype . ',' . $evmain . ',' . $evmax . ',' . $evdeefault . "\r\n", FILE_APPEND | LOCK_EX);
  } else if ($method == 'list') {
    readfile('../db/events.tracker');
  }
} else if ($target == 'tracker') {
  $eventid = $_POST['eventid'];
  $puser = $_POST['puser'];
  $pname = $_POST['pname'];
  $pvalue = $_POST['pvalue'];

  if ($method == 'add') { 
    $ts = time();
    $tssec = $ts; // Previous impl got value from client
    $tsday = intval($tssec/86400);
    file_put_contents('../db/tracker_' . $tsday . '.txt', $eventid . ',' . $puser . ',' . $pname . ',' . $pvalue . ',' . $tssec . "\r\n", FILE_APPEND | LOCK_EX);
  } else if ($method == 'list') {
    $dayindex = $_POST['dayindex'];
    readfile('../db/tracker_' . $dayindex . '.txt');
  }
}