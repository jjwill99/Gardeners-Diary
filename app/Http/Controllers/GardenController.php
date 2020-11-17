<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Garden;

class GardenController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userId = \Auth::user()->id;
        $gardensQuery = Garden::orderBy('id')->get();
        return view('gardens.index', array('userId'=>$userId, 'gardens'=>$gardensQuery));
    }

    public function show($id){
        $garden = Garden::find($id);
        return view('gardens.show', array('garden'=> $garden));
    }

    public function create(){
        return view('gardens.create');
    }

    public function store(Request $request){
        //form validation
        $garden = $this->validate(request(), [
            'name' => 'required',
            'width' => 'required',
            'length' => 'required',
            'picture' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:500',
        ]);

        //Create a Garden object and set its values from the input
        $garden = new Garden;
        $garden->user_id = \Auth::user()->id;
        $garden->name = $request->input('name');
        $garden->width = $request->input('width');
        $garden->length = $request->input('length');

        //Handles the uploading of the picture
        if ($request->hasFile('picture')){
            //Gets the filename with the extension
                $fileNameWithExt = $request->file('picture')->getClientOriginalName();
            //just gets the filename
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //Just gets the extension
                $extension = $request->file('picture')->getClientOriginalExtension();
            //Gets the filename to store
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //Uploads the picture
                $path = $request->file('picture')->storeAs('public/images', $fileNameToStore);
                $garden->picture = $fileNameToStore;
        }

        //Save the Garden object
        $garden->save();
        
        //Generate a redirect HTTP response with a success message
        return back()->with('success', 'Garden has been added');
    }

}
