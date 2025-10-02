<?php
session_start();

if (!isset($_SESSION['nis'])) {
    header('Location: ../index.php');
    exit();
}

$nis = $_SESSION['nis'];

require_once __DIR__ . '/../koneksi.php';

$barcodeConfig = require __DIR__ . '/../barcode_config.php';
$generalBarcodes = [
    'masuk' => is_array($barcodeConfig) && isset($barcodeConfig['masuk']) ? (string) $barcodeConfig['masuk'] : 'ABSENSI-MASUK',
    'pulang' => is_array($barcodeConfig) && isset($barcodeConfig['pulang']) ? (string) $barcodeConfig['pulang'] : 'ABSENSI-PULANG',
];

$feedback = $_SESSION['absen_feedback'] ?? null;
$activeTab = 'masuk';
$feedbackMode = null;
$feedbackMessage = '';
$feedbackSuccess = null;

if ($feedback) {
    $feedbackMode = $feedback['mode'] ?? null;
    $feedbackMessage = $feedback['message'] ?? '';
    $feedbackSuccess = $feedback['success'] ?? false;
    if (!empty($feedbackMode)) {
        $activeTab = $feedbackMode;
    }
    unset($_SESSION['absen_feedback']);
}

$studentName = 'Siswa';
$studentClass = '';
$studentStmt = $koneksi->prepare('SELECT nama, kelas FROM data_siswa WHERE nis = ? LIMIT 1');
if ($studentStmt) {
    $studentStmt->bind_param('s', $nis);
    $studentStmt->execute();
    $studentStmt->bind_result($namaResult, $kelasResult);
    if ($studentStmt->fetch()) {
        $studentName = $namaResult !== null ? $namaResult : $studentName;
        $studentClass = $kelasResult !== null ? $kelasResult : $studentClass;
    }
    $studentStmt->close();
}

$historyRows = [];
$locationBuckets = [];

$historyQuery = $koneksi->prepare(
    "(SELECT 'Masuk' AS jenis, tanggal, jam_masuk AS waktu, status, latitude, longitude FROM masuk WHERE nis = ?)
    UNION ALL
    (SELECT 'Pulang' AS jenis, tanggal, jam_pulang AS waktu, status, latitude, longitude FROM pulang WHERE nis = ?)
    ORDER BY tanggal DESC, waktu DESC
    LIMIT 40"
);

if ($historyQuery) {
    $historyQuery->bind_param('ss', $nis, $nis);
    $historyQuery->execute();
    $historyQuery->bind_result($jenis, $tanggal, $waktu, $status, $latitude, $longitude);

    while ($historyQuery->fetch()) {
        $tanggalValue = $tanggal ?? '';
        $tanggalTimestamp = $tanggalValue !== '' ? strtotime($tanggalValue) : false;
        $tanggalTimestamp = $tanggalTimestamp !== false ? $tanggalTimestamp : null;
        $tanggalDisplay = $tanggalTimestamp !== null ? date('d M Y', $tanggalTimestamp) : '—';
        $dateKey = $tanggalTimestamp !== null ? date('Y-m-d', $tanggalTimestamp) : null;

        $waktuValue = $waktu ?? '';
        $waktuTimestamp = $waktuValue !== '' ? strtotime($waktuValue) : false;
        $waktuTimestamp = $waktuTimestamp !== false ? $waktuTimestamp : null;
        $waktuDisplay = $waktuTimestamp !== null ? date('H:i', $waktuTimestamp) : '—';
        $timeKey = $waktuTimestamp !== null ? date('H:i:s', $waktuTimestamp) : null;

        $statusDisplay = isset($status) && $status !== '' ? $status : '—';

        $latValue = $latitude !== null ? (float) $latitude : null;
        $lngValue = $longitude !== null ? (float) $longitude : null;
        $coordinateDisplay = ($latValue !== null && $lngValue !== null)
            ? number_format($latValue, 5, '.', '') . ', ' . number_format($lngValue, 5, '.', '')
            : '—';

        $historyRows[] = [
            'jenis' => $jenis,
            'tanggal' => $tanggalDisplay,
            'waktu' => $waktuDisplay,
            'status' => $statusDisplay,
            'koordinat' => $coordinateDisplay,
        ];

        if ($latValue !== null && $lngValue !== null) {
            $key = sprintf('%.8f,%.8f', $latValue, $lngValue);
            if (!isset($locationBuckets[$key])) {
                $locationBuckets[$key] = [
                    'latitude' => $latValue,
                    'longitude' => $lngValue,
                    'count' => 0,
                    'entries' => [],
                    'last_used' => null,
                ];
            }

            $locationBuckets[$key]['count']++;

            $entryLabelParts = array_filter([
                $jenis,
                $tanggalDisplay !== '—' ? $tanggalDisplay : null,
                $waktuDisplay !== '—' ? $waktuDisplay : null,
            ]);
            $locationBuckets[$key]['entries'][] = implode(' • ', $entryLabelParts);

            $combinedTimestamp = null;
            if ($dateKey !== null) {
                if ($timeKey !== null) {
                    $combinedTimestamp = strtotime($dateKey . ' ' . $timeKey);
                } else {
                    $combinedTimestamp = strtotime($dateKey);
                }
            }

            if ($combinedTimestamp !== null) {
                $previous = $locationBuckets[$key]['last_used'];
                if ($previous === null || $combinedTimestamp > $previous) {
                    $locationBuckets[$key]['last_used'] = $combinedTimestamp;
                }
            }
        }
    }

    $historyQuery->close();
}

$koneksi->close();

$locationSummary = array_values($locationBuckets);
usort($locationSummary, static function ($a, $b) {
    if ($a['count'] === $b['count']) {
        return ($b['last_used'] ?? 0) <=> ($a['last_used'] ?? 0);
    }
    return $b['count'] <=> $a['count'];
});

foreach ($locationSummary as &$locationItem) {
    $locationItem['latitude_display'] = number_format($locationItem['latitude'], 5, '.', '');
    $locationItem['longitude_display'] = number_format($locationItem['longitude'], 5, '.', '');
    $locationItem['last_used_display'] = $locationItem['last_used'] !== null
        ? date('d M Y H:i', $locationItem['last_used'])
        : null;
    if (count($locationItem['entries']) > 6) {
        $locationItem['entries'] = array_slice($locationItem['entries'], 0, 6);
    }
}
unset($locationItem);

$locationMarkers = array_map(static function ($location) {
    return [
        'latitude' => $location['latitude'],
        'longitude' => $location['longitude'],
        'count' => $location['count'],
        'entries' => $location['entries'],
        'lastUsed' => $location['last_used_display'],
    ];
}, $locationSummary);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Siswa</title>
    <link rel="icon" href="../assets/img/smkmadya.png">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
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
                            foreground: '#F5F3FF'
                        }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-1Aon9LV8CC3aF6a0xkY4kG3E62rAgQPe31mSZY+4CUM=" crossorigin="">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js" integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-Sm1jHPIHy4m3qsQm7n3GxudsnIn5IJZG2ol9u5p6dGI=" crossorigin=""></script>
    <style>
        #absen-map {
            height: 20rem;
        }

        .leaflet-control-attribution {
            font-size: 11px;
        }
    </style>
</head>

<body class="font-display bg-slate-950/[0.02] text-slate-900">
    <div class="min-h-screen">
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
                <div>
                    <p class="text-sm text-slate-500">Selamat datang kembali,</p>
                    <h1 class="text-xl font-semibold text-slate-900"><?= htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?></h1>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <p class="text-sm font-medium text-slate-700">NIS: <?= htmlspecialchars($nis, ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php if ($studentClass !== ''): ?>
                            <p class="text-xs text-slate-500">Kelas <?= htmlspecialchars($studentClass, ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php endif; ?>
                    </div>
                    <a href="../logout.php" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-slate-900/10 transition hover:bg-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9l3 3m0 0-3 3m3-3H3" />
                        </svg>
                        Keluar
                    </a>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl space-y-10 px-6 py-10">
            <section aria-label="Navigasi cepat" class="flex flex-wrap gap-3">
                <a href="#scan-section" class="group inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-primary hover:text-primary">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary">1</span>
                    Absensi Mandiri
                </a>
                <a href="#history-section" class="group inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-primary hover:text-primary">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary">2</span>
                    Riwayat Absen
                </a>
                <a href="#location-section" class="group inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-primary hover:text-primary">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-primary">3</span>
                    Peta Lokasi Absen
                </a>
            </section>

            <section id="scan-section" class="grid gap-6 lg:grid-cols-12">
                <div class="lg:col-span-7 space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-xl shadow-indigo-500/5">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Absensi Siswa</h2>
                                <p class="text-sm text-slate-500">Pindai barcode masuk atau pulang untuk merekam kehadiran Anda.</p>
                            </div>
                            <div class="flex gap-2 rounded-full bg-slate-100 p-1 text-xs font-medium text-slate-500">
                                <button type="button" data-tab-button="masuk" class="rounded-full px-4 py-2 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500">
                                    Absen Masuk
                                </button>
                                <button type="button" data-tab-button="pulang" class="rounded-full px-4 py-2 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500">
                                    Absen Pulang
                                </button>
                            </div>
                        </div>
                        <div class="mt-6 space-y-6">
                            <div data-tab-panel="masuk" class="md:space-y-5">
                                <div id="reader-masuk" class="md:flex md:h-72 items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white text-sm text-slate-400"></div>
                                <div id="result-masuk" class="space-y-4"></div>
                            </div>
                            <div data-tab-panel="pulang" class="hidden space-y-5">
                                <div id="reader-pulang" class="md:flex md:h-72 items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-white text-sm text-slate-400"></div>
                                <div id="result-pulang" class="space-y-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-5 space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-lg shadow-indigo-500/5">
                        <h3 class="text-base font-semibold text-slate-900">Status Lokasi</h3>
                        <p class="mt-2 text-sm text-slate-500">Pastikan GPS aktif dan izinkan akses lokasi agar absensi dapat dikirim dengan benar.</p>
                        <dl class="mt-5 space-y-3 text-sm">
                            <div class="flex items-center justify-between rounded-2xl bg-slate-100/60 px-4 py-3">
                                <dt class="font-medium text-slate-600">Latitude</dt>
                                <dd class="font-semibold text-slate-900" data-location-lat>Memuat lokasi...</dd>
                            </div>
                            <div class="flex items-center justify-between rounded-2xl bg-slate-100/60 px-4 py-3">
                                <dt class="font-medium text-slate-600">Longitude</dt>
                                <dd class="font-semibold text-slate-900" data-location-lng>Memuat lokasi...</dd>
                            </div>
                        </dl>
                        <p id="location-status" class="mt-4 hidden rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700"></p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-lg shadow-indigo-500/5">
                        <h3 class="text-base font-semibold text-slate-900">Tips pemindaian</h3>
                        <ul class="mt-4 space-y-3 text-sm text-slate-600">
                            <li class="flex gap-3">
                                <span class="mt-1 h-2 w-2 rounded-full bg-primary"></span>
                                Pastikan barcode berada dalam area pemindaian dan pencahayaan cukup.
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 h-2 w-2 rounded-full bg-primary"></span>
                                Gunakan barcode pribadi atau barcode umum sesuai jenis absensi.
                            </li>
                            <li class="flex gap-3">
                                <span class="mt-1 h-2 w-2 rounded-full bg-primary"></span>
                                Setelah pemindaian sukses, sistem akan otomatis mengirim data ke server.
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            <section id="history-section" class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-xl shadow-indigo-500/5">
                <div class="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Riwayat Absen</h2>
                        <p class="text-sm text-slate-500">Pantau catatan absensi Anda hingga 40 data terakhir.</p>
                    </div>
                </div>
                <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <tr>
                                <th scope="col" class="px-4 py-3">Jenis</th>
                                <th scope="col" class="px-4 py-3">Tanggal</th>
                                <th scope="col" class="px-4 py-3">Waktu</th>
                                <th scope="col" class="px-4 py-3">Status</th>
                                <th scope="col" class="px-4 py-3">Koordinat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <?php if (count($historyRows) === 0): ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">Belum ada data absensi untuk ditampilkan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($historyRows as $row): ?>
                                    <tr class="hover:bg-slate-50/80">
                                        <td class="px-4 py-4 font-medium text-slate-700"><?= htmlspecialchars($row['jenis'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="px-4 py-4 text-slate-600"><?= htmlspecialchars($row['tanggal'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="px-4 py-4 text-slate-600"><?= htmlspecialchars($row['waktu'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td class="px-4 py-4">
                                            <?php if ($row['status'] !== '—'): ?>
                                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                                    <?= htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8'); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-slate-400">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-4 text-slate-600"><?= htmlspecialchars($row['koordinat'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="location-section" class="grid gap-6 lg:grid-cols-12">
                <div class="lg:col-span-7 space-y-4">
                    <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-xl shadow-indigo-500/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">Peta Lokasi Absen</h2>
                                <p class="text-sm text-slate-500">Lihat lokasi yang pernah digunakan saat melakukan absensi.</p>
                            </div>
                        </div>
                        <div class="relative mt-6 overflow-hidden rounded-2xl border border-slate-200">
                            <div id="absen-map"></div>
                            <div id="map-empty-state" class="absolute inset-0 hidden items-center justify-center bg-white/90 text-sm text-slate-500">
                                Belum ada data lokasi untuk ditampilkan.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-5 space-y-4">
                    <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-lg shadow-indigo-500/5">
                        <h3 class="text-base font-semibold text-slate-900">Ringkasan Lokasi Teratas</h3>
                        <p class="mt-2 text-sm text-slate-500">Periksa lokasi yang paling sering digunakan untuk absen.</p>
                        <div class="mt-5 space-y-4">
                            <?php if (count($locationSummary) === 0): ?>
                                <p class="text-sm text-slate-500">Belum ada koordinat lokasi yang tersimpan.</p>
                            <?php else: ?>
                                <ul class="space-y-4">
                                    <?php foreach ($locationSummary as $index => $location): ?>
                                        <li class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-800">Lokasi <?= $index + 1; ?></p>
                                                    <p class="text-xs text-slate-500"><?= htmlspecialchars($location['latitude_display'], ENT_QUOTES, 'UTF-8'); ?>, <?= htmlspecialchars($location['longitude_display'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                    <?php if (!empty($location['last_used_display'])): ?>
                                                        <p class="mt-2 text-xs text-slate-400">Terakhir digunakan: <?= htmlspecialchars($location['last_used_display'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary"><?= htmlspecialchars((string) $location['count'], ENT_QUOTES, 'UTF-8'); ?>x</span>
                                            </div>
                                            <?php if (!empty($location['entries'])): ?>
                                                <ul class="mt-3 space-y-1 text-xs text-slate-500">
                                                    <?php foreach ($location['entries'] as $entry): ?>
                                                        <li class="flex gap-2">
                                                            <span class="mt-1 h-1.5 w-1.5 rounded-full bg-primary/60"></span>
                                                            <span><?= htmlspecialchars($entry, ENT_QUOTES, 'UTF-8'); ?></span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        const sessionNis = "<?= htmlspecialchars($nis, ENT_QUOTES, 'UTF-8'); ?>";
        const generalBarcodes = <?php echo json_encode($generalBarcodes, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
        const defaultMode = "<?= htmlspecialchars($activeTab, ENT_QUOTES, 'UTF-8'); ?>";
        const readerIds = { masuk: 'reader-masuk', pulang: 'reader-pulang' };
        const resultContainers = {};
        let activeScanner = null;
        let activeMode = null;

        const scannerConfig = {
            qrbox: {
                width: 250,
                height: 250,
            },
            fps: 15,
        };

        const feedbackData = <?php echo json_encode([
            'mode' => $feedbackMode,
            'message' => $feedbackMessage,
            'success' => $feedbackSuccess,
        ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

        if (feedbackData && typeof feedbackData.message === 'string' && feedbackData.message !== '') {
                const icon = feedbackData.success ? 'success' : 'error';
                const title = feedbackData.success ? 'Absen Berhasil' : 'Absen Gagal';
                Swal.fire({
                    icon,
                    title,
                    text: feedbackData.message,
                });
            }
            console.log(feedbackData)

        const locationState = {
            latitude: null,
            longitude: null,
            error: null,
            watcherId: null,
        };

        const locationMarkers = <?php echo json_encode($locationMarkers, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

        let locationAlertShown = false;

        function formatCoordinate(value) {
            if (typeof value !== 'number' || Number.isNaN(value)) {
                return '';
            }
            return value.toFixed(8);
        }

        function stopScanner() {
            if (activeScanner) {
                activeScanner.clear().catch(() => {
                    // abaikan error ketika menghentikan pemindaian
                });
                activeScanner = null;
            }
            Object.values(readerIds).forEach((elementId) => {
                const element = document.getElementById(elementId);
                if (element) {
                    element.innerHTML = '';
                }
            });
            activeMode = null;
        }

        function updateDisplayedLocation() {
            const latText = locationState.latitude !== null ? formatCoordinate(locationState.latitude) : 'Memuat lokasi...';
            const lngText = locationState.longitude !== null ? formatCoordinate(locationState.longitude) : 'Memuat lokasi...';
            document.querySelectorAll('[data-location-lat]').forEach((element) => {
                element.textContent = latText;
            });
            document.querySelectorAll('[data-location-lng]').forEach((element) => {
                element.textContent = lngText;
            });
        }

        function updateLocationStatusMessage(message) {
            const statusElement = document.getElementById('location-status');
            if (!statusElement) {
                return;
            }
            if (message && message !== '') {
                statusElement.textContent = message;
                statusElement.classList.remove('hidden');
            } else {
                statusElement.textContent = '';
                statusElement.classList.add('hidden');
            }
        }

        function applyLocationToForm(mode) {
            const form = document.getElementById(`form-${mode}`);
            if (!form) {
                return false;
            }

            if (locationState.latitude === null || locationState.longitude === null) {
                updateDisplayedLocation();
                return false;
            }

            const latValue = formatCoordinate(locationState.latitude);
            const lngValue = formatCoordinate(locationState.longitude);

            const latInput = form.querySelector('input[name="latitude"]');
            const lngInput = form.querySelector('input[name="longitude"]');

            if (latInput) {
                latInput.value = latValue;
            }
            if (lngInput) {
                lngInput.value = lngValue;
            }

            updateDisplayedLocation();
            return true;
        }

        function showMessage(mode, type, message) {
            const container = resultContainers[mode];
            const sanitizedMessage = escapeHtml(message);
            if (container) {
                const classMap = {
                    success: 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-100',
                    warning: 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-100',
                    error: 'bg-rose-50 text-rose-700 ring-1 ring-inset ring-rose-100',
                };
                const alertClass = classMap[type] || 'bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-200';
                container.innerHTML = `
                    <div class="rounded-2xl px-4 py-3 text-sm font-medium ${alertClass}">
                        ${sanitizedMessage}
                    </div>
                `;
            }
            const iconMap = {
                success: 'success',
                warning: 'warning',
                error: 'error',
            };
            const icon = iconMap[type] || 'info';
            const title = icon === 'success' ? 'Berhasil' : icon === 'warning' ? 'Perhatian' : 'Gagal';
            Swal.fire({
                icon,
                title,
                text: message,
            });
        }

        function escapeHtml(value) {
            return String(value).replace(/[&<>"']/g, (match) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;',
            })[match]);
        }

        function buildCard(mode, nisValue, scannedText) {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');

            const today = `${year}-${month}-${day}`;
            const time = `${hours}:${minutes}:${seconds}`;
            const validatorEndpoint = mode === 'masuk' ? 'validator.php' : 'validator_plg.php';
            const sanitizedNis = escapeHtml(nisValue);
            const sanitizedScan = escapeHtml(scannedText);
            const usingGeneralBarcode = nisValue !== scannedText;
            const latText = locationState.latitude !== null ? formatCoordinate(locationState.latitude) : 'Memuat lokasi...';
            const lngText = locationState.longitude !== null ? formatCoordinate(locationState.longitude) : 'Memuat lokasi...';
            const safeLatText = escapeHtml(latText);
            const safeLngText = escapeHtml(lngText);
            const title = mode === 'masuk' ? 'Konfirmasi Absen Masuk' : 'Konfirmasi Absen Pulang';

            return `
                <form id="form-${mode}" action="${validatorEndpoint}" method="post" class="block">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg shadow-slate-900/5">
                        <div class="bg-slate-900/5 px-6 py-4">
                            <p class="text-sm font-semibold text-slate-800">${title}</p>
                            <p class="mt-1 text-xs text-slate-500">Pastikan data berikut sudah benar sebelum dilanjutkan.</p>
                        </div>
                        <div class="px-6 py-5 space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Data Barcode</span>
                                <span class="font-semibold text-slate-900">${sanitizedScan}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">NIS terdeteksi</span>
                                <span class="font-semibold text-slate-900">${sanitizedNis}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Tanggal</span>
                                <span class="font-medium text-slate-900">${today}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Waktu</span>
                                <span class="font-medium text-slate-900">${time}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Latitude</span>
                                <span class="font-medium text-slate-900" data-location-lat>${safeLatText}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Longitude</span>
                                <span class="font-medium text-slate-900" data-location-lng>${safeLngText}</span>
                            </div>
                        </div>
                        ${usingGeneralBarcode ? `<div class="bg-indigo-50 px-6 py-3 text-xs text-indigo-600">Barcode umum terdeteksi. Sistem menggunakan NIS akun Anda.</div>` : ''}
                    </div>
                    <input type="hidden" name="nis" value="${sanitizedNis}">
                    <input type="hidden" name="raw_code" value="${sanitizedScan}">
                    <input type="hidden" name="time_val" value="${time}">
                    <input type="hidden" name="date_val" value="${today}">
                    <input type="hidden" name="mode" value="${mode}">
                    <input type="hidden" name="latitude" value="">
                    <input type="hidden" name="longitude" value="">
                    <input type="submit" value="Submit" style="display: none;">
                </form>
            `;
        }

        function handleScanSuccess(decodedText, decodedResult, mode) {
            const container = resultContainers[mode];
            if (!container) {
                return;
            }

            const trimmedText = String(decodedText).trim();
            const personalBarcode = trimmedText === sessionNis;
            const modeBarcode = typeof generalBarcodes[mode] === 'string' ? generalBarcodes[mode] : null;
            const otherMode = mode === 'masuk' ? 'pulang' : 'masuk';
            const otherModeBarcode = typeof generalBarcodes[otherMode] === 'string' ? generalBarcodes[otherMode] : null;
            const generalBarcode = modeBarcode !== null && trimmedText === modeBarcode;
            const otherGeneralBarcode = otherModeBarcode !== null && trimmedText === otherModeBarcode;

            if (!personalBarcode && !generalBarcode) {
                if (otherGeneralBarcode) {
                    showMessage(mode, 'error', `Barcode ini digunakan untuk absen ${otherMode}. Silakan pindai pada tab ${otherMode}.`);
                } else {
                    showMessage(mode, 'error', 'Barcode tidak dikenali. Gunakan barcode akun Anda atau barcode umum sesuai jenis absensi.');
                }
                return;
            }

            if (locationState.latitude === null || locationState.longitude === null) {
                const warningText = locationState.error || 'Aplikasi belum memperoleh lokasi Anda. Pastikan GPS aktif dan izinkan akses lokasi sebelum melakukan absensi.';
                Swal.fire({
                    icon: 'warning',
                    title: 'Lokasi diperlukan',
                    text: warningText,
                });
                container.innerHTML = '';
                requestLocationPermission();
                return;
            }

            container.innerHTML = buildCard(mode, sessionNis, trimmedText);

            const locationReady = applyLocationToForm(mode);
            if (!locationReady) {
                const warningText = locationState.error || 'Aplikasi belum memperoleh lokasi Anda. Pastikan GPS aktif sebelum melakukan absensi.';
                Swal.fire({
                    icon: 'warning',
                    title: 'Lokasi diperlukan',
                    text: warningText,
                });
                container.innerHTML = '';
                startScanner(mode);
                return;
            }

            stopScanner();

            const form = document.getElementById(`form-${mode}`);
            if (form) {
                form.submit();
            }
        }

        function handleScanError(errorMessage) {
            console.warn(errorMessage);
        }

        function startScanner(mode) {
            stopScanner();
            activeMode = mode;
            const elementId = readerIds[mode];
            const scanner = new Html5QrcodeScanner(elementId, scannerConfig, false);
            scanner.render((decodedText, decodedResult) => handleScanSuccess(decodedText, decodedResult, mode), handleScanError);
            activeScanner = scanner;
        }

        function translateLocationError(error) {
            if (!error) {
                return 'Tidak dapat memperoleh lokasi saat ini.';
            }
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    return 'Izin lokasi ditolak. Izinkan akses lokasi untuk melanjutkan.';
                case error.POSITION_UNAVAILABLE:
                    return 'Informasi lokasi tidak tersedia. Coba aktifkan GPS atau pindah ke area terbuka.';
                case error.TIMEOUT:
                    return 'Permintaan lokasi melebihi batas waktu. Coba lagi.';
                default:
                    return 'Terjadi kesalahan saat mengambil lokasi.';
            }
        }

        function requestLocationPermission() {
            if (!('geolocation' in navigator)) {
                locationState.error = 'Perangkat ini tidak mendukung geolokasi.';
                updateDisplayedLocation();
                updateLocationStatusMessage(locationState.error);
                if (!locationAlertShown) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Lokasi tidak tersedia',
                        text: locationState.error,
                    });
                    locationAlertShown = true;
                }
                return;
            }

            const successHandler = (position) => {
                locationState.latitude = position.coords.latitude;
                locationState.longitude = position.coords.longitude;
                locationState.error = null;
                locationAlertShown = false;
                updateDisplayedLocation();
                updateLocationStatusMessage('');
            };

            const errorHandler = (error) => {
                locationState.error = translateLocationError(error);
                updateDisplayedLocation();
                updateLocationStatusMessage(locationState.error);
                if (!locationAlertShown) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Lokasi belum tersedia',
                        text: locationState.error,
                    });
                    locationAlertShown = true;
                }
            };

            navigator.geolocation.getCurrentPosition(successHandler, errorHandler, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0,
            });

            if (locationState.watcherId === null) {
                locationState.watcherId = navigator.geolocation.watchPosition(successHandler, errorHandler, {
                    enableHighAccuracy: true,
                    maximumAge: 5000,
                });
            }
        }

        function setActiveTab(mode) {
            const tabButtons = document.querySelectorAll('[data-tab-button]');
            const tabPanels = document.querySelectorAll('[data-tab-panel]');

            tabButtons.forEach((button) => {
                const buttonMode = button.getAttribute('data-tab-button');
                const isActive = buttonMode === mode;
                button.classList.toggle('bg-white', !isActive);
                button.classList.toggle('text-slate-600', !isActive);
                button.classList.toggle('shadow-sm', !isActive);
                button.classList.toggle('bg-primary', isActive);
                button.classList.toggle('text-primary-foreground', isActive);
            });

            tabPanels.forEach((panel) => {
                const panelMode = panel.getAttribute('data-tab-panel');
                panel.classList.toggle('hidden', panelMode !== mode);
            });

            startScanner(mode);
        }

        function setupTabs() {
            const tabButtons = document.querySelectorAll('[data-tab-button]');
            tabButtons.forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    const mode = button.getAttribute('data-tab-button');
                    if (mode && mode !== activeMode) {
                        setActiveTab(mode);
                    }
                });
            });
            const initialMode = defaultMode === 'pulang' ? 'pulang' : 'masuk';
            setActiveTab(initialMode);
        }

        function initializeLocationMap() {
            const mapElement = document.getElementById('absen-map');
            const emptyState = document.getElementById('map-empty-state');
            if (!mapElement) {
                return;
            }

            if (!Array.isArray(locationMarkers) || locationMarkers.length === 0) {
                if (emptyState) {
                    emptyState.classList.remove('hidden');
                    emptyState.classList.add('flex');
                }
                return;
            }

            const map = L.map(mapElement, {
                zoomControl: true,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> kontributor'
            }).addTo(map);

            const latLngs = [];
            locationMarkers.forEach((marker) => {
                if (typeof marker.latitude !== 'number' || typeof marker.longitude !== 'number') {
                    return;
                }
                const latLng = [marker.latitude, marker.longitude];
                latLngs.push(latLng);
                const entries = Array.isArray(marker.entries) ? marker.entries : [];
                const listItems = entries.map((entry) => `<li>${escapeHtml(entry)}</li>`).join('');
                const lastUsed = marker.lastUsed ? `<p class="mt-2 text-xs text-slate-400">Terakhir digunakan: ${escapeHtml(marker.lastUsed)}</p>` : '';
                const popupContent = `
                    <div class="text-sm font-semibold text-slate-800">${marker.count}x absen</div>
                    ${lastUsed}
                    ${entries.length > 0 ? `<ul class="mt-2 space-y-1 text-xs text-slate-600">${listItems}</ul>` : ''}
                `;
                L.marker(latLng).addTo(map).bindPopup(popupContent);
            });

            if (latLngs.length === 1) {
                map.setView(latLngs[0], 17);
            } else if (latLngs.length > 1) {
                const bounds = L.latLngBounds(latLngs);
                map.fitBounds(bounds, { padding: [30, 30] });
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            resultContainers.masuk = document.getElementById('result-masuk');
            resultContainers.pulang = document.getElementById('result-pulang');


            updateDisplayedLocation();
            requestLocationPermission();
            setupTabs();
            initializeLocationMap();



        });

        window.addEventListener('beforeunload', () => {
            stopScanner();
            if (locationState.watcherId !== null && 'geolocation' in navigator) {
                navigator.geolocation.clearWatch(locationState.watcherId);
            }
        });
    </script>
</body>

</html>
