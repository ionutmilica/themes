<?php namespace Ionutmilica\Themes;

use Illuminate\Filesystem\Filesystem;

class ThemeFinder {

    /**
     * @var string
     */
    protected $themesPath;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->themesPath = base_path('themes/');
    }

    /**
     * Get all themes
     */
    public function all()
    {
        $themes = array();

        if ( ! $this->filesystem->isDirectory($path = $this->themesPath))
        {
            return array();
        }

        $directories = $this->filesystem->directories($path);

        foreach ($directories as $theme)
        {
            $themeName = basename($theme);

            $themes[$themeName] = $this->getThemeInfo($themeName);
        }

        return $themes;
    }

    /**
     * Check if a given theme exists
     *
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        $themes = $this->all();

        return isset($themes[$name]);
    }

    /**
     * Get theme location
     *
     * @param $theme
     * @return string
     */
    public function getThemePath($theme)
    {
        return $this->themesPath . $theme;
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