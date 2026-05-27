<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function currentAgency()
    {
        return app('current_agency');
    }

    public function index()
    {
        $agency = $this->currentAgency();

        $users = User::where('agency_id', $agency->id)
            ->with('zone')
            ->orderBy('nom')
            ->get();

        return view('users.index', compact('users', 'agency'));
    }

    public function create()
    {
        $agency = $this->currentAgency();
        $zones  = Zone::where('agency_id', $agency->id)
            ->where('is_active', true)
            ->orderBy('nom')
            ->get();

        return view('users.create', compact('agency', 'zones'));
    }

    public function store(Request $request)
    {
        $agency = $this->currentAgency();

        $data = $request->validate([
            'prenom'    => 'required|string|max:100',
            'nom'       => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:30',
            'role'      => 'required|in:agency_admin,gestionnaire,readonly',
            'zone_id'   => 'nullable|exists:zones,id',
        ]);

        // Vérifier que la zone appartient bien à cette agence
        if (!empty($data['zone_id'])) {
            Zone::where('id', $data['zone_id'])
                ->where('agency_id', $agency->id)
                ->firstOrFail();
        }

        $data['agency_id'] = $agency->id;
        $data['password']  = Hash::make($data['password']);

        $user = User::create($data);
        $user->assignRole($data['role']);

        return redirect()->route('users.index')
            ->with('success', $user->prenom . ' ' . $user->nom . ' a été créé.');
    }

    public function edit(User $user)
    {
        $agency = $this->currentAgency();

        // Sécurité : ne peut modifier que les utilisateurs de son agence
        abort_if($user->agency_id !== $agency->id, 403);

        $zones = Zone::where('agency_id', $agency->id)
            ->where('is_active', true)
            ->orderBy('nom')
            ->get();

        return view('users.edit', compact('user', 'agency', 'zones'));
    }

    public function update(Request $request, User $user)
    {
        $agency = $this->currentAgency();

        abort_if($user->agency_id !== $agency->id, 403);

        $data = $request->validate([
            'prenom'    => 'required|string|max:100',
            'nom'       => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:30',
            'role'      => 'required|in:agency_admin,gestionnaire,readonly',
            'zone_id'   => 'nullable|exists:zones,id',
            'password'  => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($data['zone_id'])) {
            Zone::where('id', $data['zone_id'])
                ->where('agency_id', $agency->id)
                ->firstOrFail();
        }

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        $user->syncRoles([$data['role']]);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user)
    {
        $agency = $this->currentAgency();

        abort_if($user->agency_id !== $agency->id, 403);

        // Impossible de se supprimer soi-même
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $nom = $user->prenom . ' ' . $user->nom;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', $nom . ' a été supprimé.');
    }
}
