CREATE DATABASE praktikum_db;
USE praktikum_db;

CREATE TABLE KEHADIRAN_PRAKTIKUM (
    id_kehadiran_praktikum INT PRIMARY KEY,
    id_kehadiran_asisten INT,
    id_kehadiran_praktikan INT
);

CREATE TABLE KEHADIRAN_PRAKTIKAN (
    id_kehadiran_praktikan INT PRIMARY KEY,
    id_praktikan INT,
    id_pertemuan INT,
    keterangan VARCHAR(255),
    waktu_masuk TIME,
    waktu_keluar TIME
);

CREATE TABLE KEHADIRAN_ASISTEN (
    id_kehadiran_asisten INT PRIMARY KEY,
    id_asisten INT,
    id_pertemuan INT,
    keterangan VARCHAR(255),
    waktu_masuk TIME,
    waktu_keluar TIME
);

CREATE TABLE PRAKTIKAN (
    id_praktikan INT PRIMARY KEY,
    nama VARCHAR(255),
    nim VARCHAR(20)
);

CREATE TABLE PERTEMUAN (
    id_pertemuan INT PRIMARY KEY,
    id_praktikum INT,
    pertemuan_ke INT,
    modul VARCHAR(255),
    kegiatan VARCHAR(255),
    tanggal DATE,
    keterangan VARCHAR(255)
);

CREATE TABLE ASISTEN (
    id_asisten INT PRIMARY KEY,
    nama VARCHAR(255),
    nim VARCHAR(20)
);

CREATE TABLE KELAS_PRAKTIKAN (
    id_kelas_praktikan INT PRIMARY KEY,
    id_praktikan INT,
    id_praktikum INT
);

CREATE TABLE PRAKTIKUM (
    id_praktikum INT PRIMARY KEY,
    nama_praktikum VARCHAR(255),
    kelas VARCHAR(10),
    lab VARCHAR(10),
    waktu_mulai TIME,
    waktu_selesai TIME
);

CREATE TABLE KELAS_ASISTEN (
    id_kelas_asisten INT PRIMARY KEY,
    id_asisten INT,
    id_praktikum INT
);
