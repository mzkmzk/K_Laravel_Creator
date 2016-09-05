<?php

namespace K_Laravel_Creator\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Support\Facades\Config;

class K_Make_Command extends Command
{
    use AppNamespaceDetectorTrait;

    protected $name = 'make:k_command';

    protected $description = 'Make a series file';

    public function fire()
    {
        $entities = Config::get('creator.entities');
        foreach($entities as $key=>$value){
            $this->call('make:k_controller',['name' => $value]);
            $this->call('make:k_model',['name' => $value]);
            $this->call('make:k_migration',['name' => $value]);
            $this->call('make:k_factory',['name' => $value]);
            $this->call('make:k_seeder',['name' => $value]);
        }
        $this->call("migrate");
        $this->call("db:seed");


        $this->comment('Authentication scaffolding generated successfully!');
    }

}
