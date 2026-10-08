<?php
require 'config.php';

$q    = trim($_GET['q'] ?? '');
$slug = $_GET['k'] ?? '';
$aktif = kat_dari_slug($slug);            // nama kelompok yang dipilih (null = semua)
if(!$aktif) $slug = '';

// Cari di nama, nama latin, ringkasan, dan manfaat (jadi "batuk" pun ketemu tanaman obatnya)
$like = '%'.addcslashes($q,'%_\\').'%';
$s = db()->prepare('SELECT id,nama,nama_latin,kategori,ringkasan,foto FROM tanaman
  WHERE nama LIKE ? OR nama_latin LIKE ? OR ringkasan LIKE ? OR manfaat LIKE ?
  ORDER BY nama');
$s->execute([$like,$like,$like,$like]);
$rows = $s->fetchAll();

// Kelompokkan sesuai urutan di config; kategori tak dikenal masuk "Lainnya"
$kelompok = [];
foreach(array_keys(KATEGORI) as $n) $kelompok[$n] = [];
foreach($rows as $r){
  $n = isset(KATEGORI[$r['kategori']]) ? $r['kategori'] : 'Lainnya';
  $kelompok[$n][] = $r;
}
$total = count($rows);
$tampil = $aktif ? [$aktif=>$kelompok[$aktif]] : array_filter($kelompok);

function link_filter($slug,$q){
  $p = array_filter(['q'=>$q,'k'=>$slug],fn($v)=>$v!=='');
  return 'index.php'.($p?'?'.http_build_query($p):'');
}

head('Kebun Hijau - Koleksi Tanaman'); ?>
<header class="top"><a class="logo" href="index.php">KEBUN HIJAU RPTRA PERMAI</a><a class="navlink" href="admin/login.php">Admin</a></header>

<section class="hero">
  <video autoplay muted loop class="hero-video">
    <source src="uploads/intro.mp4" type="video/mp4">
    Browser kamu tidak mendukung video.
  </video>
  <div class="hero-text">
    <h1>Kenali Jenis &amp; Manfaat tanaman</h1>
    <p>Koleksi tanaman di kebun RPTRA PERMAI.</p>
    <form class="search">
      <input name="q" value="<?=e($q)?>" placeholder="Cari tanaman...">
      <button>Cari</button>
    </form>
  </div>
</section>






<nav class="chips" aria-label="Kelompok tanaman">
  <a class="chip<?=$slug===''?' on':''?>" href="<?=e(link_filter('',$q))?>">🌳 Semua <b><?=$total?></b></a>
  <?php foreach(KATEGORI as $n=>$i): ?>
  <a class="chip c-<?=$i['slug']?><?=$slug===$i['slug']?' on':''?>" href="<?=e(link_filter($i['slug'],$q))?>"><?=$i['emoji']?> <?=e(str_replace('Tanaman ','',$n))?> <b><?=count($kelompok[$n])?></b></a>
  <?php endforeach ?>
</nav>

<?php if($q!==''): ?>
<p class="info">Hasil untuk “<b><?=e($q)?></b>” · <a href="<?=e(link_filter($slug,''))?>">hapus pencarian ✕</a></p>
<?php endif ?>

<main class="katalog">
<?php if(!$total || ($aktif && !$kelompok[$aktif])): ?>
  <p class="empty"><span>🙈</span>Yah, tanaman tidak ditemukan.<br><a href="index.php">Lihat semua tanaman</a></p>
<?php else: foreach($tampil as $nama=>$list): $i = kat($nama); ?>
  <section class="group g-<?=$i['slug']?>" id="<?=$i['slug']?>">
    <h2 class="group-title"><span class="emo"><?=$i['emoji']?></span><?=e($nama)?><small><?=count($list)?> tanaman</small></h2>
    <?php if($i['sub']): ?><p class="group-sub"><?=e($i['sub'])?></p><?php endif ?>
    <div class="grid">
    <?php foreach($list as $r): ?>
      <a class="card" href="tanaman.php?id=<?=$r['id']?>">
        <?php if($r['foto']): ?><img src="uploads/<?=e($r['foto'])?>" alt="<?=e($r['nama'])?>" loading="lazy">
        <?php else: ?><div class="ph"><?=$i['emoji']?></div><?php endif ?>
        <div class="cb"><h3><?=e($r['nama'])?></h3><em><?=e($r['nama_latin'])?></em><p><?=e($r['ringkasan'])?></p><span class="more">Lihat detail →</span></div>
      </a>
    <?php endforeach ?>
    </div>
  </section>
<?php endforeach; endif ?>
</main>
<footer>© <?=date('Y')?> RPTRA Permai · Bina Sarana Informatika Kelompok 3</footer></body></html>
