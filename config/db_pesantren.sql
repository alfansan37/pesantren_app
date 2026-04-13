USE db_pesantren;

-- Tabel santri
CREATE TABLE santri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nis VARCHAR(50) NOT NULL UNIQUE,
    alamat TEXT,
    tanggal_masuk DATE,
    nama_orang_tua VARCHAR(100),
    nomer_hp_ortu VARCHAR(20)
);

-- Tabel ustadz
CREATE TABLE ustadz (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    niu VARCHAR(50) NOT NULL UNIQUE,
    alamat TEXT,
    spesialisasi VARCHAR(100),
    no_hp_ustadz VARCHAR(20)
);

-- Tabel alumni
CREATE TABLE alumni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT,
    tahun_lulus YEAR,
    pekerjaan VARCHAR(100)
);