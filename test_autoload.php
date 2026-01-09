<?php
define('BLITZ_CACHE_PLUGIN_DIR', '/d/Codebox/__WP_PLUGINS__/BlitzCache/blitz-cache/');
$class = 'Blitz_Cache_Cache';
$file = BLITZ_CACHE_PLUGIN_DIR . 'includes/class-' . strtolower(str_replace('_', '-', $class)) . '.php';
echo "Looking for: $file\n";
echo file_exists($file) ? "exists\n" : "not found\n";
