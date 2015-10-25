<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use Config;
class MVC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:mvc {name} {--foundation} {--bootstrap} {--reset} {--table=false} {--private} {--master=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a Model, a view, a migration and a controller and adds it to the routes.';
    
    /**
     * The name of the created model.
     *
     * @var string
     */
    protected $name;

    /**
     * The database table used for model and migration.
     *
     * @var string
     */
    protected $table;

    /**
     * The master.blade file which the views extend.
     *
     * @var string
     */
    protected $master;
    
    /**
     * Overwrite-Mode, defines if the templates are being rewritten
     *
     * @var string
     */
    protected $reset;
    
    /**
     * The original Template Locations
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
        $this->checkFiles();
        $this->setVariables();
        
        //create migration // FILTER

        if ($this->migration    || ($this->reset == true))
        {        
            $this->call('make:migration' ,['name' => "create_".$this->table."_table",'--create' => $this->table]);
        }


        // write Templates
        foreach ($this->rewriteFiles as $key => $value)
        {   
            $this->rewriteFile($this->name,$key,$value);
        }
        
        //  Add a route to the Controller into the routes.php
        if (File::exists('app/Http/routes.php'))
        {
            if (!(strpos(File::get('app/Http/routes.php'),"Route::resource('/".strtolower($this->name)."','".ucfirst($this->name)."Controller')")))
            {
                if (File::append('app/Http/routes.php',"\n"."Route::resource('/".strtolower($this->name)."','".ucfirst($this->name)."Controller');"))
                {
                    $this->info("Added path to routes.php");
                }        
            }
        }
    }

    protected function rewriteFile($name,$file,$template,$type = 'w')
    {    
        if (File::exists($template))
        {    
            $newContent = File::get(str_replace("::engine", $this->templateEngine, $template));  
            $newContent = str_replace('::table',    $this->table,               $newContent);
            $newContent = str_replace('::private',  $this->private,             $newContent);
            $newContent = str_replace('::master',   $this->master,              $newContent);
            $newContent = str_replace('::name',     strtolower($this->name),    $newContent);
            $newContent = str_replace('::Name',     ucfirst($this->name),      $newContent);
            
            if (strpos($file,'sources/views/'))
            {
                if (!(File::exists("resources/views/".$this->name)))
                {
                    mkdir("resources/views/".$this->name);
                }
            }
            
            if (File::put($file, $newContent))
                $this->info("Added Content to ".$file);
            
        }            
    }

    protected function setVariables(){
       
        // Template Engine Defintions
        $this->templateEngine = Config::get('mvc.templateEngine');
        
        if ($this->option('foundation') !== false)
        {
            $this->templateEngine ='foundation';
        }

        if ($this->option('bootstrap') !== false)
        {
            $this->templateEngine ='bootstrap';
        }

        
        // Name Definition
        $name           =   $this->argument('name');

        if ($name === null)
        {
            $name   =   $this->ask('Please define the name of the package');
        }

        $this->name     =   strtolower($name);


        // Table Definition
        
        $table  =   $this->option('table');

        if ( ($table  ==  "false") || ($table   ==  false) )
        {
            $table  =   $this->name."s";
        }

        $this->table     =   strtolower($table);


        // Master.blade Definition

        $master  =   $this->option('master');

        if ( ($master  ==  "false") || ($master   ==  false) )
        {
            $master  =   Config::get('mvc.templateMaster');;
        }

        $this->master     =   $master;

        
        // Reset Definition, defines if the templates are being rewritten

        $reset           =   $this->option('reset');

        if ($reset === true)
        {
            $this->reset    ="Yes to all";
        }
        
        $rewriteFiles       =   $this->rewriteFiles;
        $this->rewriteFiles =   [];

        foreach ($rewriteFiles as $key => $value)
        {
            
            if ($this->reset !== 'No to all')
            {
                $index=str_replace('::Name',ucfirst($this->name),str_replace('::name',$this->name,$key));
                
                if ( (File::exists($index)) && ($this->reset !== 'Yes to all') )
                {
                    $this->error ("File ".$index." already exists!");
                    $this->reset= $this->choice("Overwrite ?",['No','Yes','No to all','Yes to all'],0);
                    if (( $this->reset  !==  "No")  &&  ( $this->reset  !==  "No to all"))
                    {
                        $this->rewriteFiles[$index] = str_replace('::engine',$this->templateEngine,$value);
                    }
                }
                else
                {
                    $this->rewriteFiles[$index] = str_replace('::engine',$this->templateEngine,$value);   
                }
            }
        }
        
        // Private Definition, can be accessed by owner only

        $private           =   $this->option('private');

        if ($private !== true)
        {
            $private    = "null";
        }

        $this->private      =   $private;

        // Migration,test if if exists
        $this->migration    =  true;

        $migrations         =   File::files('database/migrations');        

        foreach ($migrations as $key => $value) 
        {
            if (strpos($value,'create_'.$this->table."_table"))
            {
                $this->migration =  false;
                break;
            }
        }
    }

    protected function checkFiles(){
                     
        //   Config
        if (!(File::exists(config_path().'/mvc.php')))
        {
            if (File::copy(__DIR__.'/config/mvc.php',config_path().'/mvc.php'))
            {
                $this->info('MVC config created.');
                /*
                $array  =   ['name'=>$this->argument('name')];
            
                foreach ($this->option() as $key => $value) 
                {
                    $array["--".$key] = $value;
                }
                \Artisan::call('make:mvc',$array);
                */
            }
        }
    }
}
