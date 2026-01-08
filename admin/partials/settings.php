<?php
/**
 * Settings partial
 */

$options = Blitz_Cache_Options::get();
$defaults = Blitz_Cache_Options::get_defaults();

// Merge with defaults for missing values
$options = array_merge($defaults, $options);
?>

<form id="blitz-cache-settings-form" method="post">
    <?php wp_nonce_field('blitz_cache_nonce', 'blitz_cache_nonce'); ?>

    <div class="blitz-cache-section">
        <h2><?php esc_html_e('Page Cache', 'blitz-cache'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('Enable Page Cache', 'blitz-cache'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="page_cache_enabled" value="1" <?php checked($options['page_cache_enabled']); ?> />
                        <?php esc_html_e('Cache HTML pages for faster loading', 'blitz-cache'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Cache TTL', 'blitz-cache'); ?></th>
                <td>
                    <input type="number" name="page_cache_ttl" value="<?php echo esc_attr($options['page_cache_ttl']); ?>" min="3600" step="3600" />
                    <span class="description"><?php esc_html_e('Time to live in seconds (default: 86400 = 24 hours)', 'blitz-cache'); ?></span>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Cache Logged-in Users', 'blitz-cache'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="cache_logged_in" value="1" <?php checked($options['cache_logged_in']); ?> />
                        <?php esc_html_e('Enable caching for logged-in users', 'blitz-cache'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Separate Mobile Cache', 'blitz-cache'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="mobile_cache" value="1" <?php checked($options['mobile_cache']); ?> />
                        <?php esc_html_e('Create separate cache for mobile devices', 'blitz-cache'); ?>
                    </label>
                </td>
            </tr>
        </table>
    </div>

    <div class="blitz-cache-section">
        <h2><?php esc_html_e('Browser Cache', 'blitz-cache'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('Enable Browser Cache', 'blitz-cache'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="browser_cache_enabled" value="1" <?php checked($options['browser_cache_enabled']); ?> />
                        <?php esc_html_e('Set cache headers for browsers', 'blitz-cache'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('CSS/JS TTL', 'blitz-cache'); ?></th>
                <td>
                    <input type="number" name="css_js_ttl" value="<?php echo esc_attr($options['css_js_ttl']); ?>" min="86400" step="86400" />
                    <span class="description"><?php esc_html_e('Cache duration for CSS/JS files in seconds (default: 2592000 = 30 days)', 'blitz-cache'); ?></span>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Images TTL', 'blitz-cache'); ?></th>
                <td>
                    <input type="number" name="images_ttl" value="<?php echo esc_attr($options['images_ttl']); ?>" min="86400" step="86400" />
                    <span class="description"><?php esc_html_e('Cache duration for images in seconds (default: 7776000 = 90 days)', 'blitz-cache'); ?></span>
                </td>
            </tr>
        </table>
    </div>

    <div class="blitz-cache-section">
        <h2><?php esc_html_e('Compression', 'blitz-cache'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('Enable GZIP', 'blitz-cache'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="gzip_enabled" value="1" <?php checked($options['gzip_enabled']); ?> />
                        <?php esc_html_e('Compress cached files with GZIP', 'blitz-cache'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Enable HTML Minify', 'blitz-cache'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="html_minify_enabled" value="1" <?php checked($options['html_minify_enabled']); ?> />
                        <?php esc_html_e('Minify HTML before caching', 'blitz-cache'); ?>
                    </label>
                </td>
            </tr>
        </table>
    </div>

    <div class="blitz-cache-section">
        <h2><?php esc_html_e('Exclusions', 'blitz-cache'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('Excluded URLs', 'blitz-cache'); ?></th>
                <td>
                    <textarea name="excluded_urls" rows="5" cols="50" class="large-text"><?php echo esc_textarea(implode("\n", $options['excluded_urls'])); ?></textarea>
                    <p class="description"><?php esc_html_e('Enter one URL pattern per line. Use * as a wildcard.', 'blitz-cache'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Excluded Cookies', 'blitz-cache'); ?></th>
                <td>
                    <textarea name="excluded_cookies" rows="5" cols="50" class="large-text"><?php echo esc_textarea(implode("\n", $options['excluded_cookies'])); ?></textarea>
                    <p class="description"><?php esc_html_e('Enter one cookie name pattern per line. Use * as a wildcard.', 'blitz-cache'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Excluded User Agents', 'blitz-cache'); ?></th>
                <td>
                    <textarea name="excluded_user_agents" rows="5" cols="50" class="large-text"><?php echo esc_textarea(implode("\n", $options['excluded_user_agents'])); ?></textarea>
                    <p class="description"><?php esc_html_e('Enter one user agent pattern per line. Use * as a wildcard.', 'blitz-cache'); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="blitz-cache-section">
        <h2><?php esc_html_e('Preload', 'blitz-cache'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('Enable Cache Warmup', 'blitz-cache'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="warmup_enabled" value="1" <?php checked($options['warmup_enabled']); ?> />
                        <?php esc_html_e('Automatically cache pages after purge', 'blitz-cache'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Warmup Source', 'blitz-cache'); ?></th>
                <td>
                    <select name="warmup_source">
                        <option value="sitemap" <?php selected($options['warmup_source'], 'sitemap'); ?>><?php esc_html_e('Sitemap', 'blitz-cache'); ?></option>
                        <option value="menu" <?php selected($options['warmup_source'], 'menu'); ?>><?php esc_html_e('Navigation Menu', 'blitz-cache'); ?></option>
                        <option value="custom" <?php selected($options['warmup_source'], 'custom'); ?>><?php esc_html_e('Custom (via filter)', 'blitz-cache'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Warmup Interval', 'blitz-cache'); ?></th>
                <td>
                    <select name="warmup_interval">
                        <option value="7200" <?php selected($options['warmup_interval'], 7200); ?>><?php esc_html_e('2 hours', 'blitz-cache'); ?></option>
                        <option value="21600" <?php selected($options['warmup_interval'], 21600); ?>><?php esc_html_e('6 hours', 'blitz-cache'); ?></option>
                        <option value="43200" <?php selected($options['warmup_interval'], 43200); ?>><?php esc_html_e('12 hours', 'blitz-cache'); ?></option>
                        <option value="86400" <?php selected($options['warmup_interval'], 86400); ?>><?php esc_html_e('24 hours', 'blitz-cache'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><?php esc_html_e('Warmup Batch Size', 'blitz-cache'); ?></th>
                <td>
                    <input type="number" name="warmup_batch_size" value="<?php echo esc_attr($options['warmup_batch_size']); ?>" min="1" max="50" />
                    <p class="description"><?php esc_html_e('Number of URLs to cache per batch (lower = less server load)', 'blitz-cache'); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <p class="submit">
        <button type="submit" class="button-primary"><?php esc_html_e('Save Settings', 'blitz-cache'); ?></button>
    </p>
</form>
