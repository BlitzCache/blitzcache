<?php
class Blitz_Cache_Cloudflare {
    private string $api_url = 'https://api.cloudflare.com/client/v4';
    private array $options;

    public function __construct() {
        $this->options = Blitz_Cache_Options::get_cloudflare();
    }

    public function test_connection(): array {
        $response = $this->request('GET', '/user/tokens/verify');

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'message' => $response->get_error_message(),
            ];
        }

        if ($response['success']) {
            // Update connection status
            Blitz_Cache_Options::set_cloudflare(['connection_status' => 'connected']);
            return [
                'success' => true,
                'message' => __('Connection successful!', 'blitz-cache'),
            ];
        }

        Blitz_Cache_Options::set_cloudflare(['connection_status' => 'error']);
        return [
            'success' => false,
            'message' => $response['errors'][0]['message'] ?? __('Unknown error', 'blitz-cache'),
        ];
    }

    public function get_zones(): array {
        $response = $this->request('GET', '/zones');

        if (is_wp_error($response) || !$response['success']) {
            return [];
        }

        $zones = [];
        foreach ($response['result'] as $zone) {
            $zones[] = [
                'id' => $zone['id'],
                'name' => $zone['name'],
                'status' => $zone['status'],
            ];
        }

        return $zones;
    }

    public function purge_all(): bool {
        if (empty($this->options['zone_id'])) {
            return false;
        }

        $response = $this->request('POST', "/zones/{$this->options['zone_id']}/purge_cache", [
            'purge_everything' => true,
        ]);

        $success = !is_wp_error($response) && ($response['success'] ?? false);

        if ($success) {
            Blitz_Cache_Options::set_cloudflare(['last_purge' => time()]);
            do_action('blitz_cache_cf_purge_success', 'all');
        } else {
            do_action('blitz_cache_cf_purge_failed', 'all', $response);
        }

        return $success;
    }

    public function purge_urls(array $urls): bool {
        if (empty($this->options['zone_id']) || empty($urls)) {
            return false;
        }

        // Cloudflare allows max 30 URLs per request
        $chunks = array_chunk($urls, 30);
        $success = true;

        foreach ($chunks as $chunk) {
            $response = $this->request('POST', "/zones/{$this->options['zone_id']}/purge_cache", [
                'files' => $chunk,
            ]);

            if (is_wp_error($response) || empty($response['success'])) {
                $success = false;
                do_action('blitz_cache_cf_purge_failed', 'urls', $response);
            }
        }

        if ($success) {
            do_action('blitz_cache_cf_purge_success', 'urls', $urls);
        }

        return $success;
    }

    public function get_workers_script(): string {
        // Return the Workers script for edge caching
        return <<<'JAVASCRIPT'
addEventListener('fetch', event => {
  event.respondWith(handleRequest(event.request))
})

async function handleRequest(request) {
  const url = new URL(request.url)

  // Skip cache for admin, login, specific paths
  const skipPaths = ['/wp-admin', '/wp-login', '/wp-json', '/cart', '/checkout', '/my-account']
  if (skipPaths.some(path => url.pathname.startsWith(path))) {
    return fetch(request)
  }

  // Skip for logged-in users (check cookie)
  const cookies = request.headers.get('Cookie') || ''
  if (cookies.includes('wordpress_logged_in_') || cookies.includes('woocommerce_cart_hash')) {
    return fetch(request)
  }

  // Only cache GET requests
  if (request.method !== 'GET') {
    return fetch(request)
  }

  // Check cache
  const cache = caches.default
  let response = await cache.match(request)

  if (!response) {
    response = await fetch(request)

    // Only cache successful HTML responses
    const contentType = response.headers.get('Content-Type') || ''
    if (response.status === 200 && contentType.includes('text/html')) {
      const newResponse = new Response(response.body, response)
      newResponse.headers.set('X-Blitz-Edge-Cache', 'MISS')
      newResponse.headers.set('Cache-Control', 'public, max-age=86400')

      event.waitUntil(cache.put(request, newResponse.clone()))
      return newResponse
    }

    return response
  }

  // Add cache hit header
  const cachedResponse = new Response(response.body, response)
  cachedResponse.headers.set('X-Blitz-Edge-Cache', 'HIT')
  return cachedResponse
}
JAVASCRIPT;
    }

    private function request(string $method, string $endpoint, array $data = []): array|\WP_Error {
        $args = [
            'method' => $method,
            'timeout' => 30,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->options['api_token'],
                'Content-Type' => 'application/json',
            ],
        ];

        if (!empty($data)) {
            $args['body'] = wp_json_encode($data);
        }

        $response = wp_remote_request($this->api_url . $endpoint, $args);

        if (is_wp_error($response)) {
            return $response;
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }
}
