<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Home;
use App\Models\HouseMateriel;
use App\Models\Materiel;
use App\Models\OfferMateriel;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = User::find($user->id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'lastname' => 'required|string|max:191',
            'firstname' => 'required|string|max:191',
            'phone' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:6',
            'address' => 'required|string|max:191',
            'avatar' => 'required|file|max:2048',
            'roles' =>'required|json',
        ]);

        $user->update($validatedData);
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }

    public function postPartner(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'price' => 'required|string',
        ]);

        $offre = new OfferMateriel();
        $offre->description = $validatedData['description'];
        $offre->price = $validatedData['price'];
        $offre->user_id = $user->id;

        $offre->save();

        return response()->json(['message' => 'Offre enregistrée avec succès'], 200);
    }

    public function createHome(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'address' => 'required|string',
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

        $user_id = auth()->user()->id;
        // Créer la maison en utilisant l'adresse, user_id et materiel_id
        $home = Home::create([
            'address' => $validatedData['address'],
            'user_id' => $user_id,
            'materiel_id' => $materiel->id,
        ]);

        // Créer la relation entre la maison et le matériel
        $houseMateriel = HouseMateriel::create([
            'home_id' => $home->id,
            'materiel_id' => $materiel->id,
        ]);

        return response()->json($home);
    }

    public function getMaterielByUser()
    {
        $home  = Home::with('houseMateriel.materiel.type')->where('user_id', auth()->user()->id)->get();

        return response()->json($home);
    }
}
