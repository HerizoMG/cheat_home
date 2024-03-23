<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Materiel;
use App\Models\Type;
use Illuminate\Http\Request;

class MaterielController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materiels = Materiel::all();
        return response()->json($materiels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'price' => 'required|string',
            'libelle' => 'required|string',
        ]);

        // Créer d'abord le type avec le libellé fourni
        $type = Type::firstOrCreate(['libelle' => $validatedData['libelle']]);

        // Créer le matériel en utilisant le type_id du type créé
        $materiel = Materiel::create([
            'price' => $validatedData['price'],
            'type_id' => $type->id,
        ]);

        // Retourner une réponse appropriée, par exemple les données du matériel créé
        return response()->json($materiel);
    }

    /**
     * Display the specified resource.
     */
    public function show(Materiel $materiel)
    {
        $materiel = Materiel::find($materiel->id);
        return response()->json($materiel);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materiel $materiel)
    {
        $validatedData = $request->validate([
            'price' => 'required|string',
            'libelle' => 'required|string',
        ]);

        // Mettre à jour le type avec le libellé fourni
        $type = Type::firstOrCreate(['libelle' => $validatedData['libelle']]);

        // Mettre à jour le matériel en utilisant le type_id du type créé
        $materiel->update([
            'price' => $validatedData['price'],
            'type_id' => $type->id,
        ]);

        // Retourner une réponse appropriée, par exemple les données du matériel mis à jour
        return response()->json($materiel);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materiel $materiel)
    {
        $materiel->delete();
        return response()->json(['message' => 'Materiel deleted']);
    }
}
