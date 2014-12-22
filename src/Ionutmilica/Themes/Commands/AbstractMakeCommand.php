<?php namespace Ionutmilica\Themes\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

abstract class AbstractMakeCommand extends Command {
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    /**
     * Make directories
     *
     * @param $dirs
     * @param null $callback
     */
    public function makeDirs($dirs, $callback = null)
    {
        if (is_callable($callback))
        {
            array_walk($dirs, $callback);
        }

        foreach ($dirs as $dir)
        {
            $this->filesystem->makeDirectory($dir);
        }
    }

    /**
     * Create files
     *
     * @param $files
     * @param string $content
     * @param null $callback
     */
    public function makeFiles($files, $content = '', $callback = null)
    {
        if (is_callable($callback))
        {
            array_walk($files, $callback);
        }

        foreach ($files as $file)
        {
            $this->filesystem->put($file, $content);
        }
    }

    /**
     * Replace stub vars and move it to the marked location
     *
     * @param $name
     * @param $path
     * @param array $data
     */
    protected function moveStub($name, $path, array $data)
    {
        $content = $this->compileStub($name, $data);
        $this->filesystem->put($path.'/'.$name.'.php', $content);
    }

    /**
     * Prepare stub for creation
     *
     * @param $file
     * @param array $data
     * @return mixed|string
     */
    protected function compileStub($file, array $data)
    {
        $content = file_get_contents(__DIR__.'/stubs/'.$file.'.txt');

        foreach ($data as $var => $rep)
        {
            $content = str_replace('$$'.$var.'$$', $rep, $content);
        }

        return $content;
    }
}