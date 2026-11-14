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
$challengeId = (($_SESSION['auth_challenge_purpose'] ?? '') === ($signup ? 'registration' : 'signin')) ? (string)($_SESSION['auth_challenge_id'] ?? '') : '';
$oldPhone = (string)($old['phone'] ?? '');
$resendAt = 0;
$savedPhoneHash = (string)($_SESSION['auth_resend_phone_hash'] ?? '');
if ($challengeId !== '' && $oldPhone !== '' && preg_match('/^[a-f0-9]{64}$/D', $savedPhoneHash) && hash_equals($savedPhoneHash, hash('sha256', $oldPhone))) {
    $resendAt = (int)($_SESSION['auth_resend_at_ms'] ?? 0);
    if ($resendAt <= (int)floor(microtime(true) * 1000) || $resendAt > (int)floor(microtime(true) * 1000) + 120000) $resendAt = 0;
}
if (($_SESSION['auth_challenge_purpose'] ?? '') !== ($signup ? 'registration' : 'signin')) {
    unset($_SESSION['auth_challenge_id'], $_SESSION['auth_challenge_purpose'], $_SESSION['auth_resend_at_ms'], $_SESSION['auth_resend_phone_hash']);
}
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
            background: var(--nivis-page);
            font-family: Arial, sans-serif;
            color: var(--nivis-text)
        }

        .card {
            width: 100%;
            max-width: 480px;
            background: var(--nivis-panel);
            padding: 36px;
            border: 1px solid var(--nivis-border);
            border-radius: 20px;
            box-shadow: var(--nivis-shadow)
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
            color: var(--nivis-muted);
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
            border: 1px solid var(--nivis-border);
            border-radius: 8px;
            text-decoration: none;
            color: var(--nivis-teal)
        }

        .tabs a.active,
        button,
        .continue {
            background: var(--nivis-button);
            color: #fff
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
            border: 1px solid var(--nivis-border);
            border-radius: 8px
        }

        textarea {
            resize: vertical;
            min-height: 76px
        }

        input:focus,
        textarea:focus {
            outline: 2px solid var(--nivis-gold);
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
            background: var(--nivis-surface);
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"><link rel="stylesheet" href="assets/css/theme.css?v=3"><link rel="stylesheet" href="assets/css/account-login.css?v=4"></head>

<body class="nivis-theme nivis-login">
    <main class="card">
        <div class="brand"><img src="assets/img/nivis-labs-international.png" alt="Nivis Labs International"></div>
        <?php if ($notice !== ''): ?>
            <h1>Welcome to Nivis Labs</h1>
            <p class="message" role="status"><?= customer_escape($notice) ?></p>
            <a class="continue" href="index.php">Continue to website</a>
        <?php else: ?>
            <h1><?= $signup ? 'Create your account' : 'Welcome back' ?></h1>
            <p><?= $signup ? 'Create an account and verify your WhatsApp number.' : 'Sign in securely with a code sent to your WhatsApp number.' ?></p>
            <?php
            $returnOrderId = isset($_GET['order_id']) && is_string($_GET['order_id']) ? trim($_GET['order_id']) : '';
            $returnToTracking = ($_GET['return'] ?? '') === 'tracking.php' && $returnOrderId !== '' && strlen($returnOrderId) <= 100;
            $returnTo = $returnToTracking ? 'tracking.php' : (($_GET['return'] ?? '') === 'checkout.php' ? 'checkout.php' : '');
            $returnQuery = $returnTo === 'checkout.php'
                ? '&amp;return=checkout.php'
                : ($returnToTracking ? '&amp;return=tracking.php&amp;order_id=' . rawurlencode($returnOrderId) : '');
            ?>
            <nav class="tabs" aria-label="Account options"><a href="login.php?mode=signup<?= $returnQuery ?>" class="<?= $signup ? 'active' : '' ?>">Sign up</a><a href="login.php?mode=login<?= $returnQuery ?>" class="<?= !$signup ? 'active' : '' ?>">Login</a></nav>
            <?php if ($error !== ''): ?><p class="message error" role="alert"><?= customer_escape($error) ?></p><?php endif; ?>
            <form id="customer-auth-form" action="check_login.php" method="post" data-purpose="<?= $signup ? 'registration' : 'signin' ?>" data-challenge="<?= customer_escape($challengeId) ?>">
                <input type="hidden" name="csrf" value="<?= customer_escape($_SESSION['csrf']) ?>">
                <input type="hidden" name="mode" value="<?= $signup ? 'signup' : 'login' ?>">
                <?php if ($returnTo): ?><input type="hidden" name="return" value="<?= customer_escape($returnTo) ?>"><?php endif; ?>
                <?php if ($returnToTracking): ?><input type="hidden" name="order_id" value="<?= customer_escape($returnOrderId) ?>"><?php endif; ?>
                <?php if ($signup): ?>
                    <label for="name">Name</label><input id="name" name="name" autocomplete="name" maxlength="120" value="<?= customer_escape($old['name'] ?? '') ?>" required>
                    <label for="address">Address</label><textarea id="address" name="address" autocomplete="street-address" maxlength="255" required><?= customer_escape($old['address'] ?? '') ?></textarea>
                    <label for="country">Country</label><select id="country" name="country" autocomplete="country" required>
<?php $countryOptions = json_decode(file_get_contents(__DIR__ . '/assets/customer-countries.json'), true)['data']['countries']; foreach ($countryOptions as $option): ?>
<option value="<?= customer_escape($option['code']) ?>" <?= ($old['country'] ?? 'IN') === $option['code'] ? 'selected' : '' ?>><?= customer_escape($option['name']) ?></option>
<?php endforeach; ?></select>
                    <label for="province">State / Province</label><select id="province" name="province" autocomplete="address-level1" required><option value="">Select state</option>
<?php foreach ($countryOptions as $option): if ($option['code'] !== ($old['country'] ?? 'IN')) continue; foreach ($option['provinces'] ?? [] as $state): ?>
<option value="<?= customer_escape($state['code']) ?>" <?= ($old['province'] ?? '') === $state['code'] ? 'selected' : '' ?>><?= customer_escape($state['name']) ?></option>
<?php endforeach; endforeach; ?></select>
                    <label for="postcode">PIN / Postal code</label><input id="postcode" name="postcode" maxlength="20" value="<?= customer_escape($old['postcode'] ?? '') ?>" autocomplete="postal-code" required>
                <?php endif; ?>
                <label for="phone">WhatsApp number</label><input id="phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" pattern="\+[1-9][0-9]{7,14}" maxlength="16" title="Include the country code, for example +919876543210" value="<?= customer_escape($old['phone'] ?? '') ?>" required>
                <?php if ($signup): ?><label for="email">Email</label><input id="email" name="email" type="email" autocomplete="email" maxlength="254" value="<?= customer_escape($old['email'] ?? '') ?>" required><?php endif; ?>
                <p class="hint"><?= $signup ? 'Enter your number with country code. We will send a one-time code to verify it.' : 'Enter the WhatsApp number linked to your account, including its country code.' ?></p>
                <input type="hidden" id="challenge_id" name="challenge_id" value="<?= customer_escape($challengeId) ?>">
                <input type="hidden" id="resend_at_ms" value="<?= $resendAt ?>">
                <div id="otp-step" <?= $challengeId === '' ? 'hidden' : '' ?>>
                    <label for="otp_code">WhatsApp verification code</label>
                    <input id="otp_code" name="otp_code" type="text" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" maxlength="6" aria-describedby="otp-message" <?= $challengeId !== '' ? 'required' : 'disabled' ?>>
                </div>
                <p id="otp-message" class="message" role="status" aria-live="polite" <?= $challengeId === '' ? 'hidden' : '' ?>><?= $challengeId !== '' ? 'Enter the code sent to your WhatsApp number.' : '' ?></p>
                <button type="button" id="request-otp" <?= $challengeId !== '' ? 'hidden' : '' ?>>Send WhatsApp code</button>
                <button type="submit" id="finish-auth" <?= $challengeId === '' ? 'hidden' : '' ?>><?= $signup ? 'Create account' : 'Sign in' ?></button>
            </form>
            <?php if (!$signup): ?><p class="hint">Each sign-in uses a new code. You do not need a password.</p><?php endif; ?>
        <?php endif; ?>
    </main>
<script src="assets/js/account-address.js?v=2" defer></script>
<script src="assets/js/customer-otp.js?v=3" defer></script>
</body>

</html>
