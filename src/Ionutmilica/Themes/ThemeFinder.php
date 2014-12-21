<?php namespace Ionutmilica\Themes;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;

class ThemeFinder {
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Repository
     */
    private $config;

    /**
     * @param Filesystem $filesystem
     * @param Repository $config
     */
    public function __construct(Filesystem $filesystem, Repository $config)
    {
        $this->filesystem = $filesystem;
        $this->config = $config;
    }

    /**
     * Get all themes
     */
    public function all()
    {
        $themes = array();
        $path = base_path() . '/themes/';

        if ( ! $this->filesystem->isDirectory($path))
        {
            return array();
        }

        $directories = $this->filesystem->directories($path);

        foreach ($directories as $theme)
        {
            $themeName = basename($theme);

            $themes[$themeName] = require $theme . '/theme.php';
        }

        return $themes;
    }

    /**
     * Check if a given theme exists
     *
     * @param $theme
     * @return bool
     */
    public function has($theme)
    {
        return in_array($theme, $this->all());
    }

    /**
     * Get theme location
     *
     * @param $theme
     * @return string
     */
    public function getThemePath($theme)
    {
        return $this->getThemesPath() . $theme;
    }

    /**
     * Get path for themes.
     *
     * @return mixed
     */
    public function getThemesPath()
    {
        return $this->config->get('themes::config.path');
    }
}