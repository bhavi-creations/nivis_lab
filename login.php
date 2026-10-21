<?php
require_once __DIR__ . '/customer_auth.php';
$notice = $_SESSION['auth_notice'] ?? '';
if (!empty($_SESSION['customer_id']) && $notice === '') {
    header('Location: index.php');
    exit;
}
unset($_SESSION['auth_notice']);
$_SESSION['csrf'] = $_SESSION['csrf'] ?? bin2hex(random_bytes(32));
$signup = ($_GET['mode'] ?? 'signup') !== 'login';
$error = $_SESSION['auth_error'] ?? '';
$old = $_SESSION['auth_old'] ?? [];
unset($_SESSION['auth_error'], $_SESSION['auth_old']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $signup ? 'Create account' : 'Login' ?> | Nivis Labs International</title>
    <style>
        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 30px 16px;
            background: #f3f6f2;
            font-family: Arial, sans-serif;
            color: #22382f
        }

        .card {
            width: 100%;
            max-width: 480px;
            background: white;
            padding: 36px;
            border: 1px solid #dce5de;
            border-radius: 20px;
            box-shadow: 0 15px 45px #203c2910
        }

        .brand {
            font-weight: 800;
            letter-spacing: 3px;
            font-size: 23px
        }

        .brand small {
            display: block;
            font-size: 10px;
            letter-spacing: 4px;
            margin-top: 5px
        }

        h1 {
            font-size: 26px;
            margin: 28px 0 10px
        }

        p {
            color: #65746c;
            line-height: 1.6
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin: 24px 0
        }

        .tabs a {
            flex: 1;
            text-align: center;
            padding: 12px;
            border: 1px solid #cfdad3;
            border-radius: 8px;
            text-decoration: none;
            color: #234e3a
        }

        .tabs a.active,
        button,
        .continue {
            background: #234e3a;
            color: white
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin: 15px 0 7px
        }

        input,
        textarea {
            font: inherit;
            width: 100%;
            padding: 12px;
            border: 1px solid #b9c9bf;
            border-radius: 8px
        }

        textarea {
            resize: vertical;
            min-height: 76px
        }

        input:focus,
        textarea:focus {
            outline: 2px solid #77a98b;
            outline-offset: 2px
        }

        button,
        .continue {
            display: block;
            width: 100%;
            padding: 14px;
            margin-top: 24px;
            border: 0;
            border-radius: 8px;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            text-decoration: none
        }

        .message {
            padding: 14px;
            background: #edf6ed;
            border-radius: 8px;
            line-height: 1.6
        }

        .error {
            background: #fff0ed;
            color: #982f24
        }

        .hint {
            font-size: 13px;
            margin-bottom: 0
        }

        @media(max-width:480px) {
            .card {
                padding: 24px
            }
        }
    </style>
<link rel="stylesheet" href="assets/css/theme.css?v=3"><link rel="stylesheet" href="assets/css/account-login.css?v=1"></head>

<body class="nivis-theme nivis-login">
    <main class="card">
        <div class="brand"><img src="assets/img/new_logo_head.png" alt="Nivis Labs International"></div>
        <?php if ($notice !== ''): ?>
            <h1>Welcome to Nivis Labs</h1>
            <p class="message" role="status"><?= customer_escape($notice) ?></p>
            <a class="continue" href="index.php">Continue to website</a>
        <?php else: ?>
            <h1><?= $signup ? 'Create your account' : 'Welcome back' ?></h1>
            <p><?= $signup ? 'Register once to explore Nivis Labs.' : 'Log in with your email and phone number.' ?></p>
            <nav class="tabs" aria-label="Account options"><a href="login.php?mode=signup" class="<?= $signup ? 'active' : '' ?>">Sign up</a><a href="login.php?mode=login" class="<?= !$signup ? 'active' : '' ?>">Login</a></nav>
            <?php if ($error !== ''): ?><p class="message error" role="alert"><?= customer_escape($error) ?></p><?php endif; ?>
            <form action="check_login.php" method="post">
                <input type="hidden" name="csrf" value="<?= customer_escape($_SESSION['csrf']) ?>">
                <input type="hidden" name="mode" value="<?= $signup ? 'signup' : 'login' ?>">
                <?php if ($signup): ?>
                    <label for="name">Name</label><input id="name" name="name" autocomplete="name" maxlength="120" value="<?= customer_escape($old['name'] ?? '') ?>" required>
                    <label for="address">Address</label><textarea id="address" name="address" autocomplete="street-address" maxlength="4000" required><?= customer_escape($old['address'] ?? '') ?></textarea>
                    <label for="phone">Phone number</label><input id="phone" name="phone" type="tel" autocomplete="tel" pattern="\+?[0-9]{10,15}" maxlength="16" title="10 to 15 digits, optionally starting with +" value="<?= customer_escape($old['phone'] ?? '') ?>" required>
                <?php endif; ?>
                <label for="email">Email<?= $signup ? '' : ' / Username' ?></label><input id="email" name="email" type="email" autocomplete="username" maxlength="254" value="<?= customer_escape($old['email'] ?? '') ?>" required>
                <?php if (!$signup): ?>
                    <label for="password">Password</label><input id="password" name="password" type="password" autocomplete="current-password" required>
                <?php endif; ?>
                <p class="hint"><?= $signup ? 'Your email will be your username. Your phone number, exactly as entered, will be your password. We will email your login credentials to you.' : 'Use the phone number entered during signup as your password.' ?></p>
                <button type="submit"><?= $signup ? 'Create account' : 'Login' ?></button>
            </form>
        <?php endif; ?>
    </main>
</body>

</html>