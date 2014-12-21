<?php namespace Ionutmilica\Themes;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;

class ThemeFinder {
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array
     */
    private $config = null;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Get all themes
     */
    public function all()
    {
        $themes = array();

        if ( ! $this->filesystem->isDirectory($path = $this->getThemesPath()))
        {
            return array();
        }

        $directories = $this->filesystem->directories($path);

        foreach ($directories as $theme)
        {
            $themeName = basename($theme);

            $themes[$themeName] = $this->getThemeInfo($themeName);
            $themes[$themeName]['enabled'] = $themeName == $this->getCurrent();
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
     * Save in the config the default theme
     *
     * @param $theme
     */
    public function setCurrent($theme)
    {
        $config = $this->getConfigData() ?: array('current' => $theme);

        if ($config['current'] == $theme)
            return;

        $config['current'] = $theme;
        $this->config = $config;

        file_put_contents($this->getMetaConfigFile(), json_encode($config));
    }

    /**
     * Get current theme
     *
     * @return mixed
     */
    public function getCurrent()
    {
        $config = $this->getConfigData();

        return $config ? $config['current']: 'default';
    }

    /**
     * Get the config for the themes
     *
     * @return string
     */
    protected function getConfigData()
    {
        if ( ! $this->config)
        {
            $this->config = json_decode(file_get_contents($this->getMetaConfigFile()), true);
        }

        return $this->config;
    }

    /**
     * Get themes configuration file
     *
     * @return string
     */
    public function getMetaConfigFile()
    {
        return storage_path('meta/themes.json');
    }

    /**
     * Get path for themes.
     *
     * @return mixed
     */
    public function getThemesPath()
    {
        return base_path('themes/');
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
     * Get theme init file
     *
     * @param $theme
     * @return string
     */
    public function getThemeInfo($theme)
    {
        return require $this->getThemePath($theme) . '/theme.php';
    }
}