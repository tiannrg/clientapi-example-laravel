<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class TypeActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(Session::get('token'))->get($url . '/type_activity');
        if ($response->successful()) {
            $types = $response->json();
            return view('type_activity.index', compact('types'));
        }
        else {
            abort($response->status());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('type_activity.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(Session::get('token'))->post($url . '/type_activity',[
            'description' => $request->description
        ]);
        if ($response->successful()) {
            session()->flash('message', 'Registro creado exitosamente');
            return redirect()->route('type_activity.index');
        }
        elseif ($response->status()== Response::HTTP_BAD_REQUEST) {
            $errors = $response->json()['errors'];
            return redirect()-> route('type_activity.create')->withErrors($errors);
        }
        else {
            abort($response->status());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(Session::get('token'))->get($url . '/type_activity/'. $id,);
        if ($response->successful()) 
        {
            $type = $response->json();
            return view ('type_activity.edit',compact('type'));
        }
         elseif ($response->status()== Response::HTTP_BAD_REQUEST) {
            $errors = $response->json()['errors'];
            return redirect()-> route('type_activity.index')->withErrors($errors);
        }
        else {
            abort($response->status());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(Session::get('token'))->put($url . '/type_activity/' . $id,[
            'id' => $request->id,
            'description' => $request->description
        ]);

        if ($response->successful()) {
            session()->flash('message', 'Registro actuliazado exitosamente');
            return redirect()->route('type_activity.index');
        }

        elseif ($response->status()== Response::HTTP_BAD_REQUEST) {
            $errors = $response->json()['errors'];
            return redirect()-> route('type_activity.edit')->withErrors($errors);
        }

        else 
        {
            abort($response->status());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(Session::get('token'))->delete($url . '/type_activity/' . $id,);
        if ($response->successful()) {
            session()->flash('message', 'Registro eliminado exitosamente');
            return redirect()->route('type_activity.index');
        }

        elseif ($response->status()== Response::HTTP_BAD_REQUEST) {
            $errors = $response->json()['errors'];
            return redirect()-> route('type_activity.index')->withErrors($errors);
        }

        else 
        {
            abort($response->status());
        }
    }
}
