<?php require '../config.php';need_admin();
$id=(int)($_GET['id']??0);$t=['nama'=>'','nama_latin'=>'','ringkasan'=>'','deskripsi'=>'','perawatan'=>'','foto'=>'','video'=>'','video_url'=>''];
if($id){$s=db()->prepare('SELECT * FROM tanaman WHERE id=?');$s->execute([$id]);$t=$s->fetch()?:die('Tidak ada');}
if($_SERVER['REQUEST_METHOD']==='POST'){check();
  $f=$t['foto'];$v=$t['video'];
  if(!empty($_POST['hapus_foto'])){rm($f);$f=null;}
  if(!empty($_POST['hapus_video'])){rm($v);$v=null;}
  if($n=upload($_FILES['foto']??null,['jpg','jpeg','png','webp','gif'],5*1024*1024)){rm($f);$f=$n;}
  if($n=upload($_FILES['video']??null,['mp4','webm'],100*1024*1024)){rm($v);$v=$n;}
  $d=[trim($_POST['nama']),trim($_POST['nama_latin']),trim($_POST['ringkasan']),$_POST['deskripsi'],$_POST['perawatan'],$f,$v,trim($_POST['video_url'])];
  if($id){$d[]=$id;db()->prepare('UPDATE tanaman SET nama=?,nama_latin=?,ringkasan=?,deskripsi=?,perawatan=?,foto=?,video=?,video_url=? WHERE id=?')->execute($d);}
  else db()->prepare('INSERT INTO tanaman(nama,nama_latin,ringkasan,deskripsi,perawatan,foto,video,video_url) VALUES(?,?,?,?,?,?,?,?)')->execute($d);
  header('Location: index.php?ok=1');exit;}
head(($id?'Edit':'Tambah').' Tanaman','../'); ?>
<header class="top"><a class="logo" href="index.php">⚙️ Dashboard Admin</a><a href="index.php">← Kembali</a></header>
<main class="wrap"><h2><?=$id?'Edit':'Tambah'?> Tanaman</h2>
<form method="post" enctype="multipart/form-data" class="box"><input type="hidden" name="t" value="<?=csrf()?>">
<label>Nama<input name="nama" required value="<?=e($t['nama'])?>"></label>
<label>Nama Latin<input name="nama_latin" value="<?=e($t['nama_latin'])?>"></label>
<label>Ringkasan (tampil di kartu)<input name="ringkasan" maxlength="255" value="<?=e($t['ringkasan'])?>"></label>
<label>Deskripsi<textarea name="deskripsi" rows="6"><?=e($t['deskripsi'])?></textarea></label>
<label>Cara Perawatan<textarea name="perawatan" rows="6"><?=e($t['perawatan'])?></textarea></label>
<label>Foto (jpg/png/webp, maks 5MB)<input type="file" name="foto" accept="image/*"></label>
<?php if($t['foto']): ?><img class="th" src="../uploads/<?=e($t['foto'])?>"> <label class="ck"><input type="checkbox" name="hapus_foto"> Hapus foto</label><?php endif ?>
<label>Video upload (mp4/webm, maks 100MB*)<input type="file" name="video" accept="video/mp4,video/webm"></label>
<?php if($t['video']): ?><label class="ck"><input type="checkbox" name="hapus_video"> Hapus video (<?=e($t['video'])?>)</label><?php endif ?>
<label>Atau link YouTube<input name="video_url" placeholder="https://www.youtube.com/watch?v=..." value="<?=e($t['video_url'])?>"></label>
<small>*Batas upload juga dipengaruhi <code>upload_max_filesize</code> di hosting. Video besar lebih aman pakai link YouTube.</small>
<button class="btn">Simpan</button></form></main></body></html>
