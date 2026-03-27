<?php

return [
    'title' => 'Inscription Formation Présentielle',

    'breadcrumb' => [
        'home' => 'Accueil',
        'trainings' => 'Formations',
        'registration' => 'Inscription',
    ],

    'header' => [
        'title' => 'Inscription à la formation',
        'format' => 'Formation en présentiel',
    ],

    'form' => [
        'title' => 'Formulaire d\'inscription',
        'subtitle' => 'Remplissez le formulaire ci-dessous pour vous inscrire à cette formation',

        'prefilled_info' => 'Informations pré-remplies',
        'connected_as' => 'Vous êtes connecté(e) en tant que',
        'info_prefilled' => 'Certaines informations ont été pré-remplies pour vous faciliter la tâche. Vous pouvez les modifier si nécessaire.',

        // AJOUT DE LA CLÉ min_age ICI
        'min_age' => 'Âge minimum : 16 ans',

        'sections' => [
            'personal_info' => 'Informations personnelles',
            'address' => 'Adresse',
            'funding' => 'Mode de financement',
            'additional_info' => 'Informations complémentaires',
        ],

        'fields' => [
            'civility' => 'Civilité',
            'last_name' => 'Nom',
            'first_name' => 'Prénom',
            'email' => 'Email',
            'phone' => 'Téléphone',
            'birth_date' => 'Date de naissance',
            'license_date' => 'Date d\'obtention du permis',
            'address' => 'Adresse',
            'zip_code' => 'Code postal',
            'city' => 'Ville',
            'message' => 'Message',
            'optional' => 'optionnel',
        ],

        'placeholders' => [
            'last_name' => 'Votre nom',
            'first_name' => 'Votre prénom',
            'email' => 'votre@email.com',
            'phone' => '+33 1 23 45 67 89',
            'date_format' => 'Format: JJ/MM/AAAA',
            'min_age' => 'Âge minimum: 16 ans',
            'address' => 'Numéro et rue',
            'zip_code' => '75000',
            'city' => 'Paris',
            'message' => 'Précisez ici vos attentes, questions ou informations supplémentaires...',
            'message_desc' => 'Vous pouvez préciser vos disponibilités, vos objectifs spécifiques ou toute autre information utile.',
        ],

        'options' => [
            'mr' => 'Monsieur',
            'mrs' => 'Madame',
        ],

        'funding' => [
            'personal' => 'Financement personnel',
            'personal_desc' => 'Paiement direct par carte bancaire ou virement',
            'cpf' => 'CPF (Compte Personnel de Formation)',
            'cpf_desc' => 'Utilisation de vos heures de formation DIF',
            'pole_emploi' => 'Pôle Emploi',
            'pole_emploi_desc' => 'Prise en charge par Pôle Emploi (AIF)',
        ],

        'session' => [
            'selected' => 'Vous vous inscrivez à la session :',
            'places_left' => 'Places restantes',
        ],

        'terms' => [
            'accept' => 'J\'accepte les',
            'terms' => 'conditions générales d\'utilisation',
            'and' => 'et la',
            'privacy' => 'politique de confidentialité',
        ],

        'submit' => 'Envoyer ma demande d\'inscription',
        'confirmation' => 'Vous recevrez un email de confirmation sous 24h.',
    ],

    'summary' => [
        'title' => 'Détails de la formation',
        'training' => 'Formation',
        'duration' => 'Durée',
        'format' => 'Format',
        'in_person' => 'Présentiel',
        'price' => 'Tarif',
        'quote' => 'Sur devis',
        'exam_fees' => 'de frais d\'examen',
    ],

    'program' => [
        'title' => 'Programme',
        'other_modules' => 'autres modules',
    ],

    'documents' => [
        'title' => 'Documents requis',
        'id_copy' => 'Copie de la pièce d\'identité',
        'residence_proof' => 'Justificatif de domicile (< 3 mois)',
        'driving_license' => 'Permis de conduire',
        'id_photo' => 'Photo d\'identité',
        'send_to' => 'Envoyer les documents à :',
    ],

    'contact' => [
        'title' => 'Besoin d\'aide ?',
        'phone_hours' => 'Lun-Ven 9h-19h',
        'email_response' => 'Réponse sous 24h',
    ],

    'messages' => [
        'error' => 'Erreur',
        'success' => 'Succès !',
    ],

    'validation' => [
        'required_fields' => 'Veuillez remplir tous les champs obligatoires.',
        'min_age' => 'Vous devez avoir au moins 16 ans pour vous inscrire.',
        'license_future' => 'La date du permis ne peut pas être dans le futur.',
    ],

    'back_button' => 'Retour à la formation',
];
