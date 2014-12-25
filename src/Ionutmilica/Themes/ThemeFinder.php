<?php namespace Ionutmilica\Themes;

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
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        $themes = $this->all();

        return isset($themes[$name]);
    }

    /**
     * Save in the config the standard theme
     *
     * @param $theme
     */
    public function setCurrent($theme)
    {
        $config = $this->getConfigData();

        if ($config['current'] == $theme)
            return;

        $config['current'] = $theme;

        $this->setConfigData($config);
    }

    /**
     * Get current theme
     *
     * @return mixed
     */
    public function getCurrent()
    {
        $config = $this->getConfigData();

        return $config['current'];
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
			$file = $this->getMetaConfigFile();
			
			if ($this->filesystem->isFile($file))
			{
			    $this->config = json_decode(file_get_contents($file), true);	
			}

            if ( ! $this->config)
            {
                $this->config = array('current' => 'standard');
            }
        }

        return $this->config;
    }

    /**
     * Save config data to meta file
     *
     * @param array $data
     */
    protected function setConfigData(array $data)
    {
        $this->config = $data;
        file_put_contents($this->getMetaConfigFile(), json_encode($data));
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