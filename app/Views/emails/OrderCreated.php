<?php
/**
 * @var array $data
 * Data pesanan: nama, layanan, jumlah, metode_bayar, tipe, catatan, created_at
 */
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Pesanan Baru Masuk</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8f8f8; padding: 24px;">
  <div style="max-width: 480px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 24px;">
    <h2 style="color: #2d3748;">Pesanan Baru Masuk</h2>
    <p>Halo Admin,</p>
    <p>Ada pesanan baru yang masuk dengan detail sebagai berikut:</p>
    <table style="width: 100%; border-collapse: collapse;">
      <tr><td><b>Nama</b></td><td><?= esc($data['nama'] ?? '-') ?></td></tr>
      <tr><td><b>Layanan</b></td><td><?= esc($data['layanan'] ?? '-') ?></td></tr>
      <tr><td><b>Jumlah</b></td><td><?= esc($data['jumlah'] ?? '-') ?></td></tr>
      <tr><td><b>Metode Bayar</b></td><td><?= esc($data['metode_bayar'] ?? '-') ?></td></tr>
      <tr><td><b>Tipe</b></td><td><?= esc($data['tipe'] ?? '-') ?></td></tr>
      <tr><td><b>Catatan</b></td><td><?= esc($data['catatan'] ?? '-') ?></td></tr>
      <tr><td><b>Tanggal</b></td><td><?= esc($data['created_at'] ?? '-') ?></td></tr>
    </table>
    <p style="margin-top: 24px;">Segera cek dan proses pesanan ini di dashboard admin.</p>
    <p style="color: #888; font-size: 12px; margin-top: 32px;">Email otomatis dari sistem Pro Laundry</p>
  </div>
</body>
</html> 