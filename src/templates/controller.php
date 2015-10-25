<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\::Name;
use App\Input;
class ::NameController extends Controller
{

    /**
     *  Allows this Controller be be viewed by everybody,
     *  wheter they are logged in or not
     */
    protected $publicToAll              =   true;
    /**
     *  Allows this items fo,
     *  wheter they are logged in or not
     */
    protected $publicAddEditAndDelete   =   true;

    /**
     *  Defines ownership column, 
     *  must mmatch the user_id.
     */
    protected $user_id  =   null;
    
    /**
     *  Defines ownership is required for , 
     *  show method. Will produce different index in the future
     */
    protected $isPrivate  =   ::private;
    
    
    /**
     *  Model atttributes that will be ignored for, 
     *  the create, edit and update form.
     */
    protected $FormIgnore   =   [
                                ];

    protected $FormTypes   =   [
                                ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models   =   ::Name::all();

        if (!(is_array($allowed=$this->allowCurrrentAction(__FUNCTION__,null)))) 
        {
            return $allowed;
        }

        if (! $allowed['view'])
        {
            return response('Unauthorized.', 401);
        }
                            
        return view('::name.index',compact('models','allowed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model= new ::Name();
        
        if (!(is_array($allowed=$this->allowCurrrentAction(__FUNCTION__,null)))) 
        {
            return $allowed;
        }

        if ((!$allowed['view']) || (!$allowed['action']))
        {
            return response('Unauthorized.', 401);
        }

        $columns= \Schema::getColumnListing($model->table);
        
        foreach ($columns as $key=>$column) {
            if (!(in_array($column,$this->FormIgnore)))
                if (($column !=='id')&&($column !=='created_at')&&($column !=='updated_at'))
                $model->$column='';
        }
        
        return view('::name.create',compact('model','allowed'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
        //    'field' => 'rules|required|min:1',
        ]);

        $input = \Input::all();
        $model= new ::Name();
                
        if (!(is_array($allowed=$this->allowCurrrentAction(__FUNCTION__,null,$model)))) 
        {
            return $allowed;
        }

        if ((!$allowed['view']) || (!$allowed['action']))
        {
            return response('Unauthorized.', 401);
        }

        foreach ($input as $key => $value) {
            if  (($key !=='_token')&&($key !=='_method'))
                $model->$key    =   $value;
        }     
        
        if ($model->save())
            \Session::flash('flash_message',"Item successfully created.");
        
        return redirect('::name');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $model=::Name::find($id);
                        
        if (!(is_array($allowed=$this->allowCurrrentAction(__FUNCTION__,$id,$model)))) 
        {
            return $allowed;
        }

        if ((!$allowed['view']) AND (!$allowed['action']))
        {
            return response('Unauthorized.', 401);
        }

        return view('::name.show',compact('model','allowed'));   

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model=::Name::find($id);
                
        if (!(is_array($allowed=$this->allowCurrrentAction(__FUNCTION__,$id,$model)))) 
        {
            return $allowed;
        }

        if ((!$allowed['view']) || (!$allowed['action']))
        {
            return response('Unauthorized.', 401);
        }

        $columns= \Schema::getColumnListing($model->table);
        
        foreach ($columns as $key=>$column) {
            if (!(in_array($column,$this->FormIgnore)))
                if (old($column))
                    $model->$column=old($column);
                else
                    $model->$column=$model->$column;
        }
        
        return view('::name.edit',compact('model','allowed'));   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
        //    'field' => 'rules|required|min:1',
        ]);

        $input  =   \Input::all();
        $model=::Name::find($id);
                
        if (!(is_array($allowed=$this->allowCurrrentAction(__FUNCTION__,$id,$model)))) 
        {
            return $allowed;
        }

        if ((!$allowed['view']) || (!$allowed['action']))
        {
            return response('Unauthorized.', 401);
        }

        foreach ($input as $key => $value) {
            if  (($key !=='_token')&&($key !=='_method'))
                $model->$key    =   $value;
        }     
        
        if ($model->save())
            \Session::flash('flash_message',"Item successfully updated.");
        
        return redirect('::name/'.$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model=::Name::find($id);
        
        if (!(is_array($allowed=$this->allowCurrrentAction(__FUNCTION__,$id,$model)))) 
        {
            return $allowed;
        }

        if ((!$allowed['view']) || (!$allowed['action']))
        {
            return response('Unauthorized.', 401);
        }

        if ($model->delete())
            \Session::flash('flash_message',"Item successfully deleted.");
        
        return redirect('::name');
        
    }

    /**
     * Requests if user is logged in
     *
     * @param  
     * @return Boolean
     */
    public function requiresLoggedIn()
    {
        if (Auth::guest())
            return (!$this->publicToAll);
        
        return false;
    }

    /**
     * Requests if user is logged in
     *
     * @param  
     * @return Boolean
     */
    public function requiresOwnership($ownership_id=null)
    {
        if ($ownership_id !==null)
            if (!(Auth::guest()))
            {
                if (Auth::user()->id !== $ownership_id)
                    return true;
            }
            else
            {
                return true;
            }
        return false;
    }

    public function requiresAuthorizedActions()
    {
        if (Auth::guest())
            return (!$this->publicAddEditAndDelete);
        
        return false;        
    }

    protected function allowCurrrentAction($functionName,$id=null,$model=null)
    {
        $check=null;
        if (($functionName !== 'index')&&($functionName !== 'create')&&($functionName !== 'store'))
        {
            if (!$model)
            {
                return response('ID:'.$id.' not found', 404);
            }

            $check  =   (null != $this->user_id)                        ?   ($this->user_id)  :   (null);
            $check  =  ((null != $check) && (null != $model->$check))   ?   ($model->$check)  :   (null);
        }

        $allowed = 
        [
            "view"  => (!($this->requiresLoggedIn())),
            "action"  => true,
            "owner"  => false,
        ];

        switch ($functionName)
        {
            case 'index' :
                $allowed['action']  =   (! $this->requiresAuthorizedActions());
            break;
       
            case 'show'  :    
                
                if ($this->isPrivate)
                {
                    $allowed['view']    =   !$this->requiresOwnership($check);
                }

                $allowed['action']  =   ( (!($this->requiresLoggedIn())) AND (!($this->requiresAuthorizedActions())) AND (!$this->requiresOwnership($check)));
                $allowed['owner']   =   !$this->requiresOwnership($check);
            break;

            default :
                $allowed['action']  =   ( ( (!$this->requiresLoggedIn()) AND (!$this->requiresAuthorizedActions()) ) AND (!($this->requiresOwnership($check))));
                $allowed['owner']   =   !$this->requiresOwnership($check);
            break;
            
        }
        if ($allowed['view'] == false)
        {
            $allowed['action'] =  false;
        }
        return $allowed;
   }
}