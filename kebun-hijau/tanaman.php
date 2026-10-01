<?php require 'config.php';
$s=db()->prepare('SELECT * FROM tanaman WHERE id=?');$s->execute([(int)($_GET['id']??0)]);$t=$s->fetch();
if(!$t){http_response_code(404);head('Tidak ditemukan');die('<main class="wrap"><h2>Tanaman tidak ditemukan</h2><a href="index.php">← Kembali</a></main>');}
$o=db()->prepare('SELECT id,nama,foto FROM tanaman WHERE id<>? ORDER BY RAND() LIMIT 3');$o->execute([$t['id']]);
head($t['nama'].' - Kebun Hijau'); $y=yt($t['video_url']); ?>
<header class="top"><a class="logo" href="index.php">🌿 Kebun Hijau</a><a href="index.php">← Semua tanaman</a></header>
<main class="wrap">
<h1><?=e($t['nama'])?></h1><em><?=e($t['nama_latin'])?></em>
<?php if($t['foto']): ?><img class="big" src="uploads/<?=e($t['foto'])?>" alt="<?=e($t['nama'])?>"><?php endif ?>
<h2>Tentang</h2><p><?=nl2br(e($t['deskripsi']))?></p>
<h2>Cara Perawatan</h2><p><?=nl2br(e($t['perawatan']))?></p>
<?php if($t['video']||$y): ?><h2>Video</h2>
<?php if($t['video']): ?><video class="big" controls src="uploads/<?=e($t['video'])?>"></video><?php endif ?>
<?php if($y): ?><iframe class="big yt" src="https://www.youtube.com/embed/<?=e($y)?>" allowfullscreen></iframe><?php endif ?>
<?php endif ?>

</main><footer>© <?=date('Y')?> Kebun Hijau</footer></body></html>
