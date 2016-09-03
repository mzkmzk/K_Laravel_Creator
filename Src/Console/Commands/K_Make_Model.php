<?php

namespace K_Laravel_Creator\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Filesystem\Filesystem;



class K_Make_Model extends GeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:k_model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new K_Eloquent model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $name = $this->parseName($this->getNameInput()."_Model");

        $path = $this->getPath($name);

        if ($this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');

            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($name));

        $this->info($this->type.' created successfully.');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/Stubs/Model/Model.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace."\\Models";
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            //['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model.'],
        ];
    }

    protected function buildClass($name)
    {
        $build_class = parent::buildClass($name);
        $build_class = str_replace("Dummy_Table", $this->argument("name"), $build_class);
        $build_class = str_replace("dummy_belong_to",$this->build_belong_to(),$build_class);
        $build_class = str_replace("dummy_has_many",$this->build_has_many(),$build_class);
        return $build_class;
    }

    protected function build_belong_to(){

        $belong_to_string = "";
        $entity = "App\\Entities\\" . $this->argument("name") . "_Entity";
        if(isset($entity::$belong_to)){
            foreach ($entity::$belong_to as $key => $value){
                $belong_to_string .=
                    str_replace
                    (
                        "dummy_belong_to_entity"
                        ,strtolower($value)
                        ,str_replace("Dump_Belong_To_Entity",$value,$this->files->get(__DIR__.'/Stubs/Model/Has_Many.stub'))
                    );
            }
            return $belong_to_string;
        }else{
            return "";
        }
    }

    protected function build_has_many(){
        $has_many_string = "";
        $entity = "App\\Entities\\" . $this->argument("name") . "_Entity";
        if(isset($entity::$has_many)){
            foreach ($entity::$has_many as $key => $value){
                $has_many_string .=
                    str_replace
                    (
                        "dummy_has_many_entity"
                        ,strtolower($value)
                        ,str_replace("Dump_Has_Many_Entity",ucwords($value),$this->files->get(__DIR__.'/Stubs/Model/Has_Many.stub'))
                    );
            }
            return $has_many_string;
        }else{
            return "";
        }
    }

}
