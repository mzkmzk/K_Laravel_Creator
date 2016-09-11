<?php

namespace K_Laravel_Creator\Console\Commands;

use Illuminate\Auth\Console\K_Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArgvInput;

class K_Make_Controller extends GeneratorCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string

    protected $signature = 'make:K';
     */
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:k_controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new K_Controller model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/Stubs/Controller.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['resource', null, InputOption::VALUE_NONE, 'Generate a resource controller class.'],
        ];
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $namespace = $this->getNamespace($name);
        $default_replace = str_replace("use $namespace\Controller;\n", '', parent::buildClass($name));
        //return str_replace("Now_Entity", "Creator_" . $this->argument("name"), $default_replace);
        return str_replace("Now_Entity", $this->argument("name"), $default_replace);
    }

    public function fire()
    {
        //$name = $this->parseName("Creator_" . $this->getNameInput()."_Controller");
        $name = $this->parseName( $this->getNameInput()."_Controller");

        $path = $this->getPath($name);

        if ($this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');

            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($name));

        $this->info($this->type.' created successfully.');
    }


}
