<?php
session_start();

if (!isset($_SESSION['orang_tua_id'], $_SESSION['orang_tua_nis'])) {
    header('Location: index.php');
    exit();
}

require_once '../koneksi.php';

date_default_timezone_set('Asia/Jakarta');

$parentName = $_SESSION['orang_tua_nama'] ?? 'Orang Tua';
$childNis = $_SESSION['orang_tua_nis'];

$errors = [];
$successMessage = '';

$student = null;
$studentStmt = $koneksi->prepare('SELECT nama, kelas FROM data_siswa WHERE nis = ? LIMIT 1');
if ($studentStmt) {
    $studentStmt->bind_param('s', $childNis);
    $studentStmt->execute();
    $studentStmt->bind_result($studentName, $studentClass);
    if ($studentStmt->fetch()) {
        $student = [
            'nama' => $studentName,
            'kelas' => $studentClass,
        ];
    }
    $studentStmt->close();
}

$koneksi->query(
    "CREATE TABLE IF NOT EXISTS orangtua_izin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        masuk_id INT NOT NULL,
        nis VARCHAR(50) NOT NULL,
        tanggal DATE NOT NULL,
        status VARCHAR(20) NOT NULL,
        keterangan TEXT NULL,
        bukti_path VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_masuk_id (masuk_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
);

$availableStatuses = [
    'Sakit' => 'Izin Sakit',
    'Izin' => 'Izin Kegiatan',
    'Alpha' => 'Alpha (Tanpa Keterangan)',
];

$formData = [
    'tanggal' => date('Y-m-d'),
    'status' => '',
    'keterangan' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggalInput = $_POST['tanggal'] ?? date('Y-m-d');
    $statusInput = $_POST['status'] ?? '';
    $keteranganInput = trim($_POST['keterangan'] ?? '');

    $formData['tanggal'] = $tanggalInput;
    $formData['status'] = $statusInput;
    $formData['keterangan'] = $keteranganInput;

    $tanggalValid = DateTime::createFromFormat('Y-m-d', $tanggalInput);
    if (!$tanggalValid) {
        $errors[] = 'Tanggal izin tidak valid.';
    }

    if (!array_key_exists($statusInput, $availableStatuses)) {
        $errors[] = 'Status izin tidak dikenali.';
    }

    if (!isset($_FILES['bukti']) || $_FILES['bukti']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Lampiran bukti wajib diunggah.';
    }

    $uploadedRelativePath = null;
    $uploadedFullPath = null;
    $newFileUploaded = false;

    if (empty($errors) && isset($_FILES['bukti'])) {
        $fileTmp = $_FILES['bukti']['tmp_name'];
        $fileSize = $_FILES['bukti']['size'] ?? 0;
        $mimeType = $fileTmp !== '' ? mime_content_type($fileTmp) : null;

        $allowedMime = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'application/pdf' => 'pdf',
        ];

        if ($mimeType === false || $mimeType === null || !array_key_exists($mimeType, $allowedMime)) {
            $errors[] = 'Format file bukti harus berupa JPG, PNG, atau PDF.';
        }

        $maxSize = 5 * 1024 * 1024; // 5 MB
        if ($fileSize > $maxSize) {
            $errors[] = 'Ukuran file bukti maksimal 5 MB.';
        }

        if (empty($errors)) {
            $uploadDirectory = dirname(__DIR__) . '/uploads/izin';
            if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0775, true) && !is_dir($uploadDirectory)) {
                $errors[] = 'Gagal menyiapkan folder penyimpanan bukti.';
            } else {
                $extension = $allowedMime[$mimeType];
                try {
                    $randomSegment = bin2hex(random_bytes(8));
                } catch (Exception $e) {
                    $errors[] = 'Gagal membuat nama file unik.';
                    $randomSegment = null;
                }

                if ($randomSegment !== null) {
                    $filename = sprintf('izin_%s_%s_%s.%s', $childNis, date('Ymd', strtotime($tanggalInput)), $randomSegment, $extension);
                    $destination = $uploadDirectory . '/' . $filename;
                    if (move_uploaded_file($fileTmp, $destination)) {
                        $uploadedRelativePath = 'uploads/izin/' . $filename;
                        $uploadedFullPath = $destination;
                        $newFileUploaded = true;
                    } else {
                        $errors[] = 'Gagal mengunggah bukti. Silakan coba lagi.';
                    }
                }
            }
        }
    }

    if (empty($errors) && $uploadedRelativePath !== null && $tanggalValid instanceof DateTime) {
        $tanggalFormatted = $tanggalValid->format('Y-m-d');

        try {
            $koneksi->begin_transaction();

            $existingMasukStmt = $koneksi->prepare('SELECT id FROM masuk WHERE nis = ? AND tanggal = ? LIMIT 1');
            if (!$existingMasukStmt) {
                throw new Exception('Gagal menyiapkan data absensi.');
            }
            $existingMasukStmt->bind_param('ss', $childNis, $tanggalFormatted);
            $existingMasukStmt->execute();
            $existingMasukStmt->bind_result($masukId);
            $hasExisting = $existingMasukStmt->fetch();
            $existingMasukStmt->close();

            if ($hasExisting) {
                $updateMasukStmt = $koneksi->prepare('UPDATE masuk SET status = ?, jam_masuk = NULL, latitude = NULL, longitude = NULL WHERE id = ?');
                if (!$updateMasukStmt) {
                    throw new Exception('Gagal memperbarui data absensi.');
                }
                $updateMasukStmt->bind_param('si', $statusInput, $masukId);
                if (!$updateMasukStmt->execute()) {
                    $updateMasukStmt->close();
                    throw new Exception('Gagal menyimpan status izin.');
                }
                $updateMasukStmt->close();
            } else {
                $insertMasukStmt = $koneksi->prepare('INSERT INTO masuk (nis, jam_masuk, tanggal, status, latitude, longitude) VALUES (?, NULL, ?, ?, NULL, NULL)');
                if (!$insertMasukStmt) {
                    throw new Exception('Gagal menyiapkan penyimpanan izin.');
                }
                $insertMasukStmt->bind_param('sss', $childNis, $tanggalFormatted, $statusInput);
                if (!$insertMasukStmt->execute()) {
                    $insertMasukStmt->close();
                    throw new Exception('Gagal menyimpan izin.');
                }
                $masukId = $insertMasukStmt->insert_id;
                $insertMasukStmt->close();
            }

            $existingIzinStmt = $koneksi->prepare('SELECT id, bukti_path FROM orangtua_izin WHERE masuk_id = ? LIMIT 1');
            if (!$existingIzinStmt) {
                throw new Exception('Gagal menyiapkan data lampiran.');
            }
            $existingIzinStmt->bind_param('i', $masukId);
            $existingIzinStmt->execute();
            $existingIzinStmt->bind_result($izinId, $existingBuktiPath);
            $izinExists = $existingIzinStmt->fetch();
            $existingIzinStmt->close();

            if ($izinExists) {
                $updateIzinStmt = $koneksi->prepare('UPDATE orangtua_izin SET status = ?, keterangan = ?, bukti_path = ?, tanggal = ?, nis = ?, updated_at = NOW() WHERE id = ?');
                if (!$updateIzinStmt) {
                    throw new Exception('Gagal memperbarui lampiran izin.');
                }
                $updateIzinStmt->bind_param('sssssi', $statusInput, $keteranganInput, $uploadedRelativePath, $tanggalFormatted, $childNis, $izinId);
                if (!$updateIzinStmt->execute()) {
                    $updateIzinStmt->close();
                    throw new Exception('Gagal memperbarui bukti izin.');
                }
                $updateIzinStmt->close();

                if ($existingBuktiPath) {
                    $oldFullPath = dirname(__DIR__) . '/' . $existingBuktiPath;
                    if (is_file($oldFullPath) && (!is_file($uploadedFullPath) || realpath($oldFullPath) !== realpath($uploadedFullPath))) {
                        @unlink($oldFullPath);
                    }
                }
            } else {
                $insertIzinStmt = $koneksi->prepare('INSERT INTO orangtua_izin (masuk_id, nis, tanggal, status, keterangan, bukti_path) VALUES (?, ?, ?, ?, ?, ?)');
                if (!$insertIzinStmt) {
                    throw new Exception('Gagal menyimpan lampiran izin.');
                }
                $insertIzinStmt->bind_param('isssss', $masukId, $childNis, $tanggalFormatted, $statusInput, $keteranganInput, $uploadedRelativePath);
                if (!$insertIzinStmt->execute()) {
                    $insertIzinStmt->close();
                    throw new Exception('Gagal menyimpan data izin.');
                }
                $insertIzinStmt->close();
            }

            $koneksi->commit();
            $successMessage = 'Pengajuan izin berhasil dikirim.';
            $formData = [
                'tanggal' => date('Y-m-d'),
                'status' => '',
                'keterangan' => '',
            ];
        } catch (Exception $e) {
            $koneksi->rollback();
            $errors[] = $e->getMessage();
            if ($newFileUploaded && $uploadedFullPath && is_file($uploadedFullPath)) {
                @unlink($uploadedFullPath);
            }
        }
    } elseif ($newFileUploaded && $uploadedFullPath && is_file($uploadedFullPath)) {
        @unlink($uploadedFullPath);
    }
}

$izinHistory = [];
$historyQuery = $koneksi->prepare(
    'SELECT m.tanggal, m.status, i.keterangan, i.bukti_path, i.created_at, i.updated_at
     FROM masuk m
     INNER JOIN orangtua_izin i ON i.masuk_id = m.id
     WHERE m.nis = ?
     ORDER BY m.tanggal DESC'
);

if ($historyQuery) {
    $historyQuery->bind_param('s', $childNis);
    $historyQuery->execute();
    $result = $historyQuery->get_result();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $izinHistory[] = $row;
        }
    }
    $historyQuery->close();
}

$koneksi->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Izin Orang Tua</title>
    <link rel="icon" href="../assets/img/smkmadya.png">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#4338CA',
                            foreground: '#F5F3FF',
                        },
                    },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="font-display bg-slate-950/[0.02] min-h-screen py-10">
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-32 -left-32 h-64 w-64 rounded-full bg-primary/20 blur-3xl"></div>
        <div class="absolute top-1/2 right-0 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl"></div>
    </div>

    <div class="mx-auto max-w-5xl px-6">
        <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Selamat datang,</p>
                <h1 class="text-3xl font-semibold text-slate-900"><?= htmlspecialchars($parentName, ENT_QUOTES, 'UTF-8'); ?></h1>
                <?php if ($student): ?>
                    <p class="mt-1 text-sm text-slate-500">Mengajukan izin untuk: <span class="font-semibold text-slate-700"><?= htmlspecialchars($student['nama'], ENT_QUOTES, 'UTF-8'); ?></span> • Kelas <?= htmlspecialchars($student['kelas'], ENT_QUOTES, 'UTF-8'); ?> • NIS <?= htmlspecialchars($childNis, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php else: ?>
                    <p class="mt-1 text-sm text-rose-500">Data siswa tidak ditemukan. Mohon hubungi admin.</p>
                <?php endif; ?>
            </div>
            <div class="flex items-center gap-2">
                <a href="dashboard.php" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-primary hover:text-primary">Dashboard</a>
                <a href="logout.php" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-rose-300 hover:text-rose-600">Keluar</a>
            </div>
        </div>

        <div class="mb-8 rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-xl shadow-indigo-500/5 backdrop-blur">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Form Pengajuan Izin</h2>
                    <p class="text-sm text-slate-500">Gunakan formulir ini untuk mengajukan izin sakit, izin kegiatan, ataupun keterangan lainnya.</p>
                </div>
                <div class="rounded-2xl bg-primary/10 px-4 py-2 text-sm font-medium text-primary">Izin Anda tercatat langsung pada sistem absensi.</div>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="mt-6 space-y-3">
                    <?php foreach ($errors as $error): ?>
                        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($successMessage !== ''): ?>
                <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="mt-8 grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <label for="tanggal" class="text-sm font-medium text-slate-700">Tanggal Izin</label>
                    <input type="date" id="tanggal" name="tanggal" value="<?= htmlspecialchars($formData['tanggal'], ENT_QUOTES, 'UTF-8'); ?>" required class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-inner focus:border-primary focus:ring-primary" />
                </div>
                <div class="sm:col-span-1">
                    <label for="status" class="text-sm font-medium text-slate-700">Jenis Izin</label>
                    <select id="status" name="status" required class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-inner focus:border-primary focus:ring-primary">
                        <option value="" disabled <?= $formData['status'] === '' ? 'selected' : ''; ?>>Pilih status izin</option>
                        <?php foreach ($availableStatuses as $statusValue => $statusLabel): ?>
                            <option value="<?= htmlspecialchars($statusValue, ENT_QUOTES, 'UTF-8'); ?>" <?= ($formData['status'] === $statusValue) ? 'selected' : ''; ?>><?= htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label for="keterangan" class="text-sm font-medium text-slate-700">Keterangan Tambahan</label>
                    <textarea id="keterangan" name="keterangan" rows="3" placeholder="Contoh: Siswa mengalami demam tinggi sejak kemarin malam." class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/80 px-4 py-3 text-sm text-slate-900 shadow-inner focus:border-primary focus:ring-primary"><?= htmlspecialchars($formData['keterangan'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <div class="sm:col-span-2">
                    <label for="bukti" class="text-sm font-medium text-slate-700">Lampiran Bukti (JPG, PNG, PDF)</label>
                    <input type="file" id="bukti" name="bukti" accept=".jpg,.jpeg,.png,.pdf" required class="mt-2 block w-full rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-6 text-sm text-slate-500 focus:border-primary focus:ring-primary" />
                    <p class="mt-2 text-xs text-slate-400">Lampirkan surat dokter, surat izin, atau bukti pendukung lainnya maksimal ukuran 5 MB.</p>
                </div>
                <div class="sm:col-span-2 flex justify-end">
                    <button type="submit" class="inline-flex items-center rounded-2xl bg-primary px-6 py-3 text-sm font-semibold text-primary-foreground shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500">
                        Kirim Pengajuan Izin
                    </button>
                </div>
            </form>
        </div>

        <div id="riwayat" class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-xl shadow-indigo-500/5 backdrop-blur">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Riwayat Pengajuan Izin</h2>
                    <p class="text-sm text-slate-500">Pantau seluruh pengajuan izin yang pernah dikirim melalui portal ini.</p>
                </div>
            </div>
            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Keterangan</th>
                                <th class="px-4 py-3">Lampiran</th>
                                <th class="px-4 py-3">Diajukan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white/80 text-sm text-slate-600">
                            <?php if (count($izinHistory) === 0): ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-slate-400">Belum ada pengajuan izin yang tercatat.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($izinHistory as $izin): ?>
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-slate-700">
                                            <?php
                                            $formattedDate = $izin['tanggal'] ? date_create($izin['tanggal']) : null;
                                            echo $formattedDate ? htmlspecialchars($formattedDate->format('d F Y'), ENT_QUOTES, 'UTF-8') : '-';
                                            ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <?php
                                            $statusValue = $izin['status'] ?? '';
                                            $statusBadge = [
                                                'Sakit' => 'bg-rose-100 text-rose-600',
                                                'Izin' => 'bg-sky-100 text-sky-600',
                                                'Alpha' => 'bg-amber-100 text-amber-600',
                                                'Telat' => 'bg-amber-100 text-amber-600',
                                                '' => 'bg-emerald-100 text-emerald-600',
                                            ];
                                            $badgeClass = $statusBadge[$statusValue] ?? 'bg-slate-100 text-slate-600';
                                            ?>
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold <?= $badgeClass; ?>">
                                                <?= htmlspecialchars($statusValue !== '' ? $statusValue : 'Tepat Waktu', ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <?= $izin['keterangan'] !== null && $izin['keterangan'] !== '' ? nl2br(htmlspecialchars($izin['keterangan'], ENT_QUOTES, 'UTF-8')) : '<span class="text-slate-400">-</span>'; ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <?php if (!empty($izin['bukti_path'])): ?>
                                                <a href="../<?= htmlspecialchars($izin['bukti_path'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:text-indigo-600">
                                                    Lihat Bukti
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                                        <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V9a1 1 0 10-2 0v6H5V5h6a1 1 0 000-2H5zm7-1a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 11-2 0V4.414l-6.293 6.293a1 1 0 01-1.414-1.414L14.586 3H13a1 1 0 01-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-slate-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <?php
                                            $createdAt = $izin['updated_at'] ?? $izin['created_at'];
                                            $createdDateTime = $createdAt ? date_create($createdAt) : null;
                                            echo $createdDateTime ? htmlspecialchars($createdDateTime->format('d M Y H:i'), ENT_QUOTES, 'UTF-8') : '-';
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
