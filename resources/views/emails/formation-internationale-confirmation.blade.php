{{-- resources/views/emails/formation-internationale-confirmation.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de votre demande</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .header {
            background: linear-gradient(135deg, #caa24d 0%, #b38b3a 100%);
            color: black;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            border: 1px solid #e5e7eb;
        }
        .card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .card-title {
            color: #caa24d;
            font-weight: 600;
            margin: 0 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #caa24d;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: 600;
            width: 140px;
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
            margin: 10px 0;
        }
        .badge {
            background: #fef3c7;
            color: #92400e;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid #fde68a;
        }
        .message-box {
            background: #f9fafb;
            padding: 15px;
            border-left: 4px solid #caa24d;
            margin: 15px 0;
            white-space: pre-line;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 13px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .btn {
            display: inline-block;
            background: #caa24d;
            color: black;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 10px 0;
        }
        .btn:hover {
            background: #b38b3a;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>DJOK PRESTIGE International</h1>
        <p style="margin: 10px 0 0; opacity: 0.9;">Votre partenaire pour les formations et séminaires internationaux</p>
    </div>

    <div class="content">
        <h2 style="text-align: center; color: #caa24d;">Merci pour votre demande !</h2>

        <p>Bonjour <strong>{{ $nomResponsable }}</strong>,</p>

        <p>Nous avons bien reçu votre demande d'organisation et nous vous en remercions. Notre équipe internationale va étudier votre projet et vous contactera dans les plus brefs délais.</p>

        <div class="reference">
            Référence : INT{{ $demande->id }}
        </div>

        <div class="card">
            <h3 class="card-title">Récapitulatif de votre demande</h3>

            @if($nomEntreprise)
            <div class="info-row">
                <span class="info-label">Entreprise :</span>
                <span class="info-value">{{ $nomEntreprise }}</span>
            </div>
            @endif

            <div class="info-row">
                <span class="info-label">Responsable :</span>
                <span class="info-value">{{ $nomResponsable }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Email :</span>
                <span class="info-value">{{ $demande->email }}</span>
            </div>

            <div class="info-row">
                <span class="info-label">Téléphone :</span>
                <span class="info-value">{{ $demande->telephone }}</span>
            </div>
        </div>

        <div class="card">
            <h3 class="card-title">Détails du projet</h3>

            @if($destination)
            <div class="info-row">
                <span class="info-label">Destination :</span>
                <span class="info-value">{{ $destination }}</span>
            </div>
            @endif

            @if($nombreParticipants)
            <div class="info-row">
                <span class="info-label">Participants :</span>
                <span class="info-value">{{ $nombreParticipants }}</span>
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
            <h3 class="card-title">Votre message</h3>
            <div class="message-box">
                {{ $demande->message }}
            </div>
        </div>

        <div style="background: #f0fdf4; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3 style="color: #065f46; margin-top: 0;">Les prochaines étapes</h3>
            <ol style="margin: 0; padding-left: 20px;">
                <li>Notre équipe vous contactera sous 48h</li>
                <li>Analyse détaillée de vos besoins</li>
                <li>Proposition personnalisée avec devis</li>
                <li>Organisation logistique et administrative</li>
            </ol>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="https://djokprestige.com/contact" class="btn">
                Nous contacter
            </a>
        </div>

        <p>Notre équipe reste à votre disposition pour toute information complémentaire.</p>

        <p>Cordialement,<br>
        <strong>L'équipe Internationale DJOK PRESTIGE</strong></p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} DJOK PRESTIGE - Tous droits réservés</p>
        <p>Email automatique de confirmation - Ne pas répondre à cet email</p>
        <p style="font-size: 12px; margin-top: 15px;">
            Téléphone : {{ $telephoneContact }} | WhatsApp : {{ $whatsappContact }} | Email : {{ $emailContact }}
        </p>
    </div>
</body>
</html>
