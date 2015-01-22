<?php namespace Ionutmilica\Themes;

class Config {

    /**
     * @var string
     */
    protected $filename = null;

    /**
     * @var array
     */
    protected $defaults = array('theme' => 'standard');
    
    /**
     * @var array
     */
    protected $data = null;

    /**
     * A flag for autosaving
     *
     * @var bool
     */
    protected $wasChanged = false;


    public  function __construct()
    {
        $this->filename = storage_path('meta/themes.json');
        $this->parse();
    }

    /**
     * Parse the meta file for getting the info
     * @return void
     */
    public function parse()
    {
        if ($this->data) {
            return ;
        }

        $data = null;

        if (is_file($this->getMeta())) {
            $data = json_decode(file_get_contents($this->getMeta()), true);
        }

        if ($data) {
            $this->data = $data;
        } else {
            $this->data = $this->defaults;
        }
    }

    /**
     * Save config to the meta file
     */
    public function save()
    {
        if ($this->wasChanged) {
            file_put_contents($this->filename, json_encode($this->data));
            $this->wasChanged = false;
        }
    }

    /**
     * Set a propriety to the meta file
     *
     * @param $name
     * @param $value
     */
    public function set($name, $value)
    {
        if ($this->get($name) !== $value) {
            $this->data[$name] = $value;
            $this->wasChanged = true;
        }
    }

    /**
     * Get a propriety from theme meta file
     *
     * @param $name
     * @param null $default
     * @return null
     */
    public function get($name, $default = null)
    {
        return $this->has($name) ? $this->data[$name] : $default;
    }

    /**
     * Check if theme config file has a propriety
     *
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * Get all config proprieties
     *
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * Set a new config file
     *
     * @param $file
     */
    public function setMeta($file)
    {
        $this->filename = $file;
    }

    /**
     * Get the config file
     *
     * @return string
     */
    public function getMeta()
    {
        return $this->filename;
    }

    /**
     * Save config data right before object memory deallocation.
     */
    public function __destruct()
    {
        $this->save();
    }
}