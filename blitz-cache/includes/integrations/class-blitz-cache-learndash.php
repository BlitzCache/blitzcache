<?php
class Blitz_Cache_LearnDash {
    public function init(Blitz_Cache_Loader $loader): void {
        add_filter('blitz_cache_should_cache', [$this, 'should_cache']);

        $loader->add_action('save_post_sfwd-courses', $this, 'on_course_update');
        $loader->add_action('save_post_sfwd-lessons', $this, 'on_lesson_update');
    }

    public function should_cache(bool $should_cache): bool {
        if (!$should_cache) return false;

        // Don't cache user-specific content
        if (is_singular(['sfwd-lessons', 'sfwd-topic', 'sfwd-quiz'])) {
            if (is_user_logged_in()) {
                return false;
            }
        }

        return true;
    }

    public function on_course_update(int $post_id): void {
        $purge = new Blitz_Cache_Purge();
        $purge->purge_url(get_permalink($post_id));
        $purge->purge_url(get_post_type_archive_link('sfwd-courses'));
    }

    public function on_lesson_update(int $post_id): void {
        $purge = new Blitz_Cache_Purge();
        $purge->purge_url(get_permalink($post_id));

        // Purge parent course
        $course_id = get_post_meta($post_id, 'course_id', true);
        if ($course_id) {
            $purge->purge_url(get_permalink($course_id));
        }
    }
}
