<x-pdf-layout title="Reçu de paiement">

    <div class="header">
        <div class="header-agency">
            <div class="agency-name">{{ $agency->name }}</div>
            <div class="agency-info">
                {{ $agency->adresse }}<br>
                {{ $agency->ville }}<br>
                Tél : {{ $agency->telephone }}<br>
                Email : {{ $agency->email }}
            </div>
        </div>
        <div class="doc-title">
            <h1>REÇU DE PAIEMENT</h1>
            <div class="doc-number">N° {{ $recu->numero }}</div>
            <div class="doc-date">Émis le {{ $recu->created_at->format('d/m/Y') }}</div>
        </div>
    </div>

    <div class="info-block">
        <div class="info-row">
            <span class="info-label">Locataire :</span>
            <span class="info-value">{{ $paiement->locataire->full_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Bien loué :</span>
            <span class="info-value">{{ $paiement->contrat->bien->description }} — {{ $paiement->contrat->bien->adresse }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Période :</span>
            <span class="info-value">{{ periode_label($paiement->periode) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Mode de paiement :</span>
            <span class="info-value">{{ $paiement->mode_libelle }}</span>
        </div>
        @if($paiement->transaction_reference)
            <div class="info-row">
                <span class="info-label">Réf. transaction :</span>
                <span class="info-value">{{ $paiement->transaction_reference }}</span>
            </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Désignation</th>
                <th class="text-right">Montant dû</th>
                <th class="text-right">Montant payé</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Loyer {{ periode_label($paiement->periode) }}</td>
                <td class="text-right">{{ format_fcfa($paiement->montant_du) }}</td>
                <td class="text-right">{{ format_fcfa($paiement->montant) }}</td>
            </tr>
            @if($paiement->avance)
                <tr>
                    <td>Avance de loyer</td>
                    <td class="text-right">—</td>
                    <td class="text-right">{{ format_fcfa($paiement->avance) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td>TOTAL ENCAISSÉ</td>
                <td class="text-right">{{ format_fcfa($paiement->montant_du) }}</td>
                <td class="text-right">{{ format_fcfa($paiement->montant) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: center; margin: 30px 0;">
        <span class="amount">{{ format_fcfa($paiement->montant) }}</span>
    </div>

    <div class="signature-block">
        <div class="signature-item">
            <div class="signature-line">Signature du locataire</div>
        </div>
        <div class="signature-item">
            <div class="signature-line">L'Agence — {{ $agency->name }}</div>
        </div>
    </div>

    <div class="footer">
        Document généré par SGI Immo — {{ now()->format('d/m/Y H:i') }}
    </div>

</x-pdf-layout>
