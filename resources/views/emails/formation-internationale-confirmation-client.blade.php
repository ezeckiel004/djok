{{-- resources/views/emails/formation-internationale-confirmation.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de votre demande - DJOK PRESTIGE</title>
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
            font-size: 26px;
            font-weight: 700;
        }

        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
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
            padding: 25px;
            margin: 25px 0;
        }

        .card-title {
            color: #caa24d;
            font-weight: 600;
            margin: 0 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #caa24d;
            font-size: 18px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: 600;
            color: #4b5563;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .info-value {
            color: #1f2937;
            font-size: 15px;
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
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid #fde68a;
        }

        .message-box {
            background: #f9fafb;
            padding: 20px;
            border-left: 4px solid #caa24d;
            margin: 15px 0;
            white-space: pre-line;
            border-radius: 4px;
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
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 10px 0;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #b38b3a;
        }

        .reference {
            background: #1f2937;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            font-family: monospace;
            font-size: 18px;
            font-weight: 600;
            margin: 25px 0;
            letter-spacing: 1px;
        }

        .contact-info {
            background: #f0fdf4;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
            border: 1px solid #bbf7d0;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            text-align: center;
            margin-top: 15px;
        }

        .contact-item {
            padding: 10px;
        }

        .contact-item strong {
            color: #065f46;
            display: block;
            margin-bottom: 5px;
        }

        .step {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 6px;
            border-left: 3px solid #caa24d;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            .contact-grid {
                grid-template-columns: 1fr;
            }
            .header {
                padding: 25px 20px;
            }
            .content {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>DJOK PRESTIGE International</h1>
        <p>Votre partenaire pour les formations et séminaires internationaux</p>
    </div>

    <div class="content">
        <h2 style="text-align: center; color: #caa24d;">Demande confirmée</h2>

        <p>Bonjour <strong>{{ $nomResponsable ?? $demande->nom_complet }}</strong>,</p>

        <p>Nous avons bien reçu votre demande d'organisation et nous vous en remercions. Notre équipe internationale va étudier votre projet et vous contactera dans les plus brefs délais.</p>

        <!-- Référence -->
        <div class="reference">
            Référence : INT{{ $demande->id }}
        </div>

        <!-- Informations personnelles -->
        <div class="card">
            <h3 class="card-title">Vos informations</h3>

            <div class="info-grid">
                @if($nomEntreprise)
                <div class="info-item">
                    <div class="info-label">Entreprise</div>
                    <div class="info-value">{{ $nomEntreprise }}</div>
                </div>
                @endif

                <div class="info-item">
                    <div class="info-label">Responsable</div>
                    <div class="info-value">{{ $nomResponsable ?? $demande->nom_complet }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $demande->email }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Téléphone</div>
                    <div class="info-value">{{ $demande->telephone }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Date de la demande</div>
                    <div class="info-value">{{ $dateDemande }}</div>
                </div>
            </div>
        </div>

        <!-- Détails du projet -->
        <div class="card">
            <h3 class="card-title">Détails de votre projet</h3>

            <div class="info-grid">
                @if($destination)
                <div class="info-item">
                    <div class="info-label">Destination souhaitée</div>
                    <div class="info-value">{{ $destination }}</div>
                </div>
                @endif

                @if($nombreParticipants)
                <div class="info-item">
                    <div class="info-label">Nombre de participants</div>
                    <div class="info-value">{{ $nombreParticipants }}</div>
                </div>
                @endif

                @if(!empty($typeEvenements))
                <div class="info-item" style="grid-column: span 2;">
                    <div class="info-label">Type(s) d'événement</div>
                    <div class="badge-container">
                        @foreach($typeEvenements as $type)
                        <span class="badge">{{ $type }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Votre message -->
        <div class="card">
            <h3 class="card-title">Votre message</h3>
            <div class="message-box">
                {{ $demande->message }}
            </div>
        </div>

        <!-- Prochaines étapes -->
        <div style="background: #f8fafc; padding: 25px; border-radius: 8px; margin: 25px 0;">
            <h3 style="color: #caa24d; margin-top: 0;">Les prochaines étapes</h3>

            <div class="step">
                <strong>1. Contact initial</strong>
                <p style="margin: 5px 0 0;">Notre conseiller vous contactera sous 48 heures</p>
            </div>

            <div class="step">
                <strong>2. Étude personnalisée</strong>
                <p style="margin: 5px 0 0;">Analyse détaillée de vos besoins et objectifs</p>
            </div>

            <div class="step">
                <strong>3. Proposition sur mesure</strong>
                <p style="margin: 5px 0 0;">Programme adapté avec devis détaillé</p>
            </div>

            <div class="step">
                <strong>4. Organisation</strong>
                <p style="margin: 5px 0 0;">Planification logistique et administrative</p>
            </div>
        </div>

        <!-- Contact -->
        <div class="contact-info">
            <h3 style="color: #065f46; margin: 0 0 15px; text-align: center;">Notre équipe est à votre écoute</h3>

            <div class="contact-grid">
                <div class="contact-item">
                    <strong>Téléphone</strong>
                    <span>{{ $telephoneContact }}</span>
                </div>
                <div class="contact-item">
                    <strong>WhatsApp</strong>
                    <span>{{ $whatsappContact }}</span>
                </div>
                <div class="contact-item">
                    <strong>Email</strong>
                    <span>{{ $emailContact }}</span>
                </div>
            </div>

            <p style="text-align: center; margin: 15px 0 0; color: #4b5563;">
                Disponibilité : Lundi - Vendredi, 8h - 18h
            </p>
        </div>

        <!-- Bouton d'action -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="https://djokprestige.com/contact" class="btn">
                Nous contacter
            </a>
        </div>

        <p>Nous sommes ravis de pouvoir vous accompagner dans votre projet.</p>
        <p>A très bientôt,</p>
        <p><strong>L'équipe DJOK PRESTIGE International</strong></p>
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <p>© {{ date('Y') }} DJOK PRESTIGE. Tous droits réservés.</p>
        <p>Email automatique de confirmation - Ne pas répondre à cet email</p>
        <p style="margin-top: 15px;">
            <a href="https://djokprestige.com/cgv" style="color: #6b7280; margin: 0 10px;">Conditions Générales</a> |
            <a href="https://djokprestige.com/confidentialite" style="color: #6b7280; margin: 0 10px;">Confidentialité</a> |
            <a href="https://djokprestige.com/contact" style="color: #6b7280; margin: 0 10px;">Contact</a>
        </p>
    </div>
</body>
</html>
