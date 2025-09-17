<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(Session::get('token'))->get($url . '/activity');
        if($response->successful())
        {
            $activities = $response->json();
            return view('activity.index', compact('activities'));
        }
        else
        {
            abort($response->status());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $responseTechnicians = Http::acceptJson()->withToken(Session::get('token'))->get($url . '/technician');
        $responseTypes = Http::acceptJson()->withToken(Session::get('token'))->get($url . '/type_activity');
        if($responseTechnicians->successful() and $responseTypes->successful())
        {
            $technicians = $responseTechnicians->json();
            $types = $responseTypes->json();
            return view('activity.create', compact('technicians', 'types'));
        }
        else
        {
            abort($responseTechnicians->status());
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(Session::get('token'))->post($url . '/activity', [
            'description' => $request->description,
            'hours' => $request->hours,
            'technician_id' => $request->technician_id,
            'type_activity_id' => $request->type_activity_id
        ]);

        if($response->successful())
        {
            session()->flash('message', 'Registro creado exitosamente');
            return redirect()->route('activity.index');
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json()['errors'];
            return redirect()->route('activity.create')->withInput()->withErrors($errors);
        }
        else
        {
            abort($response->status());
        }  
    }

   
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(Session::get('token'))->get($url . '/activity/' . $id);

        if($response->successful())
        {
            $responseTechnicians = Http::acceptJson()->withToken(Session::get('token'))->get($url . '/technician');
            $responseTypes = Http::acceptJson()->withToken(Session::get('token'))->get($url . '/type_activity');
            if($responseTechnicians->successful() and $responseTypes->successful())
            {
                $technicians = $responseTechnicians->json();
                $types = $responseTypes->json();
                $activity = $response->json();
                return view('activity.edit', compact('activity', 'technicians', 'types'));
            }
            else
            {
                abort($responseTechnicians->status());
            }
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json()['errors'];
            return redirect()->route('activity.index')->withInput()->withErrors($errors);
        }
        else
        {
            abort($response->status());
        }   
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $url = env('URL_BASE_API', "http://localhost:8000");
        $response = Http::acceptJson()->withToken(Session::get('token'))->put($url . '/activity/' . $id, [
            'id' => $request->id,
            'description' => $request->description,
            'hours' => $request->hours,
            'technician_id' => $request->technician_id,
            'type_activity_id' => $request->type_activity_id
        ]);

        if($response->successful())
        {
            session()->flash('message', 'Registro actualizado exitosamente');
            return redirect()->route('activity.index');
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json()['errors'];
            return redirect()->route('activity.edit')->withInput()->withErrors($errors);
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
        $response = Http::acceptJson()->withToken(Session::get('token'))->delete($url . '/activity/' . $id);

        if($response->successful())
        {
            session()->flash('message', 'Registro eliminado exitosamente');
            return redirect()->route('activity.index');
        }
        elseif($response->status() == Response::HTTP_BAD_REQUEST)
        {
            $errors = $response->json()['errors'];
            return redirect()->route('activity.index')->withInput()->withErrors($errors);
        }
        else
        {
            abort($response->status());
        }  
    }
}