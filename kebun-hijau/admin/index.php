<?php require '../config.php';need_admin();
if($_SERVER['REQUEST_METHOD']==='POST'){check();
  $s=db()->prepare('SELECT foto,video FROM tanaman WHERE id=?');$s->execute([(int)$_POST['id']]);
  if($r=$s->fetch()){rm($r['foto']);rm($r['video']);db()->prepare('DELETE FROM tanaman WHERE id=?')->execute([(int)$_POST['id']]);}
  header('Location: index.php?ok=1');exit;}

$rows=db()->query('SELECT id,nama,nama_latin,kategori,foto,video,video_url FROM tanaman ORDER BY nama')->fetchAll();
$kelompok=[];foreach(array_keys(KATEGORI) as $n)$kelompok[$n]=[];
foreach($rows as $r){$n=isset(KATEGORI[$r['kategori']])?$r['kategori']:'Lainnya';$kelompok[$n][]=$r;}
$kelompok=array_filter($kelompok,fn($v,$n)=>isset(KATEGORI[$n])||$v,ARRAY_FILTER_USE_BOTH); // "Lainnya" hanya muncul kalau ada isinya

head('Dashboard Admin','../'); ?>
<header class="top"><a class="logo" href="index.php">Dashboard Admin</a><span class="navlink"><a href="../index.php" target="_blank">Lihat website</a> · <a href="logout.php">Keluar (<?=e($_SESSION['admin'])?>)</a></span></header>
<main class="wrap wide">
<div class="bar"><h2>Daftar Tanaman <span class="count"><?=count($rows)?></span></h2><a class="btn" href="form.php">+ Tambah Tanaman</a></div>
<?php if(isset($_GET['ok'])) echo '<p class="ok">✅ Berhasil disimpan.</p>' ?>

<nav class="chips admin-chips" aria-label="Loncat ke kelompok">
<?php foreach($kelompok as $n=>$list): $i=kat($n); ?><a class="chip c-<?=$i['slug']?>" href="#<?=$i['slug']?>"><?=$i['emoji']?> <?=e(str_replace('Tanaman ','',$n))?> <b><?=count($list)?></b></a><?php endforeach ?>
</nav>

<?php foreach($kelompok as $n=>$list): $i=kat($n); ?>
<section class="group g-<?=$i['slug']?>" id="<?=$i['slug']?>">
  <div class="bar"><h2 class="group-title"><span class="emo"><?=$i['emoji']?></span><?=e($n)?><small><?=count($list)?> tanaman</small></h2>
  <?php if(isset(KATEGORI[$n])): ?><a class="btn s" href="form.php?k=<?=$i['slug']?>">+ Tambah di sini</a><?php endif ?></div>
  <?php if(!$list): ?><p class="empty-mini">Belum ada tanaman di kelompok ini 🌱</p><?php else: ?>
  <div class="scroll"><table><tr><th>Foto</th><th>Nama</th><th>Media</th><th>Aksi</th></tr>
  <?php foreach($list as $r): ?><tr>
    <td><?php if($r['foto']) echo '<img class="th" src="../uploads/'.e($r['foto']).'" alt="">'; else echo '<span class="th-ph">'.$i['emoji'].'</span>' ?></td>
    <td><b><?=e($r['nama'])?></b><br><small><?=e($r['nama_latin'])?></small></td>
    <td><?=$r['foto']?'📷 ':''?><?=($r['video']||$r['video_url'])?'🎬':''?></td>
    <td class="act"><a class="btn s" href="../tanaman.php?id=<?=$r['id']?>" target="_blank">Lihat</a> <a class="btn s" href="form.php?id=<?=$r['id']?>">Edit</a>
    <form method="post" onsubmit="return confirm('Hapus tanaman ini?')"><input type="hidden" name="t" value="<?=csrf()?>"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn s red">Hapus</button></form></td></tr>
  <?php endforeach ?></table></div>
  <?php endif ?>
</section>
<?php endforeach ?>
</main></body></html>
