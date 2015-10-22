<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

class MVC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:mvc {name} {--foundation} {--bootstrap} {--override} {--table=false} {--private}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a Model, a view, a migration and a controller and adds it to the routes.';
    
    /**
     * Template Location definitions
     *
     * @var array ['output'=>'input']
     */

    protected $rewriteFiles =   [
                                    'app/::Name.php'                                =>  'vendor/phpapi/mvcgenerator/src/templates/model.php',
                                    'app/Http/Controllers/::NameController.php'     =>  'vendor/phpapi/mvcgenerator/src/templates/controller.php',
                                    'resources/views/::name/create.blade.php'       =>  'vendor/phpapi/mvcgenerator/src/templates/::engine/create.blade.php',
                                    'resources/views/::name/edit.blade.php'         =>  'vendor/phpapi/mvcgenerator/src/templates/::engine/edit.blade.php',
                                    'resources/views/::name/index.blade.php'        =>  'vendor/phpapi/mvcgenerator/src/templates/::engine/index.blade.php',
                                    'resources/views/::name/master.blade.php'       =>  'vendor/phpapi/mvcgenerator/src/templates/::engine/master.blade.php',
                                    'resources/views/::name/show.blade.php'         =>  'vendor/phpapi/mvcgenerator/src/templates/::engine/show.blade.php'
        
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
        // Template Engine Defintions
        $this->templateEngine = env('TEMPLATE_ENGINE','bootstrap');
        
        if ($this->option('foundation') !== false)
        {
            $this->templateEngine ='foundation';
        }

        if ($this->option('bootstrap') !== false)
        {
            $this->templateEngine ='bootstrap';
        }

        // Name Definition
        $name       =   strtolower($this->argument('name'));
        
        if ($name === null)
        {
            $name   =   strtolower($this->ask('Please define the name of the package'));
        }
        
        if ($this->option('table')  ==  "false")
        {
            //Create Model and Migraion
            $this->call('make:model',['name' => ucfirst($name),'-m' => true]);
        }
        else
        {
                $this->info($this->option('table'));   
                $this->call('make:model'     ,['name' => ucfirst($name)]);
                $this->call('make:migration' ,['name' => "create_".strtolower($this->option('table'))."_table",'--create' => strtolower($this->option('table'))]);
        }
        //Create a RESTfulController
        $this->call('make:controller',['name' => ucfirst($name).'Controller']);

        // write Templates
        foreach ($this->rewriteFiles as $key => $value)
        {
            $this->rewriteFile($name,$key,$value);
        }
        
        //  Add a route to the Controller into the routes.php
        if (file_exists('app/Http/routes.php'))
        {
            if (!(strpos(file_get_contents('app/Http/routes.php'),"Route::resource('/".strtolower($name)."','".ucfirst($name)."Controller')")))
            {
                if ($handle=fopen('app/Http/routes.php','a+'))
                {
                    if (fwrite($handle,"\n"."Route::resource('/".strtolower($name)."','".ucfirst($name)."Controller');"))
                    {
                        if (fclose($handle))
                        {
                            $this->info("Added path to routes.php");
                        }
                    }
                }
            }
        }
    }

    protected function rewriteFile($name,$file,$template,$type = 'w')
    {
        if (file_exists(str_replace("::engine", $this->templateEngine, $template)))
        {    
            $file = str_replace('::name',strtolower($name),$file);
            $file = str_replace('::Name',ucfirst($name),$file);

            $newContent = file_get_contents(str_replace("::engine", $this->templateEngine, $template));  
            $newContent = str_replace('::table',strtolower( ($this->option('table') !=="false") ? ($this->option('table')) : ('::names')),$newContent);
            $newContent = str_replace('::private',strtolower( ($this->option('private') !==false) ? ('true') : ('false')),$newContent);
            $newContent = str_replace('::master',strtolower( ($this->option('master') !=="false") ? ($this->option('table')) : ('::name')),$newContent);
            $newContent = str_replace('::name',strtolower($name),$newContent);
            $newContent = str_replace('::Name',ucfirst($name),$newContent);

            if (strpos($file,'sources/views/'))
            {
                if (!(file_exists("resources/views/$name")))
                {
                    mkdir("resources/views/$name");
                }
            }
                
            if ((!(file_exists("resources/views/$name")))||($this->option('override') !== false))
            {    
                if ($handle = fopen($file,$type))
                {
                    if (fwrite($handle,$newContent))
                    {
                        if (fclose($handle))
                        {
                            $this->info("Added Content to ".$file);
                        }
                    }
                }
            }
        }            
    }

}
