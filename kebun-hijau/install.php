<?php require 'config.php';
$d=db();
$d->exec("CREATE TABLE IF NOT EXISTS admins(id INT AUTO_INCREMENT PRIMARY KEY,username VARCHAR(50) UNIQUE NOT NULL,password VARCHAR(255) NOT NULL)");
$d->exec("CREATE TABLE IF NOT EXISTS tanaman(id INT AUTO_INCREMENT PRIMARY KEY,nama VARCHAR(120) NOT NULL,nama_latin VARCHAR(160),kategori VARCHAR(80) NOT NULL DEFAULT 'Lainnya',ringkasan VARCHAR(255),deskripsi TEXT,manfaat TEXT,perawatan TEXT,foto VARCHAR(100),video VARCHAR(100),video_url VARCHAR(255),created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
if(!$d->query("SHOW COLUMNS FROM tanaman LIKE 'kategori'")->fetch())
  $d->exec("ALTER TABLE tanaman ADD kategori VARCHAR(80) NOT NULL DEFAULT 'Lainnya' AFTER nama_latin");
if(!$d->query("SHOW COLUMNS FROM tanaman LIKE 'manfaat'")->fetch())
  $d->exec("ALTER TABLE tanaman ADD manfaat TEXT AFTER deskripsi");
if(!$d->query("SELECT 1 FROM admins WHERE username='admin'")->fetch())
  $d->prepare("INSERT INTO admins(username,password) VALUES('admin',?)")->execute([password_hash('admin123',PASSWORD_DEFAULT)]);
if(!$d->query("SELECT 1 FROM tanaman LIMIT 1")->fetch()){
  $s=$d->prepare("INSERT INTO tanaman(nama,nama_latin,kategori,ringkasan,deskripsi,perawatan) VALUES(?,?,?,?,?,?)");
  $s->execute(['Monstera','Monstera deliciosa','Tanaman Hias','Tanaman hias daun berlubang yang populer.',"Monstera berasal dari hutan tropis Amerika Tengah. Daunnya besar, mengkilap, dan berlubang khas.",'Cahaya terang tidak langsung. Siram saat 2-3 cm tanah atas kering. Pupuk sebulan sekali.']);
  $s->execute(['Lidah Mertua','Sansevieria trifasciata','Tanaman Hias','Tangguh, cocok untuk pemula.',"Lidah mertua tahan kondisi kering dan minim cahaya, serta dikenal membantu menyaring udara dalam ruangan.",'Siram 2 minggu sekali. Hindari genangan air. Tahan cahaya rendah.']);
  $s->execute(['Aglaonema','Aglaonema commutatum','Tanaman Hias','Daun berwarna cantik untuk dalam ruangan.',"Aglaonema atau sri rezeki punya corak daun merah, hijau, dan perak yang menarik.",'Tempatkan di cahaya sedang. Jaga media tanam tetap lembap, tidak becek.']);

}
$tanaman=[
  'Tanaman Obat'=>[
    'Kitolod','Daun saga','Daun kelor','Cocor bebek','Kunyit kuning','Kunyit putih',
    'Bangun-bangun','Bawang dayak','Patah tulang','Daun sirih / sirih hijau','Kencur',
    'Bangle','Sereh','Lidah buaya','Kumis kucing','Binahong','Tanaman jahe',
    'Dandang gendis','Temulawak','Pegagan','Kemangi',
  ],
  'Tanaman Hias'=>[
    'Kenanga','Daun pandan / pohon pandan','Daun suji','Melati','Bakung','Aglaonema',
    'Bambu Jepang','Syngonium wendlandii','Kembang sepatu','Keladi','Kayu urip',
    'Pucuk merah','Mangkokan','Bunga Asoka','Kuping gajah','Wali Songo',
    'Ketapang kencana','Patah tulang daun keriting','Bunga Kasandra',
  ],
  'Tanaman Pangan / Sayuran / Buah'=>[
    'Pepaya','Cabe rawit','Cabe hijau','Jagung','Mangga','Terong',
  ],
];
$find=$d->prepare('SELECT id FROM tanaman WHERE nama=? LIMIT 1');
$insert=$d->prepare("INSERT INTO tanaman(nama,kategori,ringkasan,deskripsi,manfaat,perawatan) VALUES(?,?,?,?,?,?)");
$update=$d->prepare('UPDATE tanaman SET kategori=? WHERE nama=?');
foreach($tanaman as $kategori=>$namaTanaman){
  foreach($namaTanaman as $nama){
    $find->execute([$nama]);
    if($find->fetch())$update->execute([$kategori,$nama]);
    else $insert->execute([$nama,$kategori,"Kelompok: $kategori.","$nama termasuk dalam kelompok $kategori pada katalog Kebun Hijau.",'','Sesuaikan cahaya, penyiraman, dan media tanam dengan kebutuhan tanaman.']);
  }
}
echo "<h2>Instalasi berhasil ✅</h2><p>Login admin: <b>admin</b> / <b>admin123</b></p><p><b>HAPUS file install.php dari hosting sekarang.</b></p><a href='admin/login.php'>Ke halaman login</a>";
