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

$statusMeta = [
    '' => [
        'label' => 'Hadir Tepat Waktu',
        'description' => 'Siswa hadir tepat waktu hari ini.',
        'icon' => '✅',
        'badge_class' => 'bg-emerald-100 text-emerald-600',
        'show_time' => true,
    ],
    'Hadir' => [
        'label' => 'Hadir Tepat Waktu',
        'description' => 'Siswa hadir tepat waktu hari ini.',
        'icon' => '✅',
        'badge_class' => 'bg-emerald-100 text-emerald-600',
        'show_time' => true,
    ],
    'Telat' => [
        'label' => 'Telat',
        'description' => 'Siswa tercatat telat hari ini.',
        'icon' => '⏰',
        'badge_class' => 'bg-amber-100 text-amber-600',
        'show_time' => true,
    ],
    'Sakit' => [
        'label' => 'Izin Sakit',
        'description' => 'Izin sakit telah diterima.',
        'icon' => '🤒',
        'badge_class' => 'bg-rose-100 text-rose-600',
        'show_time' => false,
    ],
    'Izin' => [
        'label' => 'Izin Orang Tua',
        'description' => 'Izin dari orang tua telah tercatat.',
        'icon' => '📝',
        'badge_class' => 'bg-sky-100 text-sky-600',
        'show_time' => false,
    ],
    'Alpha' => [
        'label' => 'Alpha',
        'description' => 'Siswa tidak hadir tanpa keterangan resmi.',
        'icon' => '⚠️',
        'badge_class' => 'bg-slate-200 text-slate-700',
        'show_time' => false,
    ],
];

$defaultStatusMeta = [
    'label' => 'Tercatat',
    'description' => 'Catatan absensi tersedia.',
    'icon' => '📘',
    'badge_class' => 'bg-slate-100 text-slate-600',
    'show_time' => true,
];

$today = date('Y-m-d');
$todayInfo = [
    'has_record' => false,
    'status' => null,
    'time' => null,
    'label' => 'Belum ada catatan',
    'description' => 'Belum ada catatan absen masuk hari ini.',
    'icon' => '🕒',
    'badge_class' => 'bg-slate-100 text-slate-600',
    'show_time' => false,
    'keterangan' => null,
    'bukti_path' => null,
];

$todayStmt = $koneksi->prepare('SELECT m.id, m.jam_masuk, m.status, oi.keterangan, oi.bukti_path FROM masuk m LEFT JOIN orangtua_izin oi ON oi.masuk_id = m.id WHERE m.nis = ? AND m.tanggal = ? LIMIT 1');
if ($todayStmt) {
    $todayStmt->bind_param('ss', $childNis, $today);
    $todayStmt->execute();
    $todayStmt->bind_result($todayMasukId, $todayTime, $todayStatus, $todayNotes, $todayEvidence);
    if ($todayStmt->fetch()) {
        $statusKey = $todayStatus ?? '';
        if (!array_key_exists($statusKey, $statusMeta)) {
            $statusKey = $todayStatus !== null ? $todayStatus : '';
        }

        $meta = $statusMeta[$statusKey] ?? $defaultStatusMeta;

        $todayInfo = [
            'has_record' => true,
            'status' => $todayStatus,
            'time' => $todayTime,
            'label' => $meta['label'],
            'description' => $meta['description'],
            'icon' => $meta['icon'],
            'badge_class' => $meta['badge_class'],
            'show_time' => $meta['show_time'],
            'keterangan' => $todayNotes,
            'bukti_path' => $todayEvidence,
        ];
    }
    $todayStmt->close();
}

$history = [];
$historyQuery = $koneksi->prepare(
    'SELECT d.tanggal,
            m.jam_masuk,
            m.status AS status_masuk,
            p.jam_pulang,
            p.status AS status_pulang,
            oi.keterangan AS izin_keterangan,
            oi.bukti_path AS izin_bukti
     FROM (
         SELECT tanggal FROM masuk WHERE nis = ?
         UNION
         SELECT tanggal FROM pulang WHERE nis = ?
     ) d
     LEFT JOIN masuk m ON m.nis = ? AND m.tanggal = d.tanggal
     LEFT JOIN pulang p ON p.nis = ? AND p.tanggal = d.tanggal
     LEFT JOIN orangtua_izin oi ON oi.masuk_id = m.id
     ORDER BY d.tanggal DESC'
);

if ($historyQuery) {
    $historyQuery->bind_param('ssss', $childNis, $childNis, $childNis, $childNis);
    $historyQuery->execute();
    $result = $historyQuery->get_result();

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $history[] = $row;
        }
    }

    $historyQuery->close();
}

$latestPermission = null;
$latestPermissionStmt = $koneksi->prepare(
    'SELECT m.tanggal, m.status, oi.keterangan, oi.bukti_path, oi.updated_at, oi.created_at
     FROM masuk m
     INNER JOIN orangtua_izin oi ON oi.masuk_id = m.id
     WHERE m.nis = ?
     ORDER BY m.tanggal DESC
     LIMIT 1'
);

if ($latestPermissionStmt) {
    $latestPermissionStmt->bind_param('s', $childNis);
    $latestPermissionStmt->execute();
    $latestPermissionResult = $latestPermissionStmt->get_result();
    if ($latestPermissionResult) {
        $latestPermission = $latestPermissionResult->fetch_assoc() ?: null;
    }
    $latestPermissionStmt->close();
}

$koneksi->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Orang Tua</title>
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

    <div class="mx-auto max-w-6xl px-6">
        <div class="mb-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Selamat datang,</p>
                <h1 class="text-3xl font-semibold text-slate-900"><?= htmlspecialchars($parentName, ENT_QUOTES, 'UTF-8'); ?></h1>
                <?php if ($student): ?>
                    <p class="mt-1 text-sm text-slate-500">Memantau: <span class="font-semibold text-slate-700"><?= htmlspecialchars($student['nama'], ENT_QUOTES, 'UTF-8'); ?></span> • Kelas <?= htmlspecialchars($student['kelas'], ENT_QUOTES, 'UTF-8'); ?> • NIS <?= htmlspecialchars($childNis, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php else: ?>
                    <p class="mt-1 text-sm text-rose-500">Data siswa tidak ditemukan. Mohon hubungi admin.</p>
                <?php endif; ?>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="dashboard.php" class="inline-flex items-center rounded-2xl border border-transparent bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-600">Dashboard</a>
                <a href="izin.php" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-primary hover:text-primary">Pengajuan Izin</a>
                <a href="logout.php" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-rose-300 hover:text-rose-600">Keluar</a>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-lg shadow-indigo-500/5 backdrop-blur">
                <p class="text-sm font-medium text-slate-500">Status Kehadiran Hari Ini</p>
                <div class="mt-3 flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold <?= htmlspecialchars($todayInfo['badge_class'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?= htmlspecialchars($todayInfo['label'], ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                        <p class="mt-3 text-sm text-slate-500">
                            <?= htmlspecialchars($todayInfo['description'], ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    </div>
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-primary/10">
                        <span class="text-xl font-semibold text-primary"><?= htmlspecialchars($todayInfo['icon'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>
                <?php if ($todayInfo['has_record']): ?>
                    <div class="mt-4 space-y-2 rounded-2xl border border-slate-100 bg-slate-50/80 p-4 text-sm text-slate-600">
                        <?php if ($todayInfo['show_time']): ?>
                            <p>
                                <?php if ($todayInfo['time']): ?>
                                    Absen pada pukul <span class="font-semibold text-slate-700"><?= htmlspecialchars($todayInfo['time'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php else: ?>
                                    Jam masuk belum tercatat.
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($todayInfo['keterangan']): ?>
                            <p><span class="font-medium text-slate-700">Catatan orang tua:</span> <?= nl2br(htmlspecialchars($todayInfo['keterangan'], ENT_QUOTES, 'UTF-8')); ?></p>
                        <?php endif; ?>
                        <?php if ($todayInfo['bukti_path']): ?>
                            <p>
                                <a href="../<?= htmlspecialchars($todayInfo['bukti_path'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="inline-flex items-center gap-1 font-semibold text-primary hover:text-indigo-600">
                                    Lihat bukti izin
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                        <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V9a1 1 0 10-2 0v6H5V5h6a1 1 0 000-2H5zm7-1a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 11-2 0V4.414l-6.293 6.293a1 1 0 01-1.414-1.414L14.586 3H13a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </p>
                        <?php endif; ?>
                        <?php if (!$todayInfo['show_time'] && !$todayInfo['keterangan'] && !$todayInfo['bukti_path']): ?>
                            <p>Catatan izin tersimpan pada sistem.</p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="mt-4 rounded-2xl border border-dashed border-slate-200 bg-white/80 p-4 text-sm text-slate-500">
                        Belum ada catatan absen masuk hari ini.
                    </div>
                <?php endif; ?>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-lg shadow-indigo-500/5 backdrop-blur">
                <p class="text-sm font-medium text-slate-500">Ringkasan Harian</p>
                <div class="mt-4 grid gap-4">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Tanggal</p>
                        <p class="mt-1 text-lg font-semibold text-slate-800"><?= htmlspecialchars(date('l, d F Y'), ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Status Masuk</p>
                        <p class="mt-1 text-lg font-semibold text-slate-800"><?= htmlspecialchars($todayInfo['label'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Catatan</p>
                        <p class="mt-1 text-sm text-slate-600">
                            <?php if ($todayInfo['has_record'] && $todayInfo['keterangan']): ?>
                                <?= nl2br(htmlspecialchars($todayInfo['keterangan'], ENT_QUOTES, 'UTF-8')); ?>
                            <?php elseif ($todayInfo['has_record']): ?>
                                <?= htmlspecialchars($todayInfo['description'], ENT_QUOTES, 'UTF-8'); ?>
                            <?php else: ?>
                                Belum ada catatan untuk hari ini.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-lg shadow-indigo-500/5 backdrop-blur">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Pengajuan Izin Terakhir</p>
                        <?php if ($latestPermission): ?>
                            <h2 class="mt-2 text-lg font-semibold text-slate-900">
                                <?php
                                $latestStatus = $latestPermission['status'] ?? '';
                                echo htmlspecialchars($statusMeta[$latestStatus]['label'] ?? ($latestStatus !== '' ? $latestStatus : 'Hadir Tepat Waktu'), ENT_QUOTES, 'UTF-8');
                                ?>
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                <?php
                                $latestDate = $latestPermission['tanggal'] ? date_create($latestPermission['tanggal']) : null;
                                echo $latestDate ? htmlspecialchars($latestDate->format('d F Y'), ENT_QUOTES, 'UTF-8') : '-';
                                ?>
                            </p>
                        <?php else: ?>
                            <h2 class="mt-2 text-lg font-semibold text-slate-900">Belum ada pengajuan</h2>
                            <p class="mt-1 text-sm text-slate-500">Ajukan izin jika siswa berhalangan hadir.</p>
                        <?php endif; ?>
                    </div>
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary/10">
                        <span class="text-lg font-semibold text-primary">🗂️</span>
                    </div>
                </div>
                <div class="mt-4 space-y-3 text-sm text-slate-600">
                    <?php if ($latestPermission && ($latestPermission['keterangan'] ?? '') !== ''): ?>
                        <p><span class="font-medium text-slate-700">Keterangan:</span> <?= nl2br(htmlspecialchars($latestPermission['keterangan'], ENT_QUOTES, 'UTF-8')); ?></p>
                    <?php endif; ?>
                    <?php if ($latestPermission && ($latestPermission['bukti_path'] ?? '') !== ''): ?>
                        <p>
                            <a href="../<?= htmlspecialchars($latestPermission['bukti_path'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="inline-flex items-center gap-1 font-semibold text-primary hover:text-indigo-600">
                                Lihat bukti terakhir
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                    <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V9a1 1 0 10-2 0v6H5V5h6a1 1 0 000-2H5zm7-1a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 11-2 0V4.414l-6.293 6.293a1 1 0 01-1.414-1.414L14.586 3H13a1 1 0 01-1-1z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </p>
                    <?php endif; ?>
                    <?php if ($latestPermission): ?>
                        <?php
                        $updatedAt = $latestPermission['updated_at'] ?: $latestPermission['created_at'];
                        $updatedDate = $updatedAt ? date_create($updatedAt) : null;
                        ?>
                        <p class="text-xs text-slate-400">Diperbarui pada <?= $updatedDate ? htmlspecialchars($updatedDate->format('d M Y H:i'), ENT_QUOTES, 'UTF-8') : '-'; ?></p>
                    <?php endif; ?>
                </div>
                <div class="mt-6 flex flex-wrap gap-2">
                    <a href="izin.php" class="inline-flex items-center rounded-2xl bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-600">Ajukan Izin</a>
                    <a href="izin.php#riwayat" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-primary hover:text-primary">Lihat Riwayat</a>
                </div>
            </div>
        </div>

        <div id="riwayat" class="mt-8 rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-xl shadow-indigo-500/5 backdrop-blur">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Riwayat Absensi</h2>
                    <p class="text-sm text-slate-500">Pantau catatan hadir masuk dan pulang siswa beserta izin yang diajukan.</p>
                </div>
            </div>
            <div class="mt-6 overflow-hidden rounded-2xl border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/80 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Jam Masuk</th>
                                <th class="px-4 py-3">Status Masuk</th>
                                <th class="px-4 py-3">Jam Pulang</th>
                                <th class="px-4 py-3">Status Pulang</th>
                                <th class="px-4 py-3">Catatan Izin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white/80 text-sm text-slate-600">
                            <?php if (count($history) === 0): ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-slate-400">Belum ada data absensi untuk siswa ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($history as $row): ?>
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-slate-700">
                                            <?php
                                            $formattedDate = $row['tanggal'] ? date_create($row['tanggal']) : null;
                                            echo $formattedDate ? htmlspecialchars($formattedDate->format('d F Y'), ENT_QUOTES, 'UTF-8') : '-';
                                            ?>
                                        </td>
                                        <td class="px-4 py-3 text-slate-700"><?= $row['jam_masuk'] ? htmlspecialchars($row['jam_masuk'], ENT_QUOTES, 'UTF-8') : '-'; ?></td>
                                        <td class="px-4 py-3">
                                            <?php
                                            $statusValue = $row['status_masuk'] ?? '';
                                            $statusBadgeMap = [
                                                '' => ['label' => 'Tepat Waktu', 'class' => 'bg-emerald-100 text-emerald-600'],
                                                'Hadir' => ['label' => 'Tepat Waktu', 'class' => 'bg-emerald-100 text-emerald-600'],
                                                'Telat' => ['label' => 'Telat', 'class' => 'bg-amber-100 text-amber-600'],
                                                'Sakit' => ['label' => 'Sakit', 'class' => 'bg-rose-100 text-rose-600'],
                                                'Izin' => ['label' => 'Izin', 'class' => 'bg-sky-100 text-sky-600'],
                                                'Alpha' => ['label' => 'Alpha', 'class' => 'bg-slate-200 text-slate-700'],
                                            ];
                                            $meta = $statusBadgeMap[$statusValue] ?? ['label' => ($statusValue !== '' ? $statusValue : 'Tercatat'), 'class' => 'bg-slate-100 text-slate-600'];
                                            ?>
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold <?= htmlspecialchars($meta['class'], ENT_QUOTES, 'UTF-8'); ?>">
                                                <?= htmlspecialchars($meta['label'], ENT_QUOTES, 'UTF-8'); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-slate-700"><?= $row['jam_pulang'] ? htmlspecialchars($row['jam_pulang'], ENT_QUOTES, 'UTF-8') : '-'; ?></td>
                                        <td class="px-4 py-3">
                                            <?php
                                            if (!$row['jam_pulang']) {
                                                echo '<span class="text-slate-400">-</span>';
                                            } else {
                                                $statusPulang = $row['status_pulang'] !== '' ? $row['status_pulang'] : 'Tercatat';
                                                echo '<span class="font-medium text-slate-700">' . htmlspecialchars($statusPulang, ENT_QUOTES, 'UTF-8') . '</span>';
                                            }
                                            ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <?php if (!empty($row['izin_keterangan']) || !empty($row['izin_bukti'])): ?>
                                                <?php if (!empty($row['izin_keterangan'])): ?>
                                                    <p><?= nl2br(htmlspecialchars($row['izin_keterangan'], ENT_QUOTES, 'UTF-8')); ?></p>
                                                <?php endif; ?>
                                                <?php if (!empty($row['izin_bukti'])): ?>
                                                    <a href="../<?= htmlspecialchars($row['izin_bukti'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="mt-1 inline-flex items-center gap-1 text-sm font-semibold text-primary hover:text-indigo-600">
                                                        Lihat bukti
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                                            <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V9a1 1 0 10-2 0v6H5V5h6a1 1 0 000-2H5zm7-1a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 11-2 0V4.414l-6.293 6.293a1 1 0 01-1.414-1.414L14.586 3H13a1 1 0 01-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-slate-400">-</span>
                                            <?php endif; ?>
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
