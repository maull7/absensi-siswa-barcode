<?php
session_start();
require_once 'koneksi.php';

$errorMessage = '';
$nisInput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisInput = isset($_POST['nis']) ? trim($_POST['nis']) : '';
    $passwordInput = isset($_POST['password']) ? trim($_POST['password']) : '';

    if ($nisInput === '' || $passwordInput === '') {
        $errorMessage = 'Masukkan NIS dan kata sandi Anda.';
    } else {
        $stmt = $koneksi->prepare('SELECT nis FROM data_siswa WHERE nis = ? AND password = ? LIMIT 1');

        if ($stmt) {
            $stmt->bind_param('ss', $nisInput, $passwordInput);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $_SESSION['nis'] = $nisInput;
                $_SESSION['password'] = $passwordInput;
                $stmt->close();
                $koneksi->close();
                header('Location: siswa/index.php');
                exit();
            }

            $stmt->close();
            $errorMessage = 'NIS atau kata sandi Anda tidak cocok.';
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
    <title>Absensi Siswa | Masuk</title>
    <link rel="icon" href="assets/img/smkmadya.png">
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
                <img src="assets/img/smkmadya.png" alt="Logo Sekolah" class="h-10 w-10 object-contain" />
            </div>
            <h1 class="mt-6 text-3xl font-semibold text-slate-900">Portal Absensi Siswa</h1>
            <p class="mt-2 text-sm text-slate-500">Masuk menggunakan akun siswa Anda untuk melakukan absensi harian.</p>
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
                        <label for="nis" class="block text-sm font-medium text-slate-700">NIS</label>
                        <input type="text" id="nis" name="nis" value="<?= htmlspecialchars($nisInput, ENT_QUOTES, 'UTF-8'); ?>"
                            autocomplete="username" placeholder="Masukkan NIS Anda" required
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
                <span>Ingin mengelola data?</span>
                <a href="admin.php" class="font-medium text-primary hover:text-indigo-600">Masuk sebagai Admin</a>
            </div>
        </div>
    </div>
</body>

</html>
