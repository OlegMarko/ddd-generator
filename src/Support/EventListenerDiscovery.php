<?php

namespace Fixik\DddGenerator\Support;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;

class EventListenerDiscovery
{
    public static function register(): void
    {
        $map = self::getEventListenerMap();

        foreach ($map as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    public static function rebuildCache(): array
    {
        $modulesPath = config('ddd.base_path', app_path('Modules'));
        $eventListenerMap = [];
        $classMap = [];

        if (!$modulesPath || !is_dir($modulesPath)) {
            return [];
        }

        foreach (File::directories($modulesPath) as $moduleDir) {
            $module = basename($moduleDir);

            $listenersPath = "$moduleDir/Application/Listeners";
            $eventsPath = "$moduleDir/Domain/Events";

            if (!is_dir($listenersPath)) {
                continue;
            }

            foreach (File::files($listenersPath) as $file) {
                $listenerName = $file->getBasename('.php');
                $listenerClass = NamespaceResolver::listener($module, $listenerName);

                $classMap[$listenerClass] = $file->getPathname();

                if (!ClassDiscovery::load($listenerClass, $file->getPathname())) {
                    continue;
                }

                $eventClass = self::resolveEventFromListener($listenerClass, $module);
                if (!$eventClass) {
                    continue;
                }

                $eventFile = "$eventsPath/" . class_basename($eventClass) . '.php';
                if (!File::exists($eventFile)) {
                    continue;
                }

                $classMap[$eventClass] = $eventFile;

                if (!ClassDiscovery::load($eventClass, $eventFile)) {
                    continue;
                }

                $eventListenerMap[$eventClass][] = $listenerClass;
            }
        }

        ClassMapCache::write($classMap);
        self::writeCache($eventListenerMap);

        return $eventListenerMap;
    }

    private static function getEventListenerMap(): array
    {
        if (
            config('ddd.event_listeners.cache', true) &&
            File::exists(config('ddd.event_listeners.cache_path', base_path('bootstrap/cache/ddd-event-listeners.php')))
        ) {
            return require config('ddd.event_listeners.cache_path', base_path('bootstrap/cache/ddd-event-listeners.php'));
        }

        return self::rebuildCache();
    }

    private static function writeCache(array $map): void
    {
        if (!config('ddd.event_listeners.cache', true)) {
            return;
        }

        $path = config('ddd.event_listeners.cache_path', base_path('bootstrap/cache/ddd-event-listeners.php'));

        if (!$path) {
            return;
        }

        File::ensureDirectoryExists(dirname($path));

        File::put(
            $path,
            '<?php return ' . var_export($map, true) . ';'
        );
    }

    private static function resolveEventFromListener(string $listener, string $module): ?string
    {
        $short = class_basename($listener);

        if (!str_ends_with($short, 'Listener')) {
            return null;
        }

        $event = str_replace('Listener', '', $short);

        return NamespaceResolver::event($module, $event);
    }
}
