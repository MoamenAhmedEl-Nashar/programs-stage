<?php

namespace App\Http\Controllers;

use App\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    // home page (return all programs)
    public function index()
    {
        return Program::with('links')->get();

        // for lazy loading
        //return Program::paginate(2);
    }

    public function store(Request $request)
    {
        $validated_request = $request->validate([
            'name' => 'required',
            'web_or_desktop' => 'required',
            'icon' => 'nullable',
            'ip' => 'nullable',
            'description' => 'nullable',
            'permission' => 'nullable',
            'version' => 'nullable',
            'database' => 'nullable'
        ]);

        $user_id = Auth::user()->id;
        $validated_request['user_id'] = $user_id;
        $program = Program::create($validated_request);

        return $program;
    }

    public function update(Request $request, $id)
    {

        $validated_request = $request->validate([
            'name' => 'nullable',
            'web_or_desktop' => 'nullable',
            'icon' => 'nullable',
            'ip' => 'nullable',
            'description' => 'nullable',
            'permission' => 'nullable',
            'version' => 'nullable',
            'database' => 'nullable'
        ]);

        $program = Program::findOrFail($id)->update($validated_request);

        return $program;
    }

    public function destroy(Request $request, $id)
    {
        $program = Program::findOrFail($id);
        $programName = $program->name;

        // delete program directory first
        $programName = str_replace(array('.','/'), "", $programName);
        $path = $programName;

        Storage::deleteDirectory($path);

        // delete the program from the database
        $program = $program->delete();

        return $path;
    }
}
