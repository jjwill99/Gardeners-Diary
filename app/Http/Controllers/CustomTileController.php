<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomTile;

class CustomTileController extends Controller
{
    public function create(){
        return view('customTiles.create');
    }

    public function store(Request $request){
        //form validation
        $customTile = $this->validate(request(), [
            'name' => 'required',
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:500',
        ]);

        //Create a Garden object and set its values from the input
        $customTile = new CustomTile;
        $customTile->user_id = \Auth::user()->id;
        $customTile->tile_name = $request->input('name');

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
                $customTile->icon = 'custom' . $fileNameToStore;
        }

        //Save the Garden object
        $customTile->save();
        
        //Generate a redirect HTTP response with a success message
        return redirect('garden')->with('success', 'Plant tile has been added');
    }

    public function destroy($id)
    {
    	$customTile = CustomTile::find($id);
        $customTile->delete();
                
        return back()->with('success', 'Plant tile has been deleted');
    }
}
