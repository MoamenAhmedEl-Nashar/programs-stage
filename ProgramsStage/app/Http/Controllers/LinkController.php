<?php

namespace App\Http\Controllers;

use App\Link;
use App\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LinkController extends Controller
{
    public function store(Request $request)
    {
        $validated_request = $request->validate([
            'name' => 'nullable',
            'path' => 'required',
            'type' => 'nullable',
            'program_id' => 'required'
        ]);

        $user_id = Auth::user()->id;
        $validated_request['user_id'] = $user_id;


        $programName = Program::findOrFail($validated_request['program_id'])->name;
        $programName = str_replace(array('.', '/'), "", $programName);
        $extension = $request->file('path')->extension();
        $fileName = $request->file('path')->getClientOriginalName();
        $validated_request['name'] = $fileName;

        $path = $request->file('path')->storeAs(
            $programName,
            $validated_request['name']
        );

        $validated_request['path'] = $path;
        $link = Link::create($validated_request);

        return $link;
    }


    public function destroy(Request $request, $id)
    {
        $link = Link::findOrFail($id);
        $path = $link->path;

        // delete the file from the disk first
        Storage::delete($path);

        // delete the link from the database
        $link = $link->delete();

        return $path;
    }

    public function downloadFile(Request $request, $id)
    {
        $link = Link::findOrFail($id);
        $path = $link->path;

        return Storage::download($path);
    }
}
