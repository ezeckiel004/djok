<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Programme - {{ $formation->title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            margin: 0;
            padding: 40px;
            color: #333;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #b89449;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #b89449;
            font-size: 24px;
            margin: 0 0 10px 0;
        }

        .header .subtitle {
            color: #666;
            font-size: 14px;
        }

        .info-section {
            background: #f5f5f5;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: bold;
            color: #b89449;
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 14px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            color: #b89449;
            border-left: 4px solid #b89449;
            padding-left: 15px;
            margin-bottom: 20px;
        }

        .description {
            background: #fafafa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .program-list {
            list-style: none;
            padding: 0;
        }

        .program-item {
            padding: 12px;
            margin-bottom: 10px;
            background: #f9f9f9;
            border-left: 3px solid #b89449;
            border-radius: 4px;
        }

        .program-number {
            display: inline-block;
            font-weight: bold;
            color: #b89449;
            margin-right: 10px;
        }

        .requirements-list, .services-list {
            list-style: none;
            padding: 0;
        }

        .requirements-list li, .services-list li {
            padding: 8px 0;
            padding-left: 25px;
            position: relative;
        }

        .requirements-list li:before, .services-list li:before {
            content: "✓";
            color: #b89449;
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #999;
        }

        @media print {
            body {
                padding: 20px;
            }
            .info-section {
                break-inside: avoid;
            }
            .program-item {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $formation->title }}</h1>
        <div class="subtitle">Programme détaillé de la formation</div>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Durée</span>
                <span class="info-value">{{ $formation->duree ?? $formation->duration_hours . ' heures' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Format</span>
                <span class="info-value">{{ $formation->format_affichage ?? ucfirst($formation->format_type) }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Type</span>
                <span class="info-value">{{ $formation->type_formation === 'e_learning' ? 'En ligne' : 'Présentiel' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Prix</span>
                <span class="info-value">{{ number_format($formation->price, 0, ',', ' ') }} € TTC</span>
            </div>
            @if($formation->frais_examen)
            <div class="info-item">
                <span class="info-label">Frais d'examen</span>
                <span class="info-value">{{ $formation->frais_examen }}</span>
            </div>
            @endif
            @if($formation->location_vehicule)
            <div class="info-item">
                <span class="info-label">Location véhicule</span>
                <span class="info-value">{{ $formation->location_vehicule }}</span>
            </div>
            @endif
        </div>
    </div>

    @if($formation->description)
    <div class="section">
        <h2 class="section-title">Description de la formation</h2>
        <div class="description">
            {!! nl2br(e($formation->description)) !!}
        </div>
    </div>
    @endif

    @if($formation->program && count($formation->program) > 0)
    <div class="section">
        <h2 class="section-title">Programme détaillé</h2>
        <div class="program-list">
            @foreach($formation->program as $index => $item)
            <div class="program-item">
                <span class="program-number">{{ $index + 1 }}.</span>
                {{ $item }}
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($formation->requirements && count($formation->requirements) > 0)
    <div class="section">
        <h2 class="section-title">Prérequis</h2>
        <ul class="requirements-list">
            @foreach($formation->requirements as $requirement)
            <li>{{ $requirement }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($formation->included_services && count($formation->included_services) > 0)
    <div class="section">
        <h2 class="section-title">Services inclus</h2>
        <ul class="services-list">
            @foreach($formation->included_services as $service)
            <li>{{ $service }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($formation->is_certified)
    <div class="section">
        <h2 class="section-title">Certification</h2>
        <p>Cette formation est certifiée et délivre un certificat reconnu à son issue.</p>
    </div>
    @endif

    <div class="footer">
        <p>DJOK PRESTIGE - Formation professionnelle VTC</p>
        <p>Document généré le {{ now()->format('d/m/Y') }}</p>
    </div>
</body>
</html>
