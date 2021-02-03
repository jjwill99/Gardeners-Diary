<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function show($id){
        $user = User::find($id);
        return view('show', array('user'=> $user));
    }

    public function list(){
        return view('list', array('users'=>Users::all()));
    }
}
