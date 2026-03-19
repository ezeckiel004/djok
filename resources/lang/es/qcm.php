<?php

return [
    // Títulos y encabezados
    'page_title' => 'QCM - DJOK PRESTIGE',
    'virtual_room' => 'Sala',
    'important_note' => 'Nota importante',
    'previous_score' => 'Tu puntuación anterior fue de',
    'attempts' => 'intentos',
    'last_attempt_warning' => 'Este es tu último intento autorizado.',

    // Información QCM
    'questions' => 'preguntas',
    'white_exam' => 'Examen de práctica',
    'minimum_score' => 'Puntuación mínima',
    'time_limit' => 'Tiempo límite',
    'attempts_allowed' => 'Intentos',
    'qcm_type' => 'Tipo de QCM',
    'multiple_answers' => 'Múltiples respuestas',

    // Instrucciones
    'white_exam_instructions' => 'Instrucciones para el examen de práctica',
    'white_exam_description' => 'Este examen de práctica simula las condiciones reales del examen. Tienes <strong>:minutes minutos</strong> para responder todas las preguntas. Una vez terminado, verás tu puntuación y podrás revisar tus respuestas.',
    'multiple_answers_instructions' => 'Instrucciones para QCM de múltiples respuestas',
    'multiple_answers_description' => '<strong>Este QCM permite múltiples respuestas correctas por pregunta.</strong> Algunas preguntas pueden tener una o varias respuestas correctas. Marca todas las respuestas que te parezcan correctas para cada pregunta.',
    'full_points' => 'Puntos completos si se marcan todas las respuestas correctas',
    'partial_points' => 'Puntos parciales según el número de respuestas identificadas correctamente',

    // Temporizador
    'time_remaining' => 'Tiempo restante',

    // Preguntas
    'select_all_correct_answers' => 'Selecciona todas las respuestas correctas',
    'no_questions_available' => 'No hay preguntas disponibles',
    'no_questions_message' => 'Este QCM no contiene preguntas en este momento.',

    // Progreso
    'answered' => 'Has respondido',
    'out_of' => 'de',
    'questions_answered' => 'preguntas',
    'multiple_answers_qcm' => 'QCM de múltiples respuestas',
    'all_questions_answered' => 'Todas las preguntas respondidas - Terminar',

    // Botones
    'back' => 'Volver',
    'finish_qcm' => 'Terminar QCM',
    'calculating_score' => 'Calculando puntuación...',

    // Resultados
    'qcm_results' => 'Resultados del QCM',
    'your_score' => 'Tu puntuación',
    'minimum_required' => 'Mínimo requerido',
    'answer_details' => 'Detalle de respuestas',
    'view_details' => 'Ver detalles',
    'back_to_room' => 'Volver a la sala',

    // Mensajes de éxito
    'congratulations' => '¡Felicitaciones!',
    'success_message' => 'Has aprobado el QCM con :score%.',
    'failure_title' => 'No aprobado',
    'failure_message' => 'Tu puntuación de :score% es inferior a la puntuación mínima requerida (:required_score%).',

    // Detalles de preguntas
    'question' => 'Pregunta',
    'correct' => 'Correcta',
    'incorrect' => 'Incorrecta',
    'points' => 'puntos',

    // Alertas y confirmaciones
    'confirm_navigation' => 'Tienes respuestas no guardadas. ¿Estás seguro de que quieres salir de esta página?',
    'alert_no_answers' => 'Por favor responde al menos a una pregunta antes de enviar.',
    'confirm_submit' => 'Has respondido a :answered de :total preguntas. ¿Aún deseas enviar tus respuestas?',

    // Detección de fraude
    'fraud_detected' => '¡Advertencia! Fraude detectado',
    'fraud_warning' => 'Has intentado recargar la página durante el QCM.',
    'fraud_action' => 'Esta acción se considera un intento de fraude.',
    'fraud_redirect' => 'Serás redirigido inmediatamente a la sala virtual.',
    'cancel' => 'Cancelar',
    'confirm' => 'Confirmar',

    // Mensajes de error
    'submit_error' => 'Error durante el envío.',
    'server_error' => 'El servidor encontró un error interno.',
    'check_logs' => 'Por favor verifica los logs de Laravel (storage/logs/laravel.log).',
    'validation_error' => 'Hay un problema con los datos enviados.',
    'connection_error' => 'No se puede conectar al servidor. Verifica tu conexión a internet.',

    // Información técnica
    'technical_details' => 'Detalles técnicos',
    'complete_debug_info' => '=== INFORMACIÓN DE DEPURACIÓN ===',
];
