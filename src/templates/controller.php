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
    protected $publicAddEditAndDelete   =   false;

    /**
     *  Defines ownership column, 
     *  must mmatch the user_id.
     */
    protected $user_id  =   null;
    
    
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
    
        //dd($models);
        return view('::name.index',compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model= new ::Name();

        //prepare model for form
        if ($this->requiresLoggedIn() or $this->requiresOwnership() or ($this->requiresAuthorizedActions()))
            return response('Unauthorized.', 401);
        $columns= \Schema::getColumnListing($model->table);
        //dd($columns);        
        foreach ($columns as $key=>$column) {
            if (!(in_array($column,$this->FormIgnore)))
                if (($column !=='id')&&($column !=='created_at')&&($column !=='updated_at'))
                $model->$column='';
        }
        
        return view('::name.create',compact('model'));
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
        if ($this->requiresLoggedIn() or $this->requiresOwnership() or ($this->requiresAuthorizedActions()))
            return response('Unauthorized.', 401);
        foreach ($input as $key => $value) {
            if  (($key !=='_token')&&($key !=='_method'))
                $model->$key    =   $value;
        }     
        //dd($model);
        //dd($input);
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
        if (!$model)
            return response('ID:'.$id.' not found', 404);
        if ($this->requiresLoggedIn() AND $this->requiresOwnership($id))
            return response('Unauthorized.', 401);
        return view('::name.show',compact('model'));   

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
        if (!$model)
            return response('ID:'.$id.' not found', 404);
        if ($this->requiresLoggedIn() or $this->requiresOwnership($id) or ($this->requiresAuthorizedActions()))
            return response('Unauthorized.', 401);

        $columns= \Schema::getColumnListing($model->table);
        
        foreach ($columns as $key=>$column) {
            if (!(in_array($column,$this->FormIgnore)))
                if (old($column))
                    $model->$column=old($column);
                else
                    $model->$column=$model->$column;
        }
        
        return view('::name.edit',compact('model'));   
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
        if (!$model)
            return response('ID:'.$id.' not found', 404);
        if ($this->requiresLoggedIn() or $this->requiresOwnership($id) or ($this->requiresAuthorizedActions()))
            return response('Unauthorized.', 401);

        foreach ($input as $key => $value) {
            if  (($key !=='_token')&&($key !=='_method'))
                $model->$key    =   $value;
        }     
        //dd($model);
        //dd($input);
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
        if (!$model)
            return response('ID:'.$id.' not found', 404);
        if ($this->requiresLoggedIn() or $this->requiresOwnership($id) or ($this->requiresAuthorizedActions()))
            return response('Unauthorized.', 401);
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
            if (Auth::user()->id !== $ownership_id)
                return true;
        return false;
    }

    public function requiresAuthorizedActions()
    {
     if (Auth::guest())
            return (!$this->publicAddEditAndDelete);
        return false;        
    }

}