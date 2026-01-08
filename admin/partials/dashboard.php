<?php
/**
 * Dashboard partial
 */

$cache = new Blitz_Cache_Cache();
$stats = $cache->get_stats();

$options = Blitz_Cache_Options::get();
$is_enabled = !empty($options['page_cache_enabled']);

$hits = $stats['hits'] ?? 0;
$misses = $stats['misses'] ?? 0;
$total_requests = $hits + $misses;
$hit_ratio = $total_requests > 0 ? round(($hits / $total_requests) * 100, 1) : 0;

$cf_options = Blitz_Cache_Options::get_cloudflare();
?>

<div class="blitz-cache-dashboard">
    <div class="blitz-cache-cards">
        <div class="blitz-cache-card">
            <h3><?php esc_html_e('Cache Status', 'blitz-cache'); ?></h3>
            <div class="blitz-cache-status">
                <?php if ($is_enabled): ?>
                    <span class="status-active"><?php esc_html_e('Active', 'blitz-cache'); ?></span>
                <?php else: ?>
                    <span class="status-inactive"><?php esc_html_e('Inactive', 'blitz-cache'); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="blitz-cache-card">
            <h3><?php esc_html_e('Cached Pages', 'blitz-cache'); ?></h3>
            <div class="blitz-cache-number"><?php echo esc_html($stats['cached_pages'] ?? 0); ?></div>
        </div>

        <div class="blitz-cache-card">
            <h3><?php esc_html_e('Hit Ratio', 'blitz-cache'); ?></h3>
            <div class="blitz-cache-number"><?php echo esc_html($hit_ratio); ?>%</div>
            <small><?php echo esc_html($hits); ?> <?php esc_html_e('hits,', 'blitz-cache'); ?> <?php echo esc_html($misses); ?> <?php esc_html_e('misses', 'blitz-cache'); ?></small>
        </div>

        <div class="blitz-cache-card">
            <h3><?php esc_html_e('Cache Size', 'blitz-cache'); ?></h3>
            <div class="blitz-cache-number"><?php echo esc_html(round(($stats['cache_size'] ?? 0) / 1024 / 1024, 2)); ?> MB</div>
        </div>
    </div>

    <div class="blitz-cache-actions">
        <h3><?php esc_html_e('Quick Actions', 'blitz-cache'); ?></h3>
        <button type="button" class="button button-primary" id="btn-purge-all">
            <span class="dashicons dashicons-trash"></span>
            <?php esc_html_e('Purge All Cache', 'blitz-cache'); ?>
        </button>
        <button type="button" class="button" id="btn-warmup">
            <span class="dashicons dashicons-update"></span>
            <?php esc_html_e('Warm Cache Now', 'blitz-cache'); ?>
        </button>
    </div>

    <div class="blitz-cache-info">
        <div class="blitz-cache-cloudflare-status">
            <h3><?php esc_html_e('Cloudflare', 'blitz-cache'); ?></h3>
            <?php if (!empty($cf_options['connection_status'])): ?>
                <?php if ($cf_options['connection_status'] === 'connected'): ?>
                    <p><span class="status-connected"><?php esc_html_e('Connected', 'blitz-cache'); ?></span></p>
                <?php elseif ($cf_options['connection_status'] === 'error'): ?>
                    <p><span class="status-error"><?php esc_html_e('Error', 'blitz-cache'); ?></span></p>
                <?php else: ?>
                    <p><span class="status-disconnected"><?php esc_html_e('Disconnected', 'blitz-cache'); ?></span></p>
                <?php endif; ?>
            <?php else: ?>
                <p><?php esc_html_e('Not configured', 'blitz-cache'); ?></p>
            <?php endif; ?>
        </div>

        <div class="blitz-cache-timestamps">
            <h3><?php esc_html_e('Last Activity', 'blitz-cache'); ?></h3>
            <?php if (!empty($stats['last_warmup'])): ?>
                <p><strong><?php esc_html_e('Last Warmup:', 'blitz-cache'); ?></strong> <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $stats['last_warmup'])); ?></p>
            <?php endif; ?>
            <?php if (!empty($stats['last_purge'])): ?>
                <p><strong><?php esc_html_e('Last Purge:', 'blitz-cache'); ?></strong> <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $stats['last_purge'])); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
