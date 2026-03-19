<?php

return [
    // Titres et en-têtes
    'page_title' => 'QCM - DJOK PRESTIGE',
    'virtual_room' => 'Salle',
    'important_note' => 'Note importante',
    'previous_score' => 'Votre précédent score était de',
    'attempts' => 'tentatives',
    'last_attempt_warning' => 'Ceci est votre dernière tentative autorisée.',

    // Informations QCM
    'questions' => 'questions',
    'white_exam' => 'Examen blanc',
    'minimum_score' => 'Note minimale',
    'time_limit' => 'Temps limite',
    'attempts_allowed' => 'Tentatives',
    'qcm_type' => 'Type de QCM',
    'multiple_answers' => 'Multi-réponses',

    // Instructions
    'white_exam_instructions' => 'Instructions pour l\'examen blanc',
    'white_exam_description' => 'Cet examen blanc simule les conditions réelles de l\'examen. Vous avez <strong>:minutes minutes</strong> pour répondre à toutes les questions. Une fois terminé, vous verrez votre score et pourrez revoir vos réponses.',
    'multiple_answers_instructions' => 'Instructions pour le QCM multi-réponses',
    'multiple_answers_description' => '<strong>Ce QCM autorise plusieurs réponses correctes par question.</strong> Certaines questions peuvent avoir une ou plusieurs réponses correctes. Cochez toutes les réponses qui vous semblent correctes pour chaque question.',
    'full_points' => 'Points complets si toutes les réponses correctes sont cochées',
    'partial_points' => 'Points partiels selon le nombre de réponses correctement identifiées',

    // Timer
    'time_remaining' => 'Temps restant',

    // Questions
    'select_all_correct_answers' => 'Sélectionnez toutes les réponses correctes',
    'no_questions_available' => 'Aucune question disponible',
    'no_questions_message' => 'Ce QCM ne contient aucune question pour le moment.',

    // Progression
    'answered' => 'Vous avez répondu à',
    'out_of' => 'sur',
    'questions_answered' => 'questions',
    'multiple_answers_qcm' => 'QCM multi-réponses',
    'all_questions_answered' => 'Toutes questions répondues - Terminer',

    // Boutons
    'back' => 'Retour',
    'finish_qcm' => 'Terminer le QCM',
    'calculating_score' => 'Calcul du score...',

    // Résultats
    'qcm_results' => 'Résultats du QCM',
    'your_score' => 'Votre score',
    'minimum_required' => 'Note minimale',
    'answer_details' => 'Détail des réponses',
    'view_details' => 'Voir détails',
    'back_to_room' => 'Retour à la salle',

    // Messages de réussite
    'congratulations' => 'Félicitations !',
    'success_message' => 'Vous avez réussi le QCM avec :score%.',
    'failure_title' => 'Non réussite',
    'failure_message' => 'Votre score de :score% est inférieur à la note minimale requise (:required_score%).',

    // Détails des questions
    'question' => 'Question',
    'correct' => 'Correcte',
    'incorrect' => 'Incorrecte',
    'points' => 'points',

    // Alertes et confirmations
    'confirm_navigation' => 'Vous avez des réponses non sauvegardées. Êtes-vous sûr de vouloir quitter cette page ?',
    'alert_no_answers' => 'Veuillez répondre au moins à une question avant de soumettre.',
    'confirm_submit' => 'Vous avez répondu à :answered sur :total questions. Souhaitez-vous quand même soumettre vos réponses ?',

    // Fraude détection
    'fraud_detected' => 'Attention ! Fraude détectée',
    'fraud_warning' => 'Vous avez tenté de recharger la page pendant le QCM.',
    'fraud_action' => 'Cette action est considérée comme une tentative de fraude.',
    'fraud_redirect' => 'Vous serez immédiatement redirigé vers la salle virtuelle.',
    'cancel' => 'Annuler',
    'confirm' => 'Confirmer',

    // Messages d'erreur
    'submit_error' => 'Erreur lors de la soumission.',
    'server_error' => 'Le serveur a rencontré une erreur interne.',
    'check_logs' => 'Veuillez vérifier les logs Laravel (storage/logs/laravel.log).',
    'validation_error' => 'Il y a un problème avec les données envoyées.',
    'connection_error' => 'Impossible de se connecter au serveur. Vérifiez votre connexion internet.',

    // Infos techniques
    'technical_details' => 'Détails techniques',
    'complete_debug_info' => '=== DEBUG INFO ===',
];
