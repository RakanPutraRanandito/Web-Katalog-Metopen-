<?php require '../config.php';
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){check();
  $s=db()->prepare('SELECT * FROM admins WHERE username=?');$s->execute([$_POST['u']??'']);$a=$s->fetch();
  if($a&&password_verify($_POST['p']??'',$a['password'])){session_regenerate_id(true);$_SESSION['admin']=$a['username'];header('Location: index.php');exit;}
  $err='Username atau password salah.';}
head('Login Admin','../'); ?>
<main class="login"><form method="post" class="box"><div class="mascot" aria-hidden="true">🌱</div><h2>Login Admin</h2>
<?php if($err) echo '<p class="err">'.e($err).'</p>' ?>
<input type="hidden" name="t" value="<?=csrf()?>">
<label>Username<input name="u" required autofocus></label>
<label>Password<input name="p" type="password" required></label>
<button>Masuk</button><a href="../index.php">← Ke website</a></form></main></body></html>
