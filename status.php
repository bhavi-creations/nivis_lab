<?php
/**
 * System Status Checker
 * Checks if everything is properly set up
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nivis Labs</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #333; margin-bottom: 20px; }
        .status-item { 
            background: white; 
            padding: 15px; 
            margin-bottom: 10px; 
            border-left: 4px solid #ddd;
            border-radius: 4px;
        }
        .status-item.ok { border-left-color: #28a745; }
        .status-item.error { border-left-color: #dc3545; }
        .status-item.warning { border-left-color: #ffc107; }
        .status-label { font-weight: bold; }
        .status-value { color: #666; margin-top: 5px; }
        .ok-text { color: #28a745; }
        .error-text { color: #dc3545; }
        .warning-text { color: #ffc107; }
        code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; }
        .section-title { 
            background: #667eea; 
            color: white; 
            padding: 10px 15px; 
            margin: 20px 0 10px 0;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 System Status Checker</h1>

        <div class="section-title">📋 PHP Environment</div>
        
        <div class="status-item <?php echo (PHP_VERSION_ID >= 70400) ? 'ok' : 'error'; ?>">
            <div class="status-label">PHP Version</div>
            <div class="status-value">
                <?php echo phpversion(); ?>
                <span class="<?php echo (PHP_VERSION_ID >= 70400) ? 'ok-text' : 'error-text'; ?>">
                    (<?php echo (PHP_VERSION_ID >= 70400) ? '✓ OK' : '✗ Needs 7.4+'; ?>)
                </span>
            </div>
        </div>

        <div class="status-item <?php echo extension_loaded('json') ? 'ok' : 'error'; ?>">
            <div class="status-label">JSON Extension</div>
            <div class="status-value">
                <?php echo extension_loaded('json') ? '✓ Loaded' : '✗ Not Loaded'; ?>
            </div>
        </div>

        <div class="status-item <?php echo extension_loaded('curl') ? 'ok' : 'warning'; ?>">
            <div class="status-label">cURL Extension</div>
            <div class="status-value">
                <?php echo extension_loaded('curl') ? '✓ Loaded' : '⚠ Not Loaded (needed for API calls)'; ?>
            </div>
        </div>

        <div class="section-title">📁 File & Directory Check</div>

        <?php
        $checks = [
            'vendor/autoload.php' => 'Composer Autoloader',
            'vendor/webonyx/graphql-php' => 'GraphQL PHP Library',
            'assets/js/graphql-client.js' => 'GraphQL Client JS',
            'config.php' => 'Configuration File',
            'graphql-api.php' => 'GraphQL API',
            'admin.php' => 'Admin Dashboard',
        ];

        foreach ($checks as $path => $label) {
            $exists = file_exists($path) || file_exists(__DIR__ . '/' . $path);
            $full_path = $exists ? (file_exists($path) ? $path : __DIR__ . '/' . $path) : '';
            ?>
            <div class="status-item <?php echo $exists ? 'ok' : 'error'; ?>">
                <div class="status-label"><?php echo $label; ?></div>
                <div class="status-value">
                    <code><?php echo $path; ?></code>
                    <div style="margin-top: 5px;">
                        <?php echo $exists ? '✓ Found' : '✗ Missing'; ?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>

        <div class="section-title">🔗 API & Connectivity</div>

        <div class="status-item">
            <div class="status-label">GraphQL API Endpoint</div>
            <div class="status-value">
                <code>http://localhost/nivis_lab/graphql-api.php</code>
                <div style="margin-top: 10px;">
                    <?php
                    $api_url = 'http://localhost/nivis_lab/graphql-api.php';
                    $query = json_encode(['query' => '{ products { id name } }']);
                    
                    $ch = @curl_init($api_url);
                    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    @curl_setopt($ch, CURLOPT_POST, true);
                    @curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
                    @curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                    @curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                    
                    $response = @curl_exec($ch);
                    $http_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    @curl_close($ch);
                    
                    if ($http_code === 200 && $response) {
                        $data = json_decode($response, true);
                        if (isset($data['data'])) {
                            echo '<span class="ok-text">✓ Working</span>';
                        } else {
                            echo '<span class="warning-text">⚠ Responding but check data</span>';
                        }
                    } else {
                        echo '<span class="error-text">✗ Not responding (HTTP ' . $http_code . ')</span>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="section-title">🧪 Test URLs</div>

        <div class="status-item">
            <div class="status-label">Quick Test Links</div>
            <div class="status-value">
                <ul style="list-style: none; padding-left: 0;">
                    <li>📊 <a href="http://localhost/nivis_lab/admin.php" target="_blank">Admin Dashboard</a></li>
                    <li>🧪 <a href="http://localhost/nivis_lab/test-graphql.php" target="_blank">GraphQL Test</a></li>
                    <li>🛍️ <a href="http://localhost/nivis_lab/products.php" target="_blank">Products Page</a></li>
                    <li>🏠 <a href="http://localhost/nivis_lab/index.php" target="_blank">Home Page</a></li>
                </ul>
            </div>
        </div>

        <div class="section-title">📝 Next Steps</div>

        <div style="background: white; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            <ol style="line-height: 1.8;">
                <li>✅ Make sure all files are present (green checkmarks above)</li>
                <li>✅ Run <code>composer install</code> if vendor folder is missing</li>
                <li>✅ Visit <strong>Admin Dashboard</strong> to see GraphQL data loading</li>
                <li>✅ Check Browser Console (F12) for any errors</li>
                <li>✅ Open <strong>test-graphql.php</strong> to test API directly</li>
            </ol>
        </div>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; border-left: 4px solid #667eea;">
            <strong>💡 Tip:</strong> Open <a href="http://localhost/nivis_lab/admin.php">Admin Dashboard</a> 
            to see your products loading from GraphQL API in real-time!
        </div>
    </div>

    <script>
        // Auto-refresh status every 30 seconds
        // setTimeout(() => location.reload(), 30000);
    </script>
</body>
</html>
