<?php namespace Ionutmilica\Themes;

use Illuminate\View\Factory as View;
use Illuminate\Foundation\Application;
use Ionutmilica\Themes\Config as Meta;
use Illuminate\Config\Repository as Config;
use Illuminate\Translation\Translator as Lang;

class Theme {

    /**
     * Current active theme
     *
     * @var
     */
    protected $active;

    /**
     * Theme finder
     *
     * @var ThemeFinder
     */
    private $finder;

    /**
     * View factory
     *
     * @var View
     */
    private $views;

    /**
     * Config repository
     *
     * @var Config
     */
    private $config;

    /**
     * Translator
     *
     * @var Lang
     */
    private $lang;
    /**
     * @var Meta
     */
    private $meta;

    /**
     * @param Meta $meta
     * @param ThemeFinder $finder
     * @param View $views
     * @param Config $config
     * @param Lang $lang
     */
    public function __construct(Meta $meta, ThemeFinder $finder, View $views, Config $config, Lang $lang)
    {
        $this->meta = $meta;
        $this->finder = $finder;
        $this->views = $views;
        $this->config = $config;
        $this->lang = $lang;
    }

    /**
     * Register active theme.
     * Add it to composer autoloader, fire theme service provider and register resources
     *
     * @param Application $app
     * @return bool
     */
    public function register(Application $app)
    {
        $theme = $this->getActive();

        if ( ! $this->has($theme)) {
            return false;
        }

        foreach (array('config', 'views', 'lang') as $hint)
        {
            $this->$hint->addNamespace($theme, $this->getThemeComponentPath($theme, $hint));
        }

        $this->registerNamespace($theme, $app);

        return true;
    }

    /**
     * Register theme service provider to composer
     *
     * @param Application $app
     */
    public function registerNamespace($theme, Application $app)
    {
        $loader = require base_path() . '/vendor/autoload.php';

        $namespace = 'Themes\\'.ucfirst($theme);
        $loader->setPsr4($namespace . "\\", $this->finder->getThemePath($theme));

        $provider = $namespace.'\\ThemeServiceProvider';
        $app->register(new $provider($app));
    }

    /**
     * Get path for a specific component from the theme
     * Ex: views, lang, config
     *
     * @param $theme
     * @param $component
     * @return string
     */
    public function getThemeComponentPath($theme, $component)
    {
        return $this->finder->getThemePath($theme) . '/Resources/' . $component;
    }

    /**
     * Get current theme
     *
     * @return mixed
     */
    public function getActive()
    {
        return $this->meta->get('theme');
    }

    /**
     * Set current theme
     *
     * @param $theme
     */
    public function setActive($theme)
    {
        if ($this->has($theme)) {
            $this->meta->set('theme', $theme);
        }
    }

    /**
     * Check if a given theme exists
     *
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return $this->finder->has($name);
    }

    /**
     * Get all themes
     *
     * @return array
     */
    public function all()
    {
        return $this->finder->all();
    }

    /**
     * Get asset for the current theme
     *
     * @param $asset
     * @return string
     */
    public function asset($asset)
    {
        return asset('themes/' . $this->active . '/' . $asset);
    }

    /**
     * Get view from current theme.
     *
     * @param $view
     * @param array $data
     * @param array $mergeData
     * @return mixed
     */
    public function view($view, $data = array(), $mergeData = array())
    {
        return $this->views->make($this->getThemeNamespace($view), $data, $mergeData);
    }
    /**
     * Get config from current theme.
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return $this->config->get($this->getThemeNamespace($key), $default);
    }
    /**
     * Get lang from current theme.
     *
     * @param $key
     * @param array $replace
     * @param null $locale
     * @return string
     */
    public function lang($key, $replace = array(), $locale = null)
    {
        return $this->lang->get($this->getThemeNamespace($key), $replace, $locale);
    }

    /**
     * Get theme namespace
     *
     * @param $key
     * @return string
     */
    public function getThemeNamespace($key)
    {
        return $this->getActive(). '::' . $key;
    }
}