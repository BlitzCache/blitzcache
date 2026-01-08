<?php
/**
 * Tools partial
 */

$options = Blitz_Cache_Options::get();
?>

<div class="blitz-cache-tools">
    <div class="blitz-cache-section">
        <h2><?php esc_html_e('Update Channel', 'blitz-cache'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('Update Source', 'blitz-cache'); ?></th>
                <td>
                    <fieldset>
                        <label>
                            <input type="radio" name="update_channel" value="stable" <?php checked($options['update_channel'] ?? 'stable', 'stable'); ?> />
                            <?php esc_html_e('Stable (WordPress.org)', 'blitz-cache'); ?>
                        </label><br>
                        <label>
                            <input type="radio" name="update_channel" value="stable_github" <?php checked($options['update_channel'] ?? 'stable', 'stable_github'); ?> />
                            <?php esc_html_e('Stable (GitHub)', 'blitz-cache'); ?>
                        </label><br>
                        <label>
                            <input type="radio" name="update_channel" value="beta" <?php checked($options['update_channel'] ?? 'stable', 'beta'); ?> />
                            <?php esc_html_e('Beta (GitHub)', 'blitz-cache'); ?>
                        </label>
                    </fieldset>
                    <p class="description"><?php esc_html_e('Choose where to check for plugin updates', 'blitz-cache'); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="blitz-cache-section">
        <h2><?php esc_html_e('Settings Management', 'blitz-cache'); ?></h2>
        <p>
            <button type="button" class="button" id="btn-export-settings"><?php esc_html_e('Export Settings', 'blitz-cache'); ?></button>
            <label class="button" for="import-settings-file" style="cursor: pointer;">
                <?php esc_html_e('Import Settings', 'blitz-cache'); ?>
            </label>
            <input type="file" id="import-settings-file" accept=".json" style="display: none;" />
        </p>
        <p>
            <button type="button" class="button button-secondary" id="btn-reset-defaults"><?php esc_html_e('Reset to Defaults', 'blitz-cache'); ?></button>
        </p>
    </div>

    <div class="blitz-cache-section">
        <h2><?php esc_html_e('Debug Information', 'blitz-cache'); ?></h2>
        <p>
            <button type="button" class="button" id="btn-toggle-debug"><?php esc_html_e('Show Debug Info', 'blitz-cache'); ?></button>
        </p>
        <div id="debug-info" style="display: none;">
            <table class="widefat">
                <tbody>
                    <tr>
                        <td><strong><?php esc_html_e('PHP Version', 'blitz-cache'); ?></strong></td>
                        <td><?php echo esc_html(PHP_VERSION); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php esc_html_e('WordPress Version', 'blitz-cache'); ?></strong></td>
                        <td><?php echo esc_html(get_bloginfo('version')); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php esc_html_e('Plugin Version', 'blitz-cache'); ?></strong></td>
                        <td><?php echo esc_html(BLITZ_CACHE_VERSION); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php esc_html_e('Cache Directory', 'blitz-cache'); ?></strong></td>
                        <td><?php echo esc_html(BLITZ_CACHE_CACHE_DIR); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php esc_html_e('Cache Directory Writable', 'blitz-cache'); ?></strong></td>
                        <td><?php echo wp_is_writable(BLITZ_CACHE_CACHE_DIR) ? esc_html__('Yes', 'blitz-cache') : esc_html__('No', 'blitz-cache'); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php esc_html_e('WP_CACHE Constant', 'blitz-cache'); ?></strong></td>
                        <td><?php echo defined('WP_CACHE') && WP_CACHE ? esc_html__('Enabled', 'blitz-cache') : esc_html__('Disabled', 'blitz-cache'); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php esc_html_e('Dropin Active', 'blitz-cache'); ?></strong></td>
                        <td><?php echo file_exists(WP_CONTENT_DIR . '/advanced-cache.php') ? esc_html__('Yes', 'blitz-cache') : esc_html__('No', 'blitz-cache'); ?></td>
                    </tr>
                    <tr>
                        <td><strong><?php esc_html_e('Object Cache', 'blitz-cache'); ?></strong></td>
                        <td><?php echo function_exists('wp_cache_get') ? esc_html__('Available', 'blitz-cache') : esc_html__('Not available', 'blitz-cache'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
