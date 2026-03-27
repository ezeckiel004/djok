<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProgrammePdfController extends Controller
{
    /**
     * Générer et afficher le PDF programme d'une formation
     */
    public function show($formationId)
    {
        Log::info('=== DÉBUT ProgrammePdfController@show ===');
        Log::info('Formation ID: ' . $formationId);

        try {
            // Récupérer la formation avec ses médias
            $formation = Formation::with(['media' => function ($query) {
                $query->where('type', 'pdf')->orderBy('order');
            }])
                ->findOrFail($formationId);

            Log::info('Formation trouvée: ' . $formation->title);

            // Vérifier si un PDF existe déjà dans le stockage et s'il n'est pas trop vieux
            if ($formation->programme_pdf_exists && !$formation->shouldRegeneratePdf()) {
                $existingPdfPath = storage_path('app/public/' . $formation->programme_pdf);
                if (file_exists($existingPdfPath)) {
                    Log::info('PDF existant trouvé, affichage: ' . $formation->programme_pdf);
                    return response()->file($existingPdfPath);
                }
            }

            // Générer le PDF en ligne (stream)
            Log::info('Génération du PDF en ligne...');
            return $this->generateAndStreamPdf($formation);
        } catch (\Exception $e) {
            Log::error('Erreur dans ProgrammePdfController@show: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());

            // Rediriger vers la page formation avec un message d'erreur
            return redirect()->route('formation')
                ->with('error', 'Impossible de générer le programme PDF. Veuillez réessayer plus tard.');
        }
    }

    /**
     * Générer et télécharger le PDF
     */
    public function download($formationId)
    {
        Log::info('=== DÉBUT ProgrammePdfController@download ===');
        Log::info('Formation ID: ' . $formationId);

        try {
            $formation = Formation::with(['media' => function ($query) {
                $query->where('type', 'pdf')->orderBy('order');
            }])
                ->findOrFail($formationId);

            Log::info('Formation trouvée: ' . $formation->title);

            // Vérifier si un PDF existe déjà
            if ($formation->programme_pdf_exists && !$formation->shouldRegeneratePdf()) {
                $existingPdfPath = storage_path('app/public/' . $formation->programme_pdf);
                if (file_exists($existingPdfPath)) {
                    Log::info('PDF existant trouvé, téléchargement: ' . $formation->programme_pdf);
                    return response()->download(
                        $existingPdfPath,
                        'programme-' . Str::slug($formation->title) . '.pdf'
                    );
                }
            }

            // Générer et télécharger le PDF
            Log::info('Génération du PDF pour téléchargement...');
            return $this->generateAndDownloadPdf($formation);
        } catch (\Exception $e) {
            Log::error('Erreur dans ProgrammePdfController@download: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());

            return redirect()->route('formation')
                ->with('error', 'Impossible de télécharger le programme PDF. Veuillez réessayer plus tard.');
        }
    }

    /**
     * Générer et streamer le PDF
     */
    private function generateAndStreamPdf(Formation $formation)
    {
        Log::info('Début de la génération du PDF en streaming...');

        // Données pour le PDF
        $data = $this->preparePdfData($formation);

        // Générer le PDF
        $pdf = PDF::loadView('pdf.programme', $data);

        // Options PDF
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'helvetica',
            'dpi' => 150,
        ]);

        // Stream le PDF directement
        Log::info('Streaming du PDF...');
        return $pdf->stream('programme-' . Str::slug($formation->title) . '.pdf');
    }

    /**
     * Générer et sauvegarder le PDF
     */
    private function generateAndDownloadPdf(Formation $formation)
    {
        Log::info('Début de la génération du PDF pour téléchargement...');

        // Données pour le PDF
        $data = $this->preparePdfData($formation);

        // Générer le PDF
        $pdf = PDF::loadView('pdf.programme', $data);

        // Options PDF
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'helvetica',
            'dpi' => 150,
        ]);

        // Télécharger le PDF
        Log::info('Téléchargement du PDF...');
        return $pdf->download('programme-' . Str::slug($formation->title) . '.pdf');
    }

    /**
     * Préparer les données pour le PDF
     */
    private function preparePdfData(Formation $formation)
    {
        Log::info('Préparation des données du PDF...');

        // Formater les données de la formation
        $program = $formation->program ?? [];
        $requirements = $formation->requirements ?? [];
        $includedServices = $formation->included_services ?? [];

        // Statistiques des médias
        $pdfCount = $formation->media->where('type', 'pdf')->count();
        $videoCount = $formation->media->where('type', 'video')->count();

        // Informations de contact
        $contactInfo = [
            'telephone' => '01 76 38 00 17',
            'email' => 'formation@djokprestige.com',
            'adresse' => '123 Avenue des Champs-Élysées, 75008 Paris',
            'site' => 'https://djokprestige.com',
        ];

        return [
            'formation' => $formation,
            'program' => $program,
            'requirements' => $requirements,
            'includedServices' => $includedServices,
            'pdfCount' => $pdfCount,
            'videoCount' => $videoCount,
            'contactInfo' => $contactInfo,
            'generationDate' => now()->format('d/m/Y H:i'),
        ];
    }

    /**
     * Générer et sauvegarder le PDF (pour l'admin)
     */
    public function generateAndSave(Request $request, $formationId)
    {
        Log::info('=== DÉBUT ProgrammePdfController@generateAndSave ===');
        Log::info('Formation ID: ' . $formationId);

        try {
            $formation = Formation::findOrFail($formationId);
            Log::info('Formation: ' . $formation->title);

            // Préparer les données
            $data = $this->preparePdfData($formation);

            // Générer le PDF
            $pdf = PDF::loadView('pdf.programme', $data);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'helvetica',
                'dpi' => 150,
            ]);

            // Nom du fichier
            $filename = 'programme-' . Str::slug($formation->title) . '-' . time() . '.pdf';
            $path = 'formations/' . $formation->id . '/pdfs/' . $filename;

            // Créer le dossier s'il n'existe pas
            $directory = dirname(storage_path('app/public/' . $path));
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Sauvegarder le PDF
            Log::info('Sauvegarde du PDF: ' . $path);
            Storage::disk('public')->put($path, $pdf->output());

            // Supprimer l'ancien PDF s'il existe
            if ($formation->programme_pdf) {
                Storage::disk('public')->delete($formation->programme_pdf);
            }

            // Mettre à jour la formation
            $formation->update([
                'programme_pdf' => $path,
                'programme_pdf_generated_at' => now(),
            ]);

            Log::info('PDF sauvegardé avec succès');

            return redirect()->route('admin.formations.show', $formation)
                ->with('success', 'PDF programme généré et sauvegardé avec succès');
        } catch (\Exception $e) {
            Log::error('Erreur dans generateAndSave: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());

            return redirect()->route('admin.formations.show', $formationId)
                ->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }
}
