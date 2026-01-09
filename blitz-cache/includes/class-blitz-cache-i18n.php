<?php
class Blitz_Cache_I18n {
    public function load_plugin_textdomain(): void {
        load_plugin_textdomain(
            'blitz-cache',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}
