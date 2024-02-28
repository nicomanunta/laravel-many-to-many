<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Type;




class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects =  Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();

        return view('admin.projects.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $form_data= $request->all();
        
        $project = new Project();

        if ($request->hasFile('cover_immagine')) {
            
            $path = Storage::disk('public')->put('projects_image', $form_data['cover_immagine']);
            $form_data['cover_immagine'] = $path;
        }

        $slug =Str::slug($form_data['nome_progetto'], '-');
        $form_data['slug'] = $slug;
        $project->fill($form_data);

        $project->save();
        
        return redirect()->route('admin.projects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
         return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $types = Type::all();
        return view('admin.projects.edit', compact('project', 'types'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $form_data= $request->all();
       

        if ($request->hasFile('cover_immagine')) {
            
            if ($project->cover_immagine != null) {
                
                Storage::disk('public')->delete($project->cover_immagine);

            }
            $path = Storage::disk('public')->put('projects_image', $form_data['cover_immagine']);
            $form_data['cover_immagine'] = $path;
        }

        // if ($request->hasFile('cover_immagine')) {
            
        //     $path = Storage::disk('public')->put('projects_image', $form_data['cover_immagine']);
        //     $form_data['cover_immagine'] = $path;
        // }
        

        $slug =Str::slug($form_data['nome_progetto'], '-');
        $form_data['slug'] = $slug;

        $project->update($form_data);
        
        return redirect()->route('admin.projects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        if ($project->cover_immagine != null) {
            Storage::disk('public')->delete($project->cover_immagine);
        }
        
        $project->delete();
        return redirect()->route('admin.projects.index');
    }
}
