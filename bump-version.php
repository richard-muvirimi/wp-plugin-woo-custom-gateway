#!/usr/bin/env php
<?php
/**
 * Version Bump Script
 * 
 * Updates version numbers across all plugin files
 * 
 * Usage: php bump-version.php <new-version>
 * Example: php bump-version.php 1.6.5
 * 
 * @package WooCustomGateway
 */

if (php_sapi_name() !== 'cli') {
    die('This script must be run from the command line.');
}

if ($argc < 2) {
    echo "Usage: php bump-version.php <new-version>\n";
    echo "Example: php bump-version.php 1.6.5\n";
    exit(1);
}

$newVersion = $argv[1];

// Validate version format (semantic versioning)
if (!preg_match('/^\d+\.\d+\.\d+$/', $newVersion)) {
    echo "Error: Version must be in format X.Y.Z (e.g., 1.6.5)\n";
    exit(1);
}

$rootDir = __DIR__;

// Helper function to find files case-insensitively
function findFile($dir, $pattern) {
    // Convert pattern to case-insensitive version
    $caseInsensitivePattern = '';
    for ($i = 0; $i < strlen($pattern); $i++) {
        $char = $pattern[$i];
        if (preg_match('/[a-zA-Z]/', $char)) {
            $caseInsensitivePattern .= '[' . strtoupper($char) . strtolower($char) . ']';
        } else {
            $caseInsensitivePattern .= $char;
        }
    }
    $files = glob($dir . '/' . $caseInsensitivePattern, GLOB_BRACE);
    return !empty($files) ? $files[0] : null;
}

// Define files to update with their patterns
$files = [
    // Main plugin file - version in header
    [
        'file' => findFile($rootDir, 'woo-custom-gateway.php'),
        'pattern' => '/(\* Version:\s+)\d+\.\d+\.\d+/',
        'replacement' => '${1}' . $newVersion,
        'description' => 'Plugin header version'
    ],
    // Main plugin file - version constant
    [
        'file' => findFile($rootDir, 'woo-custom-gateway.php'),
        'pattern' => "/(const WOO_CUSTOM_GATEWAY_VERSION = ')\d+\.\d+\.\d+('\;)/",
        'replacement' => '${1}' . $newVersion . '${2}',
        'description' => 'Plugin constant version'
    ],
    // package.json
    [
        'file' => findFile($rootDir, 'package.json'),
        'pattern' => '/("version":\s*")\d+\.\d+\.\d+(",)/',
        'replacement' => '${1}' . $newVersion . '${2}',
        'description' => 'package.json version'
    ],
    // composer.json
    [
        'file' => findFile($rootDir, 'composer.json'),
        'pattern' => '/("version"\s*:\s*")\d+\.\d+\.\d+(",)/',
        'replacement' => '${1}' . $newVersion . '${2}',
        'description' => 'composer.json version'
    ],
    // readme.txt - Stable tag
    [
        'file' => findFile($rootDir, 'readme.txt'),
        'pattern' => '/(Stable tag:\s+)\d+\.\d+\.\d+/',
        'replacement' => '${1}' . $newVersion,
        'description' => 'readme.txt stable tag'
    ],
    // readme.md - Stable tag
    [
        'file' => findFile($rootDir, 'readme.md'),
        'pattern' => '/(\*\*_ Stable tag: _\*\* )\d+\.\d+\.\d+/',
        'replacement' => '${1}' . $newVersion,
        'description' => 'readme.md stable tag'
    ],
];

$updated = [];
$errors = [];

echo "Bumping version to $newVersion...\n\n";

foreach ($files as $fileConfig) {
    $file = $fileConfig['file'];
    $description = $fileConfig['description'];
    
    if (!file_exists($file)) {
        $errors[] = "File not found: $file";
        continue;
    }
    
    $content = file_get_contents($file);
    $newContent = preg_replace(
        $fileConfig['pattern'],
        $fileConfig['replacement'],
        $content,
        1,
        $count
    );
    
    if ($count === 0) {
        $errors[] = "Pattern not found in $file ($description)";
        continue;
    }
    
    if (file_put_contents($file, $newContent) === false) {
        $errors[] = "Failed to write to $file";
        continue;
    }
    
    $updated[] = $description;
    echo "✓ Updated: $description\n";
}

echo "\n";

if (!empty($errors)) {
    echo "Errors encountered:\n";
    foreach ($errors as $error) {
        echo "✗ $error\n";
    }
    echo "\n";
}

if (!empty($updated)) {
    echo "Successfully updated " . count($updated) . " locations.\n\n";
    echo "Next steps:\n";
    echo "1. Add changelog entries to readme.txt and readme.md\n";
    echo "2. Review changes: git diff\n";
    echo "3. Commit changes: git add . && git commit -m \"Bump version to $newVersion\"\n";
    echo "4. Push to trigger deployment: git push origin production\n";
} else {
    echo "No files were updated.\n";
    exit(1);
}
