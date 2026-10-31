<?php
/* Installer / migrasi database. AMAN dijalankan berkali-kali:
   - membuat tabel kalau belum ada, menambah kolom yang kurang di database lama
   - akun admin hanya dibuat kalau belum ada (password yang sudah ada TIDAK diubah)
   - data contoh (data/tanaman.sql) hanya dimasukkan kalau tabel tanaman masih kosong */
require 'config.php';
$log=[];

try{$d=db();}
catch(PDOException $x){ // database belum dibuat -> coba buat (di hosting biasanya harus lewat cPanel)
  try{$p=new PDO('mysql:host='.DB_HOST.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
      $p->exec('CREATE DATABASE IF NOT EXISTS `'.DB_NAME.'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
      $d=db();$log[]='Database '.DB_NAME.' dibuat.';}
  catch(PDOException $y){head('Instalasi gagal');die('<main class="wrap"><p class="err">Tidak bisa terhubung ke database. Cek DB_HOST, DB_NAME, DB_USER, DB_PASS di config.php.<br><small>'.e($y->getMessage()).'</small></p></main>');}
}

$d->exec("CREATE TABLE IF NOT EXISTS admins(id INT AUTO_INCREMENT PRIMARY KEY,username VARCHAR(50) UNIQUE NOT NULL,password VARCHAR(255) NOT NULL) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$d->exec("CREATE TABLE IF NOT EXISTS tanaman(id INT AUTO_INCREMENT PRIMARY KEY,nama VARCHAR(120) NOT NULL,nama_latin VARCHAR(160),kategori VARCHAR(80) NOT NULL DEFAULT 'Tanaman Hias',ringkasan VARCHAR(255),deskripsi TEXT,karakteristik TEXT,jenis TEXT,manfaat TEXT,perawatan TEXT,foto VARCHAR(100),video VARCHAR(100),video_url VARCHAR(255),created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,KEY idx_kategori(kategori)) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

// database lama: tambahkan kolom yang belum ada
foreach(['kategori'=>"VARCHAR(80) NOT NULL DEFAULT 'Tanaman Hias'",'karakteristik'=>'TEXT','jenis'=>'TEXT','manfaat'=>'TEXT','video'=>'VARCHAR(100)','video_url'=>'VARCHAR(255)'] as $c=>$def)
  if(!$d->query("SHOW COLUMNS FROM tanaman LIKE '$c'")->fetch()){$d->exec("ALTER TABLE tanaman ADD `$c` $def");$log[]="Kolom $c ditambahkan.";}

// rapikan data lama
$n=$d->exec("UPDATE tanaman SET kategori='Tanaman Sayur / Buah' WHERE kategori='Tanaman Pangan / Sayuran / Buah'");
if($n)$log[]="$n tanaman dipindah ke kelompok 'Tanaman Sayur / Buah'.";
$n=$d->exec("UPDATE tanaman SET ringkasan=LEFT(CONCAT(TRIM(TRAILING '.' FROM SUBSTRING_INDEX(deskripsi,'. ',1)),'.'),255) WHERE ringkasan LIKE 'Kelompok:%' AND deskripsi IS NOT NULL AND deskripsi<>'' AND deskripsi NOT LIKE 'Kelompok:%'");
if($n)$log[]="$n ringkasan placeholder diganti kalimat pertama deskripsi.";

// admin: dibuat hanya kalau belum ada. Password yang sudah ada tidak disentuh.
if(!$d->query("SELECT 1 FROM admins WHERE username='admin'")->fetch()){
  $d->prepare("INSERT INTO admins(username,password) VALUES('admin',?)")->execute([password_hash('admin123',PASSWORD_DEFAULT)]);
  $log[]="Akun admin dibuat.";}

// data awal
if(!$d->query("SELECT 1 FROM tanaman LIMIT 1")->fetch()){
  $f=__DIR__.'/data/tanaman.sql';
  if(is_file($f)){$d->exec(file_get_contents($f));$log[]=$d->query("SELECT COUNT(*) FROM tanaman")->fetchColumn().' tanaman contoh dimasukkan.';}
}

head('Instalasi Kebun Hijau'); ?>
<main class="login"><div class="box"><div class="mascot" aria-hidden="true">🌱</div><h2>Instalasi berhasil ✅</h2>
<?php foreach($log as $l) echo '<p class="ok">'.e($l).'</p>'; if(!$log) echo '<p class="ok">Database sudah up-to-date, tidak ada yang perlu diubah.</p>'; ?>
<p>Login admin: <b>admin</b> / <b>admin123</b></p>
<p class="err"><b>Penting:</b> hapus file <code>install.php</code> dari hosting sekarang.</p>
<a class="btn" href="admin/login.php">Ke halaman login</a> <a href="index.php">Lihat website</a></div></main></body></html>
