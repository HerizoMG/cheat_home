<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Home;
use App\Models\HouseMateriel;
use App\Models\Interested;
use App\Models\Materiel;
use App\Models\OfferHome;
use App\Models\OfferMateriel;
use App\Models\Order;
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

    public function postMateriel(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'libelle' =>'required|string',
            'price' => 'required|string',
        ]);

        $type = Type::create(['libelle' => $validatedData['libelle']]);

        $materiel = Materiel::create([
            'type_id' => $type->id,
            'price' => $validatedData['price'],
        ]);

        $offre = OfferMateriel::create([
            'description' => $validatedData['description'],
            'user_id' => auth()->user()->id,
            'materiel_id' => $materiel->id,
        ]);

        return response()->json($offre);
    }

    public function createHome(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'address' => 'required|string',
            'price' => 'required|string',
            'libelle' => 'required|string',
            'isActivated' => 'required|boolean'
        ]);

        $type = Type::firstOrCreate(['libelle' => $validatedData['libelle']]);

        $materiel = Materiel::create([
            'price' => $validatedData['price'],
            'type_id' => $type->id,
        ]);

        $user_id = auth()->user()->id;

        $home = Home::create([
            'address' => $validatedData['address'],
            'user_id' => $user_id,
            'materiel_id' => $materiel->id,
        ]);

        $houseMateriel = HouseMateriel::create([
            'home_id' => $home->id,
            'materiel_id' => $materiel->id,
            'isActivated' => $validatedData['isActivated'],
        ]);

        return response()->json($home);
    }

    public  function getAllHome()
    {
        $allHome = Home::all();

        return response()->json($allHome);
    }

    // OFFRE DE MAISON
    public function offreHome(Request $request)
    {
        $validatedData = request()->validate([
            'description' => 'required|string',
            'price' => 'required|string',
            'image' => 'required|image',
            'home_id' => 'required',
            'user_id' => 'required',
        ]);

        $filename = '';
        if ($request->hasFile('image')){
            $uploadedFile = $request->file('image');
            $filename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
            $uploadedFile->move(public_path('imageHome'), $filename);
        }

        $home = Home::find($validatedData['home_id']);
        if ($home){
            $offre = OfferHome::create([
                'description' => $validatedData['description'],
                'price' => $validatedData['price'],
                'image' => $filename,
                'user_id' => $validatedData['user_id'],
                'home_id' => $home->id,
            ]);
            return response()->json($offre);
        }
        return response()->json(['message' => 'Home not found'], 404);
    }

    // OBTENIR ALL OFFRE DE MAISON
    public function getAllOfferHome()
    {
        $offerHome = OfferHome::with('home', 'user')->get();

        return response()->json($offerHome);
    }

    public function getMaterielByUser()
    {
        $home  = Home::with('houseMateriel.materiel.type')->where('user_id',auth()->user()->id)->get();

        return response()->json($home);
    }

    public function getAllOfferMateriel()
    {
        $offerMateriel = OfferMateriel::with('materiel.type')->get();

        return response()->json($offerMateriel);
    }

    public function getHomeByUserId($userId)
    {
        $home = Home::where('user_id',$userId)->get();
        if ($home){
            return response()->json($home);
        }
        return response()->json(['message' => 'Home not found']);
    }

    public function createInterested()
    {
        $int = Interested::where('user_id', request('user_id'))
            ->where('offer_home_id', request('offer_home_id'))
            ->first();
        if ($int) {
            $int->delete();
            return response()->json(['message' => 'Interested deleted']);
        } else {
            $interested = Interested::create([
                'user_id' => request('user_id'),
                'offer_home_id' => request('offer_home_id'),
            ]);
            return response()->json($interested);
        }
    }

    public function isInterested($offer_home_id,$user_id)
    {
        $interested = Interested::where('user_id', $user_id)
            ->where('offer_home_id', $offer_home_id)
            ->exists();

        if ($interested) {
            return response()->json([], 204);
        } else {
            return response()->json([], 404);
        }
    }

    public function isPublished()
    {
        $materiel = Materiel::with('type','offerMateriel','offerMateriel.user')
            ->where('isPublished', true)
            ->get();
        return response()->json($materiel);
    }
    public  function createdMateriel(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'price' => 'required|string',
            'libelle' => 'required|string',
            'image' => 'required|image',
            'isPublished' => 'required|boolean',
            'description' => 'required|string',
        ]);

        $filename = '';
        if ($request->hasFile('image')){
            $uploadedFile = $request->file('image');
            $filename = uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
            $uploadedFile->move(public_path('imageHome'), $filename);
        }

        $type = Type::firstOrCreate(['libelle' => $validatedData['libelle']]);

        $materiel = Materiel::create([
            'price' => $validatedData['price'],
            'type_id' => $type->id,
            'isPublished' => $validatedData['isPublished'],
            'image' => $filename,
        ]);

        $order = OfferMateriel::create([
            'description' => $validatedData['description'],
            'user_id' => 1,
            'materiel_id' => $materiel->id,
        ]);

        return response()->json($materiel);
    }


}
