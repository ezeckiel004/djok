{{-- resources/views/emails/formation-internationale-notification-admin.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle demande - Formation Internationale</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .alert-header {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .alert-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }
        .alert-badge {
            display: inline-block;
            background: #fbbf24;
            color: #78350f;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #e5e7eb;
        }
        .reference {
            background: #1f2937;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            font-family: monospace;
            font-size: 16px;
            margin: 20px 0;
            border: 2px solid #fbbf24;
        }
        .card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .card-title {
            color: #dc2626;
            font-weight: 600;
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #dc2626;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-label {
            font-weight: 600;
            width: 150px;
            color: #4b5563;
        }
        .info-value {
            color: #1f2937;
            flex: 1;
        }
        .badge-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 5px 0;
        }
        .badge {
            background: #fef3c7;
            color: #92400e;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
        }
        .message-box {
            background: #f9fafb;
            padding: 15px;
            border-left: 4px solid #f59e0b;
            margin: 10px 0;
            white-space: pre-line;
        }
        .urgent-box {
            background: #fef2f2;
            border: 2px solid #dc2626;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 5px;
        }
        .btn-urgent {
            background: #dc2626;
        }
        .btn-urgent:hover {
            background: #b91c1c;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .statut-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
        }
        @media (max-width: 600px) {
            .info-row {
                flex-direction: column;
            }
            .info-label {
                width: 100%;
                margin-bottom: 5px;
            }
        }
        a {
            color: inherit;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="alert-header">
        <span class="alert-badge">🔔 ACTION REQUISE</span>
        <h1>NOUVELLE DEMANDE FORMATION INTERNATIONALE</h1>
        <p style="margin: 10px 0 0; opacity: 0.9;">{{ $dateDemande }}</p>
    </div>

    <div class="content">
        <div class="reference">
            RÉFÉRENCE : INT{{ $demande->id }}
        </div>

        <div class="urgent-box">
            <h3 style="color: #dc2626; margin: 0 0 10px;">⚠️ URGENT - À TRAITER SOUS 48H</h3>
            <p style="margin: 0;">Cette demande nécessite une réponse rapide pour maximiser les chances de conversion.</p>
        </div>

        <div class="card">
            <h3 class="card-title">📋 INFORMATIONS DU DEMANDEUR</h3>

            @if($nomEntreprise)
            <div class="info-row">
                <span class="info-label">Entreprise :</span>
                <span class="info-value"><strong>{{ $nomEntreprise }}</strong></span>
            </div>
            @endif

            <div class="info-row">
                <span class="info-label">Responsable :</span>
                <span class="info-value"><strong>{{ $nomResponsable }}</strong></span>
            </div>

            <div class="info-row">
                <span class="info-label">Email :</span>
                <span class="info-value">
                    <a href="mailto:{{ $demande->email }}" style="color: #2563eb;">{{ $demande->email }}</a>
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Téléphone :</span>
                <span class="info-value">
                    <a href="tel:{{ $demande->telephone }}" style="color: #2563eb;">{{ $demande->telephone }}</a>
                </span>
            </div>

            <div class="info-row">
                <span class="info-label">Statut actuel :</span>
                <span class="info-value">
                    <span class="statut-badge">{{ strtoupper($demande->statut_label) }}</span>
                </span>
            </div>
        </div>

        <div class="card">
            <h3 class="card-title">🌍 DÉTAILS DU PROJET</h3>

            @if($destination)
            <div class="info-row">
                <span class="info-label">Destination :</span>
                <span class="info-value"><strong>{{ $destination }}</strong></span>
            </div>
            @endif

            @if($nombreParticipants)
            <div class="info-row">
                <span class="info-label">Participants :</span>
                <span class="info-value">{{ $nombreParticipants }} personne(s)</span>
            </div>
            @endif

            @if(!empty($typeEvenements))
            <div class="info-row">
                <span class="info-label">Type(s) d'événement :</span>
                <span class="info-value">
                    <div class="badge-container">
                        @foreach($typeEvenements as $type)
                        <span class="badge">{{ $type }}</span>
                        @endforeach
                    </div>
                </span>
            </div>
            @endif
        </div>

        <div class="card">
            <h3 class="card-title">📝 MESSAGE DU DEMANDEUR</h3>
            <div class="message-box">
                {{ $demande->message }}
            </div>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $adminUrl }}" class="btn btn-urgent" style="padding: 15px 30px; font-size: 16px;">
                🖥️ GÉRER CETTE DEMANDE
            </a>
        </div>

        <div style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; margin: 20px 0;">
            <a href="mailto:{{ $demande->email }}" class="btn" style="background: #059669;">📧 Répondre par email</a>
            <a href="tel:{{ $demande->telephone }}" class="btn" style="background: #0ea5e9;">📞 Appeler</a>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $demande->telephone) }}" class="btn" style="background: #25D366;">💬 WhatsApp</a>
        </div>

        <p style="text-align: center; color: #6b7280; margin-top: 20px;">
            <small>Demande reçue le {{ $dateDemande }}</small>
        </p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} DJOK PRESTIGE - Notification automatique</p>
        <p>Ceci est un email automatique envoyé par le système de gestion des formations internationales.</p>
    </div>
</body>
</html>
