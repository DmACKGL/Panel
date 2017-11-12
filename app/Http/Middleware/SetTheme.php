<?php

namespace Pterodactyl\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Krucas\Settings\Settings;

class SetTheme
{
    /**
     * @var \Krucas\Settings\Settings
     */
    private $settings;

    /**
     * @var \Igaster\LaravelTheme\Themes
     */
    private $themes;

    /**
     * SetTheme constructor.
     *
     * @param \Krucas\Settings\Settings $settings
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
        $this->themes = app()->make('igaster.themes');
    }

    /**
     * Set the user's selected theme. If one is not selected, use
     * the system default.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && ! is_null($user->theme)) {
            if ($this->themes->exists($user->theme)) {
                $this->themes->set($user->theme);

                return $next($request);
            }
        }

        $this->themes->set($this->settings->get('default_theme', 'pterodactyl'));

        return $next($request);
    }
}
