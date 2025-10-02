<?php
session_start();
require_once '../koneksi.php';

if (isset($_SESSION['orang_tua_id'])) {
    header('Location: dashboard.php');
    exit();
}

$errorMessage = '';
$nikInput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nikInput = isset($_POST['nik']) ? trim($_POST['nik']) : '';
    $passwordInput = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($nikInput === '' || $passwordInput === '') {
        $errorMessage = 'Masukkan NIK dan kata sandi Anda.';
    } else {
        $stmt = $koneksi->prepare('SELECT id, nama, nik, pw, nis FROM orang_tua WHERE nik = ? LIMIT 1');

        if ($stmt) {
            $stmt->bind_param('s', $nikInput);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($id, $nama, $nik, $passwordDb, $nisAnak);
                $stmt->fetch();

                if (hash_equals((string) $passwordDb, $passwordInput)) {
                    $_SESSION['orang_tua_id'] = $id;
                    $_SESSION['orang_tua_nama'] = $nama;
                    $_SESSION['orang_tua_nik'] = $nik;
                    $_SESSION['orang_tua_nis'] = $nisAnak;

                    $stmt->close();
                    $koneksi->close();

                    header('Location: dashboard.php');
                    exit();
                }
            }

            $stmt->close();
            $errorMessage = 'NIK atau kata sandi Anda tidak cocok.';
        } else {
            $errorMessage = 'Gagal memproses permintaan. Silakan coba lagi.';
        }
    }
}

$koneksi->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Siswa | Masuk Orang Tua</title>
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

<body class="font-display bg-slate-950/[0.02] min-h-screen flex items-center justify-center py-12 px-4">
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-32 -left-32 h-64 w-64 rounded-full bg-primary/20 blur-3xl"></div>
        <div class="absolute top-1/2 right-0 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl"></div>
    </div>

    <div class="w-full max-w-md">
        <div class="mb-10 text-center">
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                <img src="../assets/img/smkmadya.png" alt="Logo Sekolah" class="h-10 w-10 object-contain" />
            </div>
            <h1 class="mt-6 text-3xl font-semibold text-slate-900">Portal Orang Tua</h1>
            <p class="mt-2 text-sm text-slate-500">Pantau kehadiran siswa secara real-time.</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white/90 shadow-xl shadow-indigo-500/5 backdrop-blur-sm">
            <div class="px-8 py-10">
                <?php if ($errorMessage !== ''): ?>
                    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>
                <form method="post" class="space-y-6">
                    <div>
                        <label for="nik" class="block text-sm font-medium text-slate-700">NIK</label>
                        <input type="text" id="nik" name="nik" value="<?= htmlspecialchars($nikInput, ENT_QUOTES, 'UTF-8'); ?>"
                            autocomplete="username" placeholder="Masukkan NIK Anda" required
                            class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/60 px-4 py-3 text-slate-900 shadow-inner focus:border-primary focus:ring-primary" />
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700">Kata sandi</label>
                        <input type="password" id="password" name="password" autocomplete="current-password"
                            placeholder="Masukkan kata sandi" required
                            class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50/60 px-4 py-3 text-slate-900 shadow-inner focus:border-primary focus:ring-primary" />
                    </div>
                    <button type="submit"
                        class="flex w-full items-center justify-center rounded-2xl bg-primary px-4 py-3 text-sm font-semibold text-primary-foreground shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500">
                        Masuk ke Dashboard
                    </button>
                </form>
            </div>
            <div class="flex items-center justify-between border-t border-slate-100 px-8 py-6 text-sm text-slate-500">
                <a href="../index.php" class="font-medium text-primary hover:text-indigo-600">Masuk sebagai Siswa</a>
                <a href="../admin.php" class="font-medium text-primary hover:text-indigo-600">Masuk sebagai Admin</a>
            </div>
        </div>
    </div>
</body>

</html>
