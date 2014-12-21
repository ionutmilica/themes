<?php namespace Ionutmilica\Themes;

class Theme {
    /**
     * @var ThemeFinder
     */
    private $finder;

    /**
     * @param ThemeFinder $finder
     */
    public function __construct(ThemeFinder $finder)
    {
        $this->finder = $finder;
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
}