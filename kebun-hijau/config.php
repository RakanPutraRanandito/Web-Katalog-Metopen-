<?php
session_start();
// === EDIT BAGIAN INI sesuai database di hosting kamu ===
const DB_HOST='localhost';
const DB_NAME='db_tanaman';
const DB_USER='root';
const DB_PASS='';



function db(){static $p;if(!$p){$p=new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);}return $p;}
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function csrf(){return $_SESSION['t']??=bin2hex(random_bytes(16));}
function check(){if(!hash_equals($_SESSION['t']??'',$_POST['t']??''))die('Token tidak valid');}
function need_admin(){if(empty($_SESSION['admin'])){header('Location: login.php');exit;}}
function yt($u){return preg_match('~(?:youtu\.be/|v=|embed/)([\w-]{11})~',(string)$u,$m)?$m[1]:null;}
function upload($f,$ok,$max){
  if(!isset($f)||$f['error']!==0)return null;
  $x=strtolower(pathinfo($f['name'],PATHINFO_EXTENSION));
  if(!in_array($x,$ok)||$f['size']>$max)return null;
  $n=bin2hex(random_bytes(8)).'.'.$x;
  move_uploaded_file($f['tmp_name'],__DIR__.'/uploads/'.$n);return $n;}
function rm($n){if($n&&is_file(__DIR__.'/uploads/'.$n))unlink(__DIR__.'/uploads/'.$n);}
function head($t,$b=''){echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.e($t).'</title><link rel="stylesheet" href="'.$b.'assets/style.css"></head><body>';}
