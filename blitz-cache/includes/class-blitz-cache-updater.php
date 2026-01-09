<?php
class Blitz_Cache_Updater {
    private string $github_repo = 'ersinkoc/blitz-cache'; // TODO: Set actual repo
    private string $plugin_file;
    private string $plugin_slug = 'blitz-cache';
    private ?object $github_response = null;

    public function __construct() {
        $this->plugin_file = BLITZ_CACHE_PLUGIN_BASENAME;
    }

    public function check_update(object $transient): object {
        if (empty($transient->checked)) {
            return $transient;
        }

        // Only check GitHub updates if channel is not 'stable' (wp.org)
        $options = Blitz_Cache_Options::get();
        if (($options['update_channel'] ?? 'stable') === 'stable') {
            return $transient;
        }

        $release = $this->get_github_release();
        if (!$release) {
            return $transient;
        }

        $current_version = $transient->checked[$this->plugin_file] ?? BLITZ_CACHE_VERSION;

        if (version_compare($release->tag_name, $current_version, '>')) {
            $transient->response[$this->plugin_file] = (object)[
                'slug' => $this->plugin_slug,
                'plugin' => $this->plugin_file,
                'new_version' => $release->tag_name,
                'url' => "https://github.com/{$this->github_repo}",
                'package' => $release->zipball_url,
                'icons' => [],
                'banners' => [],
                'tested' => '',
                'requires_php' => BLITZ_CACHE_MIN_PHP,
            ];
        }

        return $transient;
    }

    public function plugin_info(mixed $result, string $action, object $args): mixed {
        if ($action !== 'plugin_information' || ($args->slug ?? '') !== $this->plugin_slug) {
            return $result;
        }

        $release = $this->get_github_release();
        if (!$release) {
            return $result;
        }

        return (object)[
            'name' => 'Blitz Cache',
            'slug' => $this->plugin_slug,
            'version' => $release->tag_name,
            'author' => '<a href="https://github.com/ersinkoc">Ersin KOÇ</a>',
            'homepage' => "https://github.com/{$this->github_repo}",
            'download_link' => $release->zipball_url,
            'sections' => [
                'description' => 'Zero-config WordPress caching with Cloudflare Edge integration.',
                'changelog' => $this->format_changelog($release->body),
            ],
            'requires' => BLITZ_CACHE_MIN_WP,
            'requires_php' => BLITZ_CACHE_MIN_PHP,
            'tested' => get_bloginfo('version'),
            'last_updated' => $release->published_at,
        ];
    }

    private function get_github_release(): ?object {
        if ($this->github_response !== null) {
            return $this->github_response;
        }

        $options = Blitz_Cache_Options::get();
        $channel = $options['update_channel'] ?? 'stable';

        // Beta channel gets latest release (including prereleases)
        // Stable GitHub channel gets latest non-prerelease
        $endpoint = $channel === 'beta'
            ? "https://api.github.com/repos/{$this->github_repo}/releases"
            : "https://api.github.com/repos/{$this->github_repo}/releases/latest";

        $response = wp_remote_get($endpoint, [
            'timeout' => 10,
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'Blitz-Cache-Updater',
            ],
        ]);

        if (is_wp_error($response)) {
            return null;
        }

        $body = json_decode(wp_remote_retrieve_body($response));

        if ($channel === 'beta' && is_array($body)) {
            $this->github_response = $body[0] ?? null;
        } else {
            $this->github_response = $body;
        }

        return $this->github_response;
    }

    private function format_changelog(string $markdown): string {
        // Basic markdown to HTML conversion
        $html = nl2br(esc_html($markdown));
        $html = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $html);
        $html = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $html);
        $html = preg_replace('/^- (.+)$/m', '<li>$1</li>', $html);
        $html = preg_replace('/(<li>.+<\/li>)/s', '<ul>$1</ul>', $html);
        return $html;
    }
}
