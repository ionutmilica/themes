<?php namespace Ionutmilica\Themes;

use Illuminate\Filesystem\Filesystem;

class ThemeFinder {
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
            $themes[] = basename($theme);
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
}