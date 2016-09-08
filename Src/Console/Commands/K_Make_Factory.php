<?php

namespace K_Laravel_Creator\Console\Commands;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Filesystem\Filesystem;



class K_Make_Factory extends GeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:k_factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new K_Factory  class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Factory';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $name = $this->parseName($this->getNameInput());

        $path =  $this->laravel->basePath()."/database/factories/ModelFactory.php";

        if (!$this->files->exists($path)) {
            $this->makeDirectory($path);
            $this->files->put($path, $this->buildClass($name));
            /*file_put_contents(
                $path,
                file_get_contents(__DIR__.'/Stubs/Factory/Factory.stub'),
                FILE_APPEND
            );*/
        }

        file_put_contents(
            $path,
            $this->append_factory($name),
            FILE_APPEND
        );

        $this->info($this->type.' created successfully.');
    }

    protected function append_factory($name){
        $stub = file_get_contents(__DIR__.'/Stubs/Factory/Model_Factory.stub');
        $stub = str_replace("Dump_Entity",ucwords($name),$stub);
        $stub = str_replace("dummy_attribute",$this->get_attribute_factory(),$stub);
        return $stub;
    }

    protected function get_attribute_factory() {
        $attribute_factory_string ="";

        $entity = "App\\Entities\\" . $this->argument("name") . "_Entity";
        foreach ($entity::get_attribute() as $key => $attribute){
            switch ($attribute['type']){
                case "string" : //后续可添加类型 姓名 email 长度等
                    $attribute_factory_string .= "'$key' => \$faker->text(30),\n";
                    break;
                case "date_time" :
                    if($key !== "deleted_at")
                    $attribute_factory_string .= "'$key' => \$faker->dateTime(),\n";
                    break;
                case "url" : //后续可添加video 多媒体
                    $attribute_factory_string .= "'$key' => \$faker->imageUrl(640,480),\n";
                    break;
                case "int" : //后续可添加范围
                    $attribute_factory_string .= "'$key' => \$faker->numberBetween(),\n";
                    break;
                case "id" :
                    if($key !== "id"){
                        $relation_name = ucwords(substr($key,0,-3));
                        $attribute_factory_string .= "'$key' => \$faker->randomElement(get_all_id(new \\App\\Models\\".$relation_name."_Model())),\n";
                    }
                    break;
            }
        }
        return $attribute_factory_string;
    }

    protected function buildClass($name)
    {
        return $this->files->get(__DIR__.'/Stubs/Factory/Factory.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return "App\\Models";
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


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {

    }
}
