<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Đăng nhập') ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('admin_assets/images/logo.png') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/base.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/grid.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/responsive.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="body__login">
    <section class="login">
        <div class="login__icon">
            <img src="<?= base_url('admin_assets/images/logo.png') ?>" alt="UIT Petcare Logo" class="login__logo-img">
        </div>
        <h2 class="login__title">UIT Petcare</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="login__alert login__alert--error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="login__alert login__alert--success">
                <i class="fa-solid fa-circle-check"></i>
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <form class="login__form" action="<?= site_url('admin/login') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="login__group">
                <label for="username" class="login__label">Tên đăng nhập</label>
                <input type="text" id="username" name="username" class="login__input" required>
            </div>

            <div class="login__group">
                <label for="password" class="login__label">Mật khẩu</label>
                <input type="password" id="password" name="password" class="login__input" required>
            </div>

            <button type="submit" class="login__button">Đăng nhập</button>
        </form>
    </section>

    <?php if (session()->getFlashdata('success')): ?>
        <script>
            setTimeout(() => {
                window.location.href = "<?= site_url('admin/dashboard') ?>";
            }, 2000);
        </script>
    <?php endif; ?>
</body>

</html>
