<?php require 'config.php';
$d=db();
$d->exec("CREATE TABLE IF NOT EXISTS admins(id INT AUTO_INCREMENT PRIMARY KEY,username VARCHAR(50) UNIQUE NOT NULL,password VARCHAR(255) NOT NULL)");
$d->exec("CREATE TABLE IF NOT EXISTS tanaman(id INT AUTO_INCREMENT PRIMARY KEY,nama VARCHAR(120) NOT NULL,nama_latin VARCHAR(160),ringkasan VARCHAR(255),deskripsi TEXT,perawatan TEXT,foto VARCHAR(100),video VARCHAR(100),video_url VARCHAR(255),created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
if(!$d->query("SELECT 1 FROM admins WHERE username='admin'")->fetch())
  $d->prepare("INSERT INTO admins(username,password) VALUES('admin',?)")->execute([password_hash('admin123',PASSWORD_DEFAULT)]);
if(!$d->query("SELECT 1 FROM tanaman LIMIT 1")->fetch()){
  $s=$d->prepare("INSERT INTO tanaman(nama,nama_latin,ringkasan,deskripsi,perawatan,karakteristik,jenis,manfaat) VALUES(?,?,?,?,?,?,?,?,?)");
  $s->execute(['Monstera','Monstera deliciosa','Tanaman hias daun berlubang yang populer.',"Monstera berasal dari hutan tropis Amerika Tengah. Daunnya besar, mengkilap, dan berlubang khas.",'Cahaya terang tidak langsung. Siram saat 2-3 cm tanah atas kering. Pupuk sebulan sekali.', 'Tanaman hias yang tahan terhadap kondisi kering dan minim cahaya.', 'Populer di kalangan pemula dan penggemar tanaman hias.', 'Membantu menyaring udara dalam ruangan.']);
  $s->execute(['Lidah Mertua','Sansevieria trifasciata','Tangguh, cocok untuk pemula.',"Lidah mertua tahan kondisi kering dan minim cahaya, serta dikenal membantu menyaring udara dalam ruangan.",'Siram 2 minggu sekali. Hindari genangan air. Tahan cahaya rendah.', 'Tanaman hias yang tahan terhadap kondisi kering dan minim cahaya.', 'Populer di kalangan pemula dan penggemar tanaman hias.', 'Membantu menyaring udara dalam ruangan.']);
  $s->execute(['Aglaonema','Aglaonema commutatum','Daun berwarna cantik untuk dalam ruangan.',"Aglaonema atau sri rezeki punya corak daun merah, hijau, dan perak yang menarik.",'Tempatkan di cahaya sedang. Jaga media tanam tetap lembap, tidak becek.', 'Tanaman hias yang tahan terhadap kondisi kering dan minim cahaya.', 'Populer di kalangan pemula dan penggemar tanaman hias.', 'Membantu menyaring udara dalam ruangan.']);
}
echo "<h2>Instalasi berhasil ✅</h2><p>Login admin: <b>admin</b> / <b>admin123</b></p><p><b>HAPUS file install.php dari hosting sekarang.</b></p><a href='admin/login.php'>Ke halaman login</a>";
