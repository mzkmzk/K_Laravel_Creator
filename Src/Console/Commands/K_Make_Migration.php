<?php

namespace K_Laravel_Creator\Console\Commands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Schema\Blueprint;



class K_Make_Migration extends GeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:k_migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new K_Migration  class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Migration';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $date = date("Y_m_d");
        $random = random_int(100000,999999);

        $raw_name =$date."_" .$random ."_Create_". $this->getNameInput();
        $name = $this->parseName($raw_name);

        $path =  $this->laravel->basePath()."/database/migrations/".$raw_name.".php";
        //if ($this->alreadyExists($this->getNameInput())) {
        //    $this->error($this->type.' already exists!');
        //    return false;
        //}

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
        return __DIR__.'/Stubs/Migration/Create.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
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
        $build_class = str_replace("Dummy_Class", "Create".$this->argument("name"), $build_class);
        $build_class = str_replace("dummy_method", $this->dummyMethod($this->argument("name")), $build_class);
        $build_class = str_replace("Dummy_Table", $this->argument("name"), $build_class);
        $entity = "App\\Entities\\" . $this->argument("name") . "_Entity";
        $build_class = str_replace("dummy_attribute",$this->build_attribute($entity),$build_class);
        return $build_class;
    }

    private function dummyMethod($table_name) {
        if (\Schema::hasTable($table_name)) {
            return "table";
        }else {
            return "create";
        }
    }

    protected function build_attribute(){
        $attribute_string = "";
        $entity = "App\\Entities\\" . $this->argument("name") . "_Entity";
        foreach ($entity::get_attribute() as $key => $attribute){
            if (\Schema::hasColumn($this->argument("name"), $key)) {
                continue;
            }
            switch ($attribute['type']){
                case "id" :
                    if ($key === "id") {
                        $attribute_string .= "\$table->increments('" .$key ."');\n\n";
                    } else {
                        $attribute_string .= "\$table->unsignedInteger('" .$key ."');\n\n";
                    }
                    break;
                //case "string" || "url": 这样date_time也会变string后面基本都会变string
                case "string":
                    $attribute_string .= "\$table->string('".$key."','" .$attribute['length'] ."');\n\n";
                    break;
                case "date_time" :
                    $attribute_string .= "\$table->dateTime('" .$key ."');\n\n";
                    break;
                case "int" :
                    $attribute_string .= "\$table->integer('" .$key ."');\n\n";
                    break;
                case "url" :
                    $attribute_string .= "\$table->string('".$key."','" .$attribute['length'] ."');\n\n";
                    break;


            }
        }
        return $attribute_string;

    }

}
