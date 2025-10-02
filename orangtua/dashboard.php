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

$today = date('Y-m-d');
$todayInfo = [
    'is_present' => false,
    'is_late' => false,
    'time' => null,
];

$todayStmt = $koneksi->prepare('SELECT jam_masuk, status FROM masuk WHERE nis = ? AND tanggal = ? LIMIT 1');
if ($todayStmt) {
    $todayStmt->bind_param('ss', $childNis, $today);
    $todayStmt->execute();
    $todayStmt->bind_result($todayTime, $todayStatus);
    if ($todayStmt->fetch()) {
        $todayInfo['is_present'] = true;
        $todayInfo['time'] = $todayTime;
        $todayInfo['is_late'] = ($todayStatus === 'Telat');
        $todayInfo['status_text'] = $todayStatus === 'Telat' ? 'Telat' : 'Tepat waktu';
    } else {
        $todayInfo['status_text'] = 'Belum absen';
    }
    $todayStmt->close();
} else {
    $todayInfo['status_text'] = 'Belum absen';
}

$history = [];
$historyQuery = $koneksi->prepare(
    'SELECT d.tanggal, m.jam_masuk, m.status AS status_masuk, p.jam_pulang, p.status AS status_pulang
    FROM (
        SELECT tanggal FROM masuk WHERE nis = ?
        UNION
        SELECT tanggal FROM pulang WHERE nis = ?
    ) d
    LEFT JOIN masuk m ON m.nis = ? AND m.tanggal = d.tanggal
    LEFT JOIN pulang p ON p.nis = ? AND p.tanggal = d.tanggal
    ORDER BY d.tanggal DESC'
);

if ($historyQuery) {
    $historyQuery->bind_param('ssss', $childNis, $childNis, $childNis, $childNis);
    $historyQuery->execute();
    $result = $historyQuery->get_result();

    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
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
        <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Selamat datang,</p>
                <h1 class="text-3xl font-semibold text-slate-900"><?= htmlspecialchars($parentName, ENT_QUOTES, 'UTF-8'); ?></h1>
                <?php if ($student): ?>
                    <p class="mt-1 text-sm text-slate-500">Memantau: <span class="font-semibold text-slate-700"><?= htmlspecialchars($student['nama'], ENT_QUOTES, 'UTF-8'); ?></span> • Kelas <?= htmlspecialchars($student['kelas'], ENT_QUOTES, 'UTF-8'); ?> • NIS <?= htmlspecialchars($childNis, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php else: ?>
                    <p class="mt-1 text-sm text-rose-500">Data siswa tidak ditemukan. Mohon hubungi admin.</p>
                <?php endif; ?>
            </div>
            <a href="logout.php" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-rose-300 hover:text-rose-600">Keluar</a>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-lg shadow-indigo-500/5 backdrop-blur">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Status Kehadiran Hari Ini</p>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900">
                            <?php if ($todayInfo['is_present']): ?>
                                <?= htmlspecialchars($todayInfo['status_text'], ENT_QUOTES, 'UTF-8'); ?>
                            <?php else: ?>
                                Belum absen
                            <?php endif; ?>
                        </h2>
                        <p class="mt-2 text-sm text-slate-500">
                            <?php if ($todayInfo['is_present']): ?>
                                Absen pada pukul <span class="font-semibold text-slate-700"><?= htmlspecialchars($todayInfo['time'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php else: ?>
                                Belum ada catatan absen masuk hari ini.
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary/10">
                        <span class="text-xl font-semibold text-primary"><?= $todayInfo['is_present'] ? ($todayInfo['is_late'] ? '⏰' : '✅') : '🕒'; ?></span>
                    </div>
                </div>
                <?php if ($todayInfo['is_present']): ?>
                    <div class="mt-4 rounded-2xl border border-slate-100 bg-slate-50/80 p-4 text-sm text-slate-600">
                        <p><?= $todayInfo['is_late'] ? 'Siswa tercatat telat hari ini.' : 'Siswa hadir tepat waktu hari ini.'; ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="lg:col-span-2 rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-lg shadow-indigo-500/5 backdrop-blur">
                <p class="text-sm font-medium text-slate-500">Ringkasan Cepat</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Tanggal</p>
                        <p class="mt-1 text-lg font-semibold text-slate-800"><?= htmlspecialchars(date('l, d F Y'), ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Status</p>
                        <p class="mt-1 text-lg font-semibold text-slate-800"><?= $todayInfo['is_present'] ? htmlspecialchars($todayInfo['status_text'], ENT_QUOTES, 'UTF-8') : 'Belum absen'; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 rounded-3xl border border-slate-200 bg-white/90 p-6 shadow-xl shadow-indigo-500/5 backdrop-blur">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-slate-900">Riwayat Absensi</h2>
                    <p class="text-sm text-slate-500">Pantau catatan hadir masuk dan pulang siswa.</p>
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
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white/80 text-sm text-slate-600">
                            <?php if (count($history) === 0): ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-slate-400">Belum ada data absensi untuk siswa ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($history as $row): ?>
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-slate-700"><?php
                                            $formattedDate = $row['tanggal'] ? date_create($row['tanggal']) : null;
                                            echo $formattedDate ? htmlspecialchars($formattedDate->format('d F Y'), ENT_QUOTES, 'UTF-8') : '-';
                                        ?></td>
                                        <td class="px-4 py-3 text-slate-700"><?= $row['jam_masuk'] ? htmlspecialchars($row['jam_masuk'], ENT_QUOTES, 'UTF-8') : '-'; ?></td>
                                        <td class="px-4 py-3">
                                            <?php
                                            if (!$row['jam_masuk']) {
                                                echo '<span class="text-slate-400">-</span>';
                                            } else {
                                                $statusMasuk = $row['status_masuk'] === 'Telat' ? 'Telat' : 'Tepat waktu';
                                                $statusColor = $row['status_masuk'] === 'Telat' ? 'text-rose-500' : 'text-emerald-600';
                                                echo '<span class="font-medium ' . $statusColor . '">' . htmlspecialchars($statusMasuk, ENT_QUOTES, 'UTF-8') . '</span>';
                                            }
                                            ?>
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
