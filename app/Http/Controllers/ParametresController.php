<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ParametresController extends Controller
{
    public function edit()
    {
        $agency = app('current_agency');

        return view('parametres.edit', compact('agency'));
    }

    public function update(Request $request)
    {
        $agency = app('current_agency');

        $data = $request->validate([
            'name'               => 'required|string|max:150',
            'email'              => 'nullable|email|max:150',
            'telephone'          => 'nullable|string|max:30',
            'adresse'            => 'nullable|string|max:255',
            'ville'              => 'nullable|string|max:100',
            'pays'               => 'nullable|string|size:2',
            'locale_defaut'      => 'nullable|in:fr,en,pt-PT',
            'taux_commission'    => 'nullable|numeric|min:0|max:100',
            'couleur_principale' => 'nullable|string|max:7',
            'logo'               => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($agency->logo) {
                Storage::disk('public')->delete($agency->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $agency->update($data);

        return redirect()->route('parametres.edit')
            ->with('success', 'Paramètres mis à jour.');
    }
}
