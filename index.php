<?php
require_once 'db.php';

// helper untuk escape output
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$errors = [];
$success = '';

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_kontrak = isset($_POST['no_kontrak']) ? trim($_POST['no_kontrak']) : '';
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $otr = isset($_POST['otr']) ? str_replace([',',' '], ['', ''], trim($_POST['otr'])) : '';
    $jangka_waktu = isset($_POST['jangka_waktu']) ? (int)$_POST['jangka_waktu'] : 0;
    $dp = isset($_POST['dp']) ? (float)$_POST['dp'] : 0;

    // Validasi sederhana
    if ($no_kontrak === '') $errors[] = "No. kontrak wajib diisi.";
    if ($nama === '') $errors[] = "Nama wajib diisi.";
    if ($otr === '' || !is_numeric($otr) || $otr <= 0) $errors[] = "OTR harus angka > 0.";
    if ($jangka_waktu <= 0) $errors[] = "Jangka waktu (bulan) harus > 0.";
    if ($dp < 0 || $dp > $otr) $errors[] = "DP tidak valid.";

    // Cek duplicate no_kontrak
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM kontrak WHERE no_kontrak = ?");
        $stmt->execute([$no_kontrak]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "No kontrak '$no_kontrak' sudah ada. Gunakan yang lain.";
        }
    }

    // Insert jika valid
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO kontrak (no_kontrak, nama, otr, dp, jangka_waktu) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$no_kontrak, $nama, $otr, $dp, $jangka_waktu]);
        $success = "Kontrak berhasil ditambahkan.";
        // HITUNG CICILAN
        $pokok = $otr - $dp;

        // Tentukan bunga
        if ($jangka_waktu <= 12) {
            $bunga_rate = 0.12;
        } elseif ($jangka_waktu <= 24) {
            $bunga_rate = 0.14;
        } else {
            $bunga_rate = 0.165;
        }

        $total = $pokok + ($pokok * $bunga_rate);
        $angsuran = $total / $jangka_waktu;

        // GENERATE JADWAL ANGSURAN
        $tanggal_awal = date('Y-m-d');

        for ($i = 1; $i <= $jangka_waktu; $i++) {
            $jatuh_tempo = date('Y-m-d', strtotime("+$i month", strtotime($tanggal_awal)));

            $stmt = $pdo->prepare("
                INSERT INTO jadwal_angsuran 
                (no_kontrak, angsuran_ke, angsuran_perbln, tanggal_jatuh_tempo, status_bayar) 
                VALUES (?, ?, ?, ?, 'BELUM BAYAR')
            ");

            $stmt->execute([
                $no_kontrak,
                $i,
                round($angsuran),
                $jatuh_tempo
            ]);
        }

        // clear POST agar form kosong
        header("Location: " . $_SERVER['PHP_SELF'] . "?added=1");
        exit;
    }
}

// Ambil daftar kontrak
$stmt = $pdo->query("SELECT * FROM kontrak ORDER BY created_at DESC");
$kontrak_list = $stmt->fetchAll();

?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <title>Input Kontrak Cicilan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body { font-family: Arial, Helvetica, sans-serif; max-width: 900px; margin: 24px auto; padding: 0 16px; color: #222; }
    h1 { margin-bottom: 6px; }
    form { background:#f7f7f7; padding:16px; border-radius:8px; margin-bottom:20px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
    label { display:block; margin-top:8px; font-weight:600; }
    input[type="text"], input[type="number"] { width:100%; padding:8px 10px; margin-top:6px; box-sizing:border-box; border:1px solid #ccc; border-radius:6px; }
    button { margin-top:12px; padding:10px 14px; border:none; background:#0b76ef; color:white; border-radius:6px; cursor:pointer; }
    .errors { background:#ffdede; border:1px solid #ffbcbc; padding:10px; border-radius:6px; margin-bottom:12px; }
    .success { background:#e6ffed; border:1px solid #b7f0c6; padding:10px; border-radius:6px; margin-bottom:12px; }
    table { width:100%; border-collapse: collapse; }
    th, td { padding:8px 10px; border-bottom:1px solid #eee; text-align:left; }
    th { background:#fafafa; }
    .small { font-size:0.9rem; color:#666; }
  </style>
</head>
<body>
  <h1>Input Kontrak Cicilan</h1>
  <p class="small">Masukkan data pelanggan yang ingin melakukan cicilan. Daftar kontrak muncul di bawah setelah disimpan.</p>

  <?php if (!empty($errors)): ?>
    <div class="errors">
      <ul>
        <?php foreach ($errors as $err): ?>
          <li><?php echo e($err); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['added'])): ?>
    <div class="success">Kontrak berhasil ditambahkan.</div>
  <?php endif; ?>

  <form method="post" action="">
    <label for="no_kontrak">No. Kontrak</label>
    <input type="text" id="no_kontrak" name="no_kontrak" value="<?php echo isset($_POST['no_kontrak']) ? e($_POST['no_kontrak']) : ''; ?>" required>

    <label for="nama">Nama Pelanggan</label>
    <input type="text" id="nama" name="nama" value="<?php echo isset($_POST['nama']) ? e($_POST['nama']) : ''; ?>" required>

    <label for="otr">OTR (angka, tanpa simbol)</label>
    <input type="text" id="otr" name="otr" value="<?php echo isset($_POST['otr']) ? e($_POST['otr']) : ''; ?>" required placeholder="contoh: 250000000">

    <label for="jangka_waktu">Jangka Waktu (bulan)</label>
    <input type="number" id="jangka_waktu" name="jangka_waktu" min="1" value="<?php echo isset($_POST['jangka_waktu']) ? e($_POST['jangka_waktu']) : '12'; ?>" required>

    <label>DP</label>
    <input type="number" name="dp" required>

    <button type="submit">Simpan Kontrak</button>
  </form>

  <h2>Daftar Kontrak</h2>
  <?php if (count($kontrak_list) === 0): ?>
    <p class="small">Belum ada kontrak.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>No. Kontrak</th>
          <th>Nama</th>
          <th>OTR</th>
          <th>Jangka (bulan)</th>
          <th>Dibuat</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($kontrak_list as $k): ?>
          <tr>
            <td><?php echo e($k['no_kontrak']); ?></td>
            <td><?php echo e($k['nama']); ?></td>
            <td>Rp <?php echo number_format($k['otr'], 0, ',', '.'); ?></td>
            <td><?php echo e($k['jangka_waktu']); ?> bulan</td>
            <td class="small"><?php echo e($k['created_at']); ?></td>
            <td>
                <a href="detail.php?no_kontrak=<?php echo e($k['no_kontrak']); ?>"
                    style="background:#28a745;color:white;padding:6px 10px;border-radius:4px;text-decoration:none;">
                    Detail Cicilan
                </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <hr style="margin:24px 0;" />
</body>
</html>
