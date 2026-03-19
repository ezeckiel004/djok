<?php

return [
    // Titles and headers
    'page_title' => 'QCM - DJOK PRESTIGE',
    'virtual_room' => 'Room',
    'important_note' => 'Important note',
    'previous_score' => 'Your previous score was',
    'attempts' => 'attempts',
    'last_attempt_warning' => 'This is your last authorized attempt.',

    // QCM information
    'questions' => 'questions',
    'white_exam' => 'Practice exam',
    'minimum_score' => 'Minimum score',
    'time_limit' => 'Time limit',
    'attempts_allowed' => 'Attempts',
    'qcm_type' => 'QCM type',
    'multiple_answers' => 'Multiple answers',

    // Instructions
    'white_exam_instructions' => 'Practice exam instructions',
    'white_exam_description' => 'This practice exam simulates real exam conditions. You have <strong>:minutes minutes</strong> to answer all questions. Once completed, you will see your score and can review your answers.',
    'multiple_answers_instructions' => 'Multiple answers QCM instructions',
    'multiple_answers_description' => '<strong>This QCM allows multiple correct answers per question.</strong> Some questions may have one or several correct answers. Check all answers that seem correct for each question.',
    'full_points' => 'Full points if all correct answers are checked',
    'partial_points' => 'Partial points based on number of correctly identified answers',

    // Timer
    'time_remaining' => 'Time remaining',

    // Questions
    'select_all_correct_answers' => 'Select all correct answers',
    'no_questions_available' => 'No questions available',
    'no_questions_message' => 'This QCM contains no questions at the moment.',

    // Progress
    'answered' => 'You answered',
    'out_of' => 'out of',
    'questions_answered' => 'questions',
    'multiple_answers_qcm' => 'Multiple answers QCM',
    'all_questions_answered' => 'All questions answered - Finish',

    // Buttons
    'back' => 'Back',
    'finish_qcm' => 'Finish QCM',
    'calculating_score' => 'Calculating score...',

    // Results
    'qcm_results' => 'QCM Results',
    'your_score' => 'Your score',
    'minimum_required' => 'Minimum required',
    'answer_details' => 'Answer details',
    'view_details' => 'View details',
    'back_to_room' => 'Back to room',

    // Success messages
    'congratulations' => 'Congratulations!',
    'success_message' => 'You passed the QCM with :score%.',
    'failure_title' => 'Not passed',
    'failure_message' => 'Your score of :score% is below the minimum required score (:required_score%).',

    // Question details
    'question' => 'Question',
    'correct' => 'Correct',
    'incorrect' => 'Incorrect',
    'points' => 'points',

    // Alerts and confirmations
    'confirm_navigation' => 'You have unsaved answers. Are you sure you want to leave this page?',
    'alert_no_answers' => 'Please answer at least one question before submitting.',
    'confirm_submit' => 'You answered :answered out of :total questions. Do you still want to submit your answers?',

    // Fraud detection
    'fraud_detected' => 'Warning! Fraud detected',
    'fraud_warning' => 'You attempted to reload the page during the QCM.',
    'fraud_action' => 'This action is considered a fraud attempt.',
    'fraud_redirect' => 'You will be immediately redirected to the virtual room.',
    'cancel' => 'Cancel',
    'confirm' => 'Confirm',

    // Error messages
    'submit_error' => 'Error during submission.',
    'server_error' => 'The server encountered an internal error.',
    'check_logs' => 'Please check Laravel logs (storage/logs/laravel.log).',
    'validation_error' => 'There is a problem with the submitted data.',
    'connection_error' => 'Unable to connect to the server. Check your internet connection.',

    // Technical info
    'technical_details' => 'Technical details',
    'complete_debug_info' => '=== DEBUG INFO ===',
];
