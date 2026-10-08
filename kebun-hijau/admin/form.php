<?php require '../config.php';need_admin();
$id=(int)($_GET['id']??0);
$t=['nama'=>'','nama_latin'=>'','kategori'=>kat_dari_slug($_GET['k']??'')?:'Tanaman Hias','ringkasan'=>'','deskripsi'=>'','karakteristik'=>'','jenis'=>'','manfaat'=>'','perawatan'=>'','foto'=>'','video'=>'','video_url'=>''];
if($id){$s=db()->prepare('SELECT * FROM tanaman WHERE id=?');$s->execute([$id]);$row=$s->fetch();if(!$row)die('Tidak ada');$t=array_merge($t,$row);}

$pilihan=array_keys(KATEGORI);
if($t['kategori']!==''&&!in_array($t['kategori'],$pilihan))$pilihan[]=$t['kategori']; // jaga data lama yang kelompoknya di luar daftar
$err=[];
if($_SERVER['REQUEST_METHOD']==='POST'){check();
  $f=$t['foto'];$v=$t['video'];
  $kat=in_array($_POST['kategori']??'',$pilihan)?$_POST['kategori']:$pilihan[0];
  // beri tahu kalau file dipilih tapi ditolak (terlalu besar / format salah), jangan diam-diam hilang
  $fotoBaru=upload($_FILES['foto']??null,['jpg','jpeg','png','webp','gif'],5*1024*1024);
  $videoBaru=upload($_FILES['video']??null,['mp4','webm'],100*1024*1024);
  if(($_FILES['foto']['error']??4)!==UPLOAD_ERR_NO_FILE&&!$fotoBaru)$err[]='Foto gagal diunggah. Pastikan jpg/png/webp/gif dan maksimal 5MB (cek juga batas upload_max_filesize di hosting).';
  if(($_FILES['video']['error']??4)!==UPLOAD_ERR_NO_FILE&&!$videoBaru)$err[]='Video gagal diunggah. Pastikan mp4/webm dan tidak melebihi batas upload di hosting. Untuk video besar, pakai link YouTube.';
  $nama=trim($_POST['nama']??'');if($nama==='')$err[]='Nama tanaman wajib diisi.';
  $isi=['nama'=>$nama,'nama_latin'=>trim($_POST['nama_latin']??''),'kategori'=>$kat,'ringkasan'=>trim($_POST['ringkasan']??''),'deskripsi'=>$_POST['deskripsi']??'','karakteristik'=>$_POST['karakteristik']??'','jenis'=>$_POST['jenis']??'','manfaat'=>$_POST['manfaat']??'','perawatan'=>$_POST['perawatan']??'','video_url'=>trim($_POST['video_url']??'')];
  if($err){rm($fotoBaru);rm($videoBaru);$t=array_merge($t,$isi);}   // tampilkan lagi form dengan isian tadi
  else{
    if(!empty($_POST['hapus_foto'])){rm($f);$f=null;}
    if(!empty($_POST['hapus_video'])){rm($v);$v=null;}
    if($fotoBaru){rm($f);$f=$fotoBaru;}
    if($videoBaru){rm($v);$v=$videoBaru;}
    $d=array_values($isi);array_push($d,$f?:null,$v?:null);
    if($id){$d[]=$id;db()->prepare('UPDATE tanaman SET nama=?,nama_latin=?,kategori=?,ringkasan=?,deskripsi=?,karakteristik=?,jenis=?,manfaat=?,perawatan=?,video_url=?,foto=?,video=? WHERE id=?')->execute($d);}
    else db()->prepare('INSERT INTO tanaman(nama,nama_latin,kategori,ringkasan,deskripsi,karakteristik,jenis,manfaat,perawatan,video_url,foto,video) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)')->execute($d);
    header('Location: index.php?ok=1');exit;}
}
head(($id?'Edit':'Tambah').' Tanaman','../'); ?>
<header class="top"><a class="logo" href="index.php">Dashboard Admin</a><a class="navlink" href="index.php">← Kembali</a></header>
<main class="wrap"><h2><?=$id?'✏️ Edit':'🌱 Tambah'?> Tanaman</h2>
<?php foreach($err as $m) echo '<p class="err">'.e($m).'</p>' ?>
<form method="post" enctype="multipart/form-data" class="box"><input type="hidden" name="t" value="<?=csrf()?>">
<label>Nama<input name="nama" required value="<?=e($t['nama'])?>"></label>
<label>Nama Latin<input name="nama_latin" value="<?=e($t['nama_latin'])?>"></label>
<label>Kelompok<select name="kategori" required><?php foreach($pilihan as $k): ?><option value="<?=e($k)?>" <?=$t['kategori']===$k?'selected':''?>><?=kat($k)['emoji']?> <?=e($k)?></option><?php endforeach ?></select></label>
<label>Ringkasan (tampil di kartu)<input name="ringkasan" maxlength="255" value="<?=e($t['ringkasan'])?>"></label>
<label>Deskripsi<textarea name="deskripsi" rows="6"><?=e($t['deskripsi'])?></textarea></label>
<label>Karakteristik dan Asal-Usul<textarea name="karakteristik" rows="6"><?=e($t['karakteristik'])?></textarea></label>
<label>Manfaat<textarea name="manfaat" rows="6"><?=e($t['manfaat'])?></textarea></label>
<label>Cara Perawatan<textarea name="perawatan" rows="6"><?=e($t['perawatan'])?></textarea></label>
<label>Foto (jpg/png/webp, maks 5MB)<input type="file" name="foto" accept="image/*"></label>
<?php if($t['foto']): ?><div class="cur"><img class="th" src="../uploads/<?=e($t['foto'])?>" alt=""> <label class="ck"><input type="checkbox" name="hapus_foto"> Hapus foto</label></div><?php endif ?>
<label>Video upload (mp4/webm, maks 100MB*)<input type="file" name="video" accept="video/mp4,video/webm"></label>
<?php if($t['video']): ?><label class="ck"><input type="checkbox" name="hapus_video"> Hapus video (<?=e($t['video'])?>)</label><?php endif ?>
<label>Link YouTube (opsional)<input name="video_url" type="url" placeholder="https://www.youtube.com/watch?v=..." value="<?=e($t['video_url'])?>"></label>
<small>*Batas upload juga dipengaruhi <code>upload_max_filesize</code> di hosting. Video besar lebih aman pakai link YouTube.</small>
<button class="btn">Simpan 💚</button></form></main></body></html>
