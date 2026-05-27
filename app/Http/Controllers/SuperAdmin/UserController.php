<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['agency', 'zone'])
            ->orderBy('nom')
            ->paginate(25);

        return view('superadmin.users.index', compact('users'));
    }

    public function create()
    {
        $agencies = Agency::where('is_active', true)->orderBy('name')->get();
        $zones    = Zone::with('agency')->orderBy('nom')->get();

        return view('superadmin.users.create', compact('agencies', 'zones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'prenom'     => 'required|string|max:100',
            'nom'        => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'telephone'  => 'nullable|string|max:30',
            'role'       => 'required|in:agency_admin,gestionnaire,readonly',
            'agency_id'  => 'required|exists:agencies,id',
            'zone_id'    => 'nullable|exists:zones,id',
            'pays'       => 'nullable|string|size:2',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['locale']   = config('locales.pays_locale')[$data['pays'] ?? ''] ?? 'fr';

        $user = User::create($data);
        $user->assignRole($data['role']);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Utilisateur créé : ' . $user->prenom . ' ' . $user->nom . '.');
    }

    public function show(User $user)
    {
        $user->load(['agency', 'zone']);
        return view('superadmin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $agencies = Agency::where('is_active', true)->orderBy('name')->get();
        $zones    = Zone::with('agency')->orderBy('nom')->get();

        return view('superadmin.users.edit', compact('user', 'agencies', 'zones'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'prenom'    => 'required|string|max:100',
            'nom'       => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'nullable|string|max:30',
            'role'      => 'required|in:agency_admin,gestionnaire,readonly',
            'agency_id' => 'required|exists:agencies,id',
            'zone_id'   => 'nullable|exists:zones,id',
            'pays'      => 'nullable|string|size:2',
            'password'  => 'nullable|string|min:8|confirmed',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        // Sync rôle Spatie
        $user->syncRoles([$data['role']]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Utilisateur mis à jour.');
    }

    public function destroy(User $user)
    {
        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Impossible de supprimer un super admin.');
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Utilisateur supprimé.');
    }
}
