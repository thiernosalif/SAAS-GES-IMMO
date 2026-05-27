<x-pdf-layout title="Situation propriétaire">

    {{-- En-tête --}}
    <div class="header">
        <div class="header-agency">
            @if(!empty($logoBase64))
                <img src="{{ $logoBase64 }}" alt="{{ $agency->name }}"
                     style="max-height:64px; max-width:180px; margin-bottom:8px; display:block; object-fit:contain;">
            @endif
            <div class="agency-name">{{ $agency->name }}</div>
            <div class="agency-info">
                {{ $agency->adresse }}<br>
                {{ $agency->ville }}<br>
                Tél : {{ $agency->telephone }}<br>
                Email : {{ $agency->email }}
            </div>
        </div>
        <div class="doc-title">
            <h1>SITUATION PROPRIÉTAIRE</h1>
            <div class="doc-number">Réf. SIT-{{ str_pad($situation->id, 5, '0', STR_PAD_LEFT) }}</div>
            <div class="doc-date">Émis le {{ now()->format('d/m/Y') }}</div>
        </div>
    </div>

    {{-- Informations propriétaire + période --}}
    <div class="info-block">
        <div class="info-row">
            <span class="info-label">Propriétaire :</span>
            <span class="info-value">{{ $situation->proprietaire?->full_name ?? '—' }}</span>
        </div>
        @if($situation->proprietaire?->telephone)
        <div class="info-row">
            <span class="info-label">Téléphone :</span>
            <span class="info-value">{{ $situation->proprietaire->telephone }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Période :</span>
            <span class="info-value">
                Du {{ $situation->periode_debut?->format('d/m/Y') }}
                au {{ $situation->periode_fin?->format('d/m/Y') }}
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Type :</span>
            <span class="info-value">{{ ucfirst($situation->type) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Générée par :</span>
            <span class="info-value">
                {{ $situation->generePar?->prenom }} {{ $situation->generePar?->nom }}
                — {{ $situation->created_at->format('d/m/Y') }}
            </span>
        </div>
    </div>

    {{-- Tableau détail --}}
    <table>
        <thead>
            <tr>
                <th>Locataire</th>
                <th>Bien</th>
                <th class="text-right">Loyer dû</th>
                <th class="text-right">Perçu</th>
                <th class="text-right">Écart</th>
                <th class="text-right">Commission</th>
                <th class="text-right">Net</th>
            </tr>
        </thead>
        <tbody>
            @foreach($situation->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->locataire_nom }}</td>
                    <td>{{ $ligne->bien_description }}</td>
                    <td class="text-right">{{ format_fcfa($ligne->loyer_du) }}</td>
                    <td class="text-right">{{ format_fcfa($ligne->montant_percu) }}</td>
                    <td class="text-right" style="{{ $ligne->ecart >= 0 ? 'color:#166534' : 'color:#991b1b' }}">
                        {{ $ligne->ecart >= 0 ? '+' : '' }}{{ format_fcfa($ligne->ecart) }}
                    </td>
                    <td class="text-right">{{ format_fcfa($ligne->commission) }}</td>
                    <td class="text-right">{{ format_fcfa($ligne->montant_percu - $ligne->commission) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2">TOTAL</td>
                <td class="text-right">—</td>
                <td class="text-right">{{ format_fcfa($situation->total_loyers_percus) }}</td>
                <td></td>
                <td class="text-right">{{ format_fcfa($situation->commission_agence) }}</td>
                <td class="text-right">{{ format_fcfa($situation->net_proprietaire) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Résumé financier --}}
    <div class="info-block" style="margin-top:20px;">
        <div class="info-row">
            <span class="info-label">Total loyers perçus :</span>
            <span class="info-value" style="font-weight:600;">{{ format_fcfa($situation->total_loyers_percus) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Commission agence :</span>
            <span class="info-value">{{ format_fcfa($situation->commission_agence) }}</span>
        </div>
        <div class="info-row" style="border-top:1px solid #e2e8f0; padding-top:8px; margin-top:6px;">
            <span class="info-label" style="font-size:13px; color:#1e3a5f; font-weight:700;">NET À REVERSER :</span>
            <span class="info-value">
                <span class="amount">{{ format_fcfa($situation->net_proprietaire) }}</span>
            </span>
        </div>
    </div>

    {{-- Signature --}}
    <div class="signature-block">
        <div class="signature-item">
            <div class="signature-line">Signature du propriétaire</div>
        </div>
        <div class="signature-item">
            <div class="signature-line">L'Agence — {{ $agency->name }}</div>
        </div>
    </div>

    <div class="footer">
        Document généré par SGI Immo — {{ now()->format('d/m/Y H:i') }} — Confidentiel
    </div>

</x-pdf-layout>
