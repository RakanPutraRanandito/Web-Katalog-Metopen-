<?php require 'config.php';
$s=db()->prepare('SELECT * FROM tanaman WHERE id=?');$s->execute([(int)($_GET['id']??0)]);$t=$s->fetch();
if(!$t){http_response_code(404);head('Tidak ditemukan');die('<main class="wrap"><p class="empty"><span>🙈</span>Tanaman tidak ditemukan.<br><a href="index.php">← Kembali</a></p></main>');}

// 3 tanaman lain dari kelompok yang sama
$o=db()->prepare('SELECT id,nama,foto FROM tanaman WHERE kategori=? AND id<>? ORDER BY RAND() LIMIT 3');$o->execute([$t['kategori'],$t['id']]);$lain=$o->fetchAll();

$i=kat($t['kategori']);$y=yt($t['video_url']??'');
// bagian-bagian yang tampil: kolom => [emoji, judul]; yang kosong otomatis disembunyikan
$bagian=['deskripsi'=>['🌱','Tentang'],'karakteristik'=>['🔎','Karakteristik dan Asal-Usul'],'manfaat'=>['💚','Manfaat'],'perawatan'=>['💧','Cara Perawatan']];

head($t['nama'].' - Kebun Hijau'); ?>
<header class="top"><a class="logo" href="index.php">Kebun Hijau RPTRA Permai</a><a class="navlink" href="index.php">← Semua tanaman</a></header>
<main class="wrap detail g-<?=$i['slug']?>">
<a class="badge" href="index.php?k=<?=$i['slug']?>"><?=$i['emoji']?> <?=e($t['kategori'])?></a>
<h1><?=e($t['nama'])?></h1><?php if($t['nama_latin']): ?><em class="latin"><?=e($t['nama_latin'])?></em><?php endif ?>
<?php if($t['foto']): ?><img class="big" src="uploads/<?=e($t['foto'])?>" alt="<?=e($t['nama'])?>"><?php else: ?><div class="big ph-big"><?=$i['emoji']?></div><?php endif ?>

<?php foreach($bagian as $kol=>[$emo,$judul]): if(trim((string)($t[$kol]??''))==='') continue; ?>
<section class="bubble"><h2><span><?=$emo?></span> <?=e($judul)?></h2><p><?=nl2br(e($t[$kol]??''))?></p></section>
<?php endforeach ?>

<?php if(!empty($t['video'])||$y): ?><section class="bubble"><h2><span>🎬</span> Video</h2>
<?php if(!empty($t['video'])): ?><video class="vid" controls src="uploads/<?=e($t['video'])?>"></video><?php endif ?>
<?php if($y): ?><iframe class="vid yt" src="https://www.youtube.com/embed/<?=e($y)?>" title="Video <?=e($t['nama'])?>" allowfullscreen loading="lazy"></iframe><?php endif ?>
</section><?php endif ?>

<?php if($lain): ?><h2 class="lain-title">Tanaman lain di kelompok ini <?=$i['emoji']?></h2>
<div class="grid mini"><?php foreach($lain as $r): ?>
<a class="card" href="tanaman.php?id=<?=$r['id']?>"><?php if($r['foto']): ?><img src="uploads/<?=e($r['foto'])?>" alt="<?=e($r['nama'])?>" loading="lazy"><?php else: ?><div class="ph"><?=$i['emoji']?></div><?php endif ?><div class="cb"><h3><?=e($r['nama'])?></h3></div></a>
<?php endforeach ?></div><?php endif ?>
</main><footer>© <?=date('Y')?> Kebun Hijau RPTRA Permai · Bina Sarana Informatika </footer></body></html>
