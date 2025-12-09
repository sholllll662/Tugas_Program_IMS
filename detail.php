<?php
require_once 'db.php';

if (!isset($_GET['no_kontrak']) || $_GET['no_kontrak'] == '') {
    die("No kontrak tidak ditemukan.");
}

$no_kontrak = $_GET['no_kontrak'];

// Ambil data kontrak
$stmt = $pdo->prepare("SELECT * FROM kontrak WHERE no_kontrak = ?");
$stmt->execute([$no_kontrak]);
$kontrak = $stmt->fetch();

if (!$kontrak) {
    die("Data kontrak tidak ditemukan.");
}

// Ambil jadwal angsuran
$stmt = $pdo->prepare("SELECT * FROM jadwal_angsuran WHERE no_kontrak = ? ORDER BY angsuran_ke ASC");
$stmt->execute([$no_kontrak]);
$jadwal = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Detail Cicilan</title>
  <style>
    body { font-family: Arial; max-width: 900px; margin: 20px auto; }
    table { width:100%; border-collapse: collapse; }
    th, td { border-bottom:1px solid #ddd; padding:8px; }
    th { background:#f5f5f5; }
    .paid { color:green; font-weight:bold; }
    .unpaid { color:red; font-weight:bold; }
    .btn { padding:6px 10px; border-radius:4px; text-decoration:none; color:white; background:#007bff; }
  </style>
</head>
<body>

<h2>Detail Cicilan</h2>

<p><b>No Kontrak:</b> <?php echo htmlspecialchars($kontrak['no_kontrak']); ?></p>
<p><b>Nama:</b> <?php echo htmlspecialchars($kontrak['nama']); ?></p>
<p><b>OTR:</b> Rp <?php echo number_format($kontrak['otr']); ?></p>
<p><b>Jangka Waktu:</b> <?php echo $kontrak['jangka_waktu']; ?> bulan</p>

<hr>

<h3>Jadwal Angsuran</h3>

<?php if (count($jadwal) == 0): ?>
    <p>Jadwal angsuran belum dibuat.</p>
<?php else: ?>

<table>
<tr>
  <th>Angsuran Ke</th>
  <th>Nominal</th>
  <th>Jatuh Tempo</th>
  <th>Status</th>
</tr>

<?php foreach ($jadwal as $row): ?>
<tr>
  <td><?= $row['angsuran_ke']; ?></td>
  <td>Rp <?= number_format($row['angsuran_perbln']); ?></td>
  <td><?= $row['tanggal_jatuh_tempo']; ?></td>
  <td class="<?= $row['status_bayar'] == 'LUNAS' ? 'paid' : 'unpaid' ?>">
       <?= $row['status_bayar']; ?>
  </td>
</tr>
<?php endforeach; ?>

</table>

<?php endif; ?>

<br>
<a href="index.php" class="btn">⬅ Kembali</a>

</body>
</html>
