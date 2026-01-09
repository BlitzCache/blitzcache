<?php
/**
 * Cloudflare partial
 */

$cf_options = Blitz_Cache_Options::get_cloudflare();
?>

<div class="blitz-cache-cloudflare">
    <div class="blitz-cache-section">
        <h2><?php esc_html_e('Cloudflare API Configuration', 'blitz-cache'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="cf_api_token"><?php esc_html_e('API Token', 'blitz-cache'); ?></label>
                </th>
                <td>
                    <input type="password" id="cf_api_token" name="api_token" value="<?php echo esc_attr($cf_options['api_token'] ?? ''); ?>" class="regular-text" />
                    <button type="button" class="button" id="btn-test-connection"><?php esc_html_e('Test Connection', 'blitz-cache'); ?></button>
                    <p class="description">
                        <?php esc_html_e('Create an API token in your Cloudflare dashboard with Zone:Zone:Read and Zone:Cache:Edit permissions.', 'blitz-cache'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cf_zone_id"><?php esc_html_e('Zone', 'blitz-cache'); ?></label>
                </th>
                <td>
                    <select id="cf_zone_id" name="zone_id">
                        <option value=""><?php esc_html_e('Select a zone', 'blitz-cache'); ?></option>
                    </select>
                    <p class="description"><?php esc_html_e('Select the domain you want to manage', 'blitz-cache'); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <div class="blitz-cache-section">
        <h2><?php esc_html_e('Workers Edge Cache', 'blitz-cache'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row"><?php esc_html_e('Enable Workers', 'blitz-cache'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" id="cf_workers_enabled" name="workers_enabled" value="1" <?php checked($cf_options['workers_enabled'] ?? false); ?> />
                        <?php esc_html_e('Deploy Cloudflare Worker for edge caching', 'blitz-cache'); ?>
                    </label>
                    <p class="description"><?php esc_html_e('Caches content at Cloudflare\'s edge locations for global performance', 'blitz-cache'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cf_workers_route"><?php esc_html_e('Workers Route', 'blitz-cache'); ?></label>
                </th>
                <td>
                    <input type="text" id="cf_workers_route" name="workers_route" value="<?php echo esc_attr($cf_options['workers_route'] ?? ''); ?>" class="regular-text" placeholder="example.com/*" />
                    <p class="description"><?php esc_html_e('Route pattern for the Worker (e.g., example.com/*)', 'blitz-cache'); ?></p>
                </td>
            </tr>
        </table>

        <div id="workers-script-container" style="display: none;">
            <h3><?php esc_html_e('Workers Script', 'blitz-cache'); ?></h3>
            <p><?php esc_html_e('Copy this script and deploy it as a Cloudflare Worker:', 'blitz-cache'); ?></p>
            <textarea id="workers_script" readonly rows="30" class="large-text" style="font-family: monospace;"></textarea>
            <p>
                <button type="button" class="button" id="btn-copy-script"><?php esc_html_e('Copy Script', 'blitz-cache'); ?></button>
            </p>
        </div>
    </div>

    <div class="blitz-cache-section">
        <h2><?php esc_html_e('Setup Instructions', 'blitz-cache'); ?></h2>
        <ol>
            <li><?php esc_html_e('Create a Cloudflare API token with Zone:Zone:Read and Zone:Cache:Edit permissions', 'blitz-cache'); ?></li>
            <li><?php esc_html_e('Enter your API token above and click "Test Connection"', 'blitz-cache'); ?></li>
            <li><?php esc_html_e('Select your zone from the dropdown', 'blitz-cache'); ?></li>
            <li><?php esc_html_e('Optional: Enable Workers edge caching and deploy the provided script', 'blitz-cache'); ?></li>
            <li><?php esc_html_e('Save settings', 'blitz-cache'); ?></li>
        </ol>
    </div>

    <p class="submit">
        <button type="button" class="button-primary" id="btn-save-cloudflare"><?php esc_html_e('Save Cloudflare Settings', 'blitz-cache'); ?></button>
    </p>
</div>
