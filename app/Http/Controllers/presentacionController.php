<?php

namespace App\Http\Controllers;

use App\Models\Caracteristica;
use App\Http\Requests\StoreCategoriaRequest;
use App\Http\Requests\UpdatePresentacionRequest;
use App\Models\Presentacione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class presentacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $presentaciones = Presentacione::with('caracteristicas')->latest()->get();
        return view('presentacion.index', ['presentaciones' => $presentaciones]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('presentacion.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoriaRequest $request)
    {
        try 
        {
            DB::beginTransaction();
            $caracteristica = Caracteristica::create($request->validated());
            $caracteristica->presentacione()->create([
                'caracteristica_id' => $caracteristica->id
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return redirect()->route('presentaciones.index')->with('success', 'Presentacion creada correctamente');
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
    public function edit(Presentacione $presentacione)
    {
        return view('presentacion.edit', compact('presentacione'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePresentacionRequest $request, Presentacione $presentacione)
    {
        Caracteristica::where('id', $presentacione->caracteristicas->id)
        ->update($request->validated());
        return redirect()->route('presentaciones.index')->with('success', 'Presentacion actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $presentacion = Presentacione::find($id);
        $message = '';

        if ($presentacion->caracteristicas->estado == 1){
            Caracteristica::where('id', $presentacion->caracteristicas->id)
            ->update(['estado' => 0]);
            $message = 'Presentacion eliminada correctamente';
        } else {
            Caracteristica::where('id', $presentacion->caracteristicas->id)
            ->update(['estado' => 1]);
            $message = 'Presentacion activada correctamente';
        }
        return redirect()->route('presentaciones.index')->with('success', $message);
    } 
}
