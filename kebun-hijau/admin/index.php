<?php require '../config.php';need_admin();
if($_SERVER['REQUEST_METHOD']==='POST'){check();
  $s=db()->prepare('SELECT foto,video FROM tanaman WHERE id=?');$s->execute([(int)$_POST['id']]);
  if($r=$s->fetch()){rm($r['foto']);rm($r['video']);db()->prepare('DELETE FROM tanaman WHERE id=?')->execute([(int)$_POST['id']]);}
  header('Location: index.php?ok=1');exit;}
$rows=db()->query('SELECT * FROM tanaman ORDER BY id DESC')->fetchAll();
head('Dashboard Admin','../'); ?>
<header class="top"><a class="logo" href="index.php">⚙️ Dashboard Admin</a><span><a href="../index.php" target="_blank">Lihat website</a> · <a href="logout.php">Keluar (<?=e($_SESSION['admin'])?>)</a></span></header>
<main class="wrap wide">
<div class="bar"><h2>Daftar Tanaman (<?=count($rows)?>)</h2><a class="btn" href="form.php">+ Tambah Tanaman</a></div>
<?php if(isset($_GET['ok'])) echo '<p class="ok">Berhasil disimpan.</p>' ?>
<div class="scroll"><table><tr><th>Foto</th><th>Nama</th><th>Media</th><th>Aksi</th></tr>
<?php foreach($rows as $r): ?><tr>
<td><?php if($r['foto']) echo '<img class="th" src="../uploads/'.e($r['foto']).'">'; else echo '🪴' ?></td>
<td><b><?=e($r['nama'])?></b><br><small><?=e($r['nama_latin'])?></small></td>
<td><?=$r['foto']?'📷 ':''?><?=($r['video']||$r['video_url'])?'🎬':''?></td>
<td class="act"><a class="btn s" href="../tanaman.php?id=<?=$r['id']?>" target="_blank">Lihat</a> <a class="btn s" href="form.php?id=<?=$r['id']?>">Edit</a>
<form method="post" onsubmit="return confirm('Hapus tanaman ini?')"><input type="hidden" name="t" value="<?=csrf()?>"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn s red">Hapus</button></form></td></tr>
<?php endforeach ?></table></div></main></body></html>
