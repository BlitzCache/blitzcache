<?php
/**
 * Uninstall confirmation modal
 */
?>
<div id="blitz-cache-uninstall-modal" style="display: none;">
    <div class="blitz-cache-modal-overlay"></div>
    <div class="blitz-cache-modal">
        <div class="blitz-cache-modal-header">
            <h2><?php esc_html_e('Uninstall Blitz Cache', 'blitz-cache'); ?></h2>
        </div>
        <div class="blitz-cache-modal-body">
            <p><?php esc_html_e('What would you like to do with your settings and cache data?', 'blitz-cache'); ?></p>
            <p>
                <label>
                    <input type="radio" name="uninstall_choice" value="keep" checked />
                    <?php esc_html_e('Keep settings (recommended)', 'blitz-cache'); ?>
                </label>
                <br>
                <small><?php esc_html_e('Settings will be preserved in case you reinstall later', 'blitz-cache'); ?></small>
            </p>
            <p>
                <label>
                    <input type="radio" name="uninstall_choice" value="delete" />
                    <?php esc_html_e('Delete everything', 'blitz-cache'); ?>
                </label>
                <br>
                <small><?php esc_html_e('Remove all settings, cache files, and plugin data', 'blitz-cache'); ?></small>
            </p>
        </div>
        <div class="blitz-cache-modal-footer">
            <button type="button" class="button" onclick="blitzCache.closeUninstallModal()">
                <?php esc_html_e('Cancel', 'blitz-cache'); ?>
            </button>
            <button type="button" class="button button-primary" onclick="blitzCache.confirmUninstall()">
                <?php esc_html_e('Confirm', 'blitz-cache'); ?>
            </button>
        </div>
    </div>
</div>
