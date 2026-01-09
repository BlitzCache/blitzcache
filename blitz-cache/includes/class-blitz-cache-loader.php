<?php
class Blitz_Cache_Loader {
    protected array $actions = [];
    protected array $filters = [];

    public function add_action(string $hook, object $component, string $callback, int $priority = 10, int $args = 1): void {
        $this->actions[] = compact('hook', 'component', 'callback', 'priority', 'args');
    }

    public function add_filter(string $hook, object $component, string $callback, int $priority = 10, int $args = 1): void {
        $this->filters[] = compact('hook', 'component', 'callback', 'priority', 'args');
    }

    public function run(): void {
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['args']);
        }
        foreach ($this->actions as $hook) {
            add_action($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['args']);
        }
    }
}
