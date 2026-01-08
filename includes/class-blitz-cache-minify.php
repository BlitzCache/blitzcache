<?php
class Blitz_Cache_Minify {
    public function minify(string $html): string {
        // Apply filter to skip minification
        if (!apply_filters('blitz_cache_should_minify', true, $html)) {
            return $html;
        }

        // Preserve inline scripts and styles
        $preserved = [];
        $index = 0;

        // Preserve <pre>, <code>, <textarea>, <script>, <style>
        $preserve_tags = ['pre', 'code', 'textarea', 'script', 'style'];
        foreach ($preserve_tags as $tag) {
            $html = preg_replace_callback(
                "/<{$tag}[^>]*>.*?<\/{$tag}>/is",
                function($match) use (&$preserved, &$index) {
                    $placeholder = "<!--BLITZ_PRESERVE_{$index}-->";
                    $preserved[$placeholder] = $match[0];
                    $index++;
                    return $placeholder;
                },
                $html
            );
        }

        // Remove HTML comments (except IE conditionals and preserved placeholders)
        $html = preg_replace('/<!--(?!\\[|BLITZ_PRESERVE_).*?-->/s', '', $html);

        // Remove whitespace between tags
        $html = preg_replace('/>\s+</', '> <', $html);

        // Remove multiple spaces
        $html = preg_replace('/\s{2,}/', ' ', $html);

        // Remove whitespace around block elements
        $block_elements = 'html|head|body|div|section|article|header|footer|nav|aside|main|p|h[1-6]|ul|ol|li|table|tr|td|th|form|fieldset';
        $html = preg_replace("/\s*(<\/?(?:{$block_elements})[^>]*>)\s*/i", '$1', $html);

        // Remove newlines and tabs (convert to single space)
        $html = str_replace(["\r\n", "\r", "\n", "\t"], ' ', $html);

        // Clean up multiple spaces again
        $html = preg_replace('/\s{2,}/', ' ', $html);

        // Restore preserved content
        foreach ($preserved as $placeholder => $content) {
            $html = str_replace($placeholder, $content, $html);
        }

        return trim($html);
    }
}
