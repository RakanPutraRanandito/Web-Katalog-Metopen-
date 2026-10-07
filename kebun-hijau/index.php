<?php require 'config.php';
// aktifkan error reporting dulu

$q=trim($_GET['q']??'');
$s=db()->prepare("SELECT * FROM tanaman WHERE nama LIKE ? OR nama_latin LIKE ? ORDER BY FIELD(kategori,'Tanaman Obat','Tanaman Hias','Tanaman Pangan / Sayuran / Buah'), kategori, nama");
$s->execute(["%$q%","%$q%"]);$rows=$s->fetchAll();
$kelompok=[];
foreach($rows as $r)$kelompok[$r['kategori']?:'Lainnya'][]=$r;
head('Kebun Hijau - Koleksi Tanaman'); ?>
<header class="top"><a class="logo" href="index.php">🌿 Kebun Hijau</a><a href="admin/login.php">Admin</a></header>
<section class="hero"><h1>Kenali &amp; rawat tanamanmu</h1><p>Koleksi tanaman lengkap dengan panduan perawatan.</p>
<form><input name="q" value="<?=e($q)?>" placeholder="Cari tanaman..."><button>Cari</button></form></section>
<?php if(!$rows): ?><main class="grid"><p class="empty">Tanaman tidak ditemukan.</p></main>
<?php else: foreach($kelompok as $kategori=>$tanamanKelompok): ?>
<section class="plant-group"><h2><?=e($kategori)?></h2><main class="grid">
<?php foreach($tanamanKelompok as $r): ?>
<a class="card" href="tanaman.php?id=<?=$r['id']?>">
<?php if($r['foto']): ?><img src="uploads/<?=e($r['foto'])?>" alt="<?=e($r['nama'])?>"><?php else: ?><div class="ph">🪴</div><?php endif ?>
<div class="cb"><h3><?=e($r['nama'])?></h3><em><?=e($r['nama_latin'])?></em><p><?=e($r['ringkasan'])?></p><span>Lihat detail →</span></div></a>
<?php endforeach; ?>
</main></section>
<?php endforeach; endif; ?>
<footer>© <?=date('Y')?> Kebun Hijau</footer></body></html>
