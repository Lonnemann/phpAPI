<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

class MVC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:mvc {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a Model, a view, a migration and a controller and adds it to the routes.';

    /**
     * Generates files and their corresponing template
     *
     * @var string
     */
    protected $rewriteFiles =   [
                                    'app/::Name.php'                                =>  'vendor/phpapi/mvcgenerator/src/templates/model.php',
                                    'app/Http/Controllers/::NameController.php'     =>  'vendor/phpapi/mvcgenerator/src/templates/controller.php',
                                    'resources/views/::name/create.blade.php'       =>  'vendor/phpapi/mvcgenerator/src/templates/create.blade.php',
                                    'resources/views/::name/edit.blade.php'         =>  'vendor/phpapi/mvcgenerator/src/templates/edit.blade.php',
                                    'resources/views/::name/index.blade.php'        =>  'vendor/phpapi/mvcgenerator/src/templates/index.blade.php',
                                    'resources/views/::name/master.blade.php'       =>  'vendor/phpapi/mvcgenerator/src/templates/master.blade.php',
                                    'resources/views/::name/show.blade.php'         =>  'vendor/phpapi/mvcgenerator/src/templates/show.blade.php'
        
                                ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Setting name 
        $name       =   strtolower($this->argument('name'));
        if ($name===null)
            $name   =   strtolower($this->ask('Please define the name of the package'));
        
        //Create Model and Creation
        $this->call('make:model',['name'=> ucfirst($name),'-m'=>true]);
        
        //Create a RESTfulController
        $this->call('make:controller',['name'=>ucfirst($name).'Controller']);

       foreach ($this->rewriteFiles as $key => $value) {
            $this->rewriteFile($name,$key,$value,'w');
        } 
        
        //  Add a route to the Controller into the routes.php
        if (file_exists('app/Http/routes.php'))
            if (!(strpos(file_get_contents('app/Http/routes.php'),"Route::resource('/".strtolower($name)."','".ucfirst($name)."Controller')")))
            if ($handle=fopen('app/Http/routes.php','a+'))
                if (fwrite($handle,"\n"."Route::resource('/".strtolower($name)."','".ucfirst($name)."Controller');"))
                    if (fclose($handle))
                        $this->info("Added path to routes.php");
        
        /**/

    }

    protected function rewriteFile($name,$file,$template,$type='w'){
        $file=str_replace('::name',strtolower($name),$file);
        $file=str_replace('::Name',ucfirst($name),$file);
        $newContent=file_get_contents($template);  
        $newContent=str_replace('::name',strtolower($name),$newContent);
        $newContent=str_replace('::Name',ucfirst($name),$newContent);
        if (strpos($file,'sources/views/'))
        {
            if (!(file_exists("resources/views/$name")))
                mkdir("resources/views/$name");
            //$type="a+";
        }
            
        if ($handle=fopen($file,$type))
            if (fwrite($handle,$newContent))
                if (fclose($handle))
                    $this->info("Added Content to ".$file);

    }   
}
