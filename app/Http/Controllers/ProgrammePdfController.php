<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ProgrammePdfController extends Controller
{
    /**
     * Afficher le programme PDF (en ligne)
     */
    public function show($formationId)
    {
        $formation = Formation::findOrFail($formationId);

        // Si un PDF personnalisé existe, le servir directement
        if ($formation->programme_pdf_exists) {
            return Storage::disk('public')->response($formation->programme_pdf, null, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="programme-' . Str::slug($formation->title) . '.pdf"'
            ]);
        }

        // Sinon, vérifier si on a du contenu texte pour générer un PDF
        if ($formation->program && count($formation->program) > 0) {
            // Générer un PDF à partir des données texte
            return $this->generateAndShow($formation);
        }

        // Si rien n'est disponible, afficher une erreur
        abort(404, 'Aucun programme disponible pour cette formation.');
    }

    /**
     * Télécharger le programme PDF
     */
    public function download($formationId)
    {
        $formation = Formation::findOrFail($formationId);

        // Si un PDF personnalisé existe, le télécharger
        if ($formation->programme_pdf_exists) {
            return Storage::disk('public')->download($formation->programme_pdf, 'programme-' . Str::slug($formation->title) . '.pdf');
        }

        // Sinon, vérifier si on a du contenu texte pour générer un PDF
        if ($formation->program && count($formation->program) > 0) {
            return $this->generateAndDownload($formation);
        }

        // Si rien n'est disponible, afficher une erreur
        abort(404, 'Aucun programme disponible pour cette formation.');
    }

    /**
     * Générer et afficher le PDF à partir des données texte
     */
    private function generateAndShow($formation)
    {
        $pdf = Pdf::loadView('pdf.programme-formation', compact('formation'));
        return $pdf->stream('programme-' . Str::slug($formation->title) . '.pdf');
    }

    /**
     * Générer et télécharger le PDF à partir des données texte
     */
    private function generateAndDownload($formation)
    {
        $pdf = Pdf::loadView('pdf.programme-formation', compact('formation'));
        return $pdf->download('programme-' . Str::slug($formation->title) . '.pdf');
    }

    /**
     * Générer et sauvegarder le PDF (pour l'admin)
     * Permet de générer un PDF à partir du texte et de le sauvegarder
     */
    public function generateAndSave($formationId)
    {
        // Vérifier que l'utilisateur est admin
        if (!auth()->check() || !auth()->user()->hasRole('admin')) {
            abort(403, 'Accès non autorisé');
        }

        $formation = Formation::findOrFail($formationId);

        // Vérifier qu'on a du contenu à générer
        if (!$formation->program || count($formation->program) == 0) {
            return redirect()->back()->with('error', 'Impossible de générer le PDF : aucun programme texte n\'est défini.');
        }

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.programme-formation', compact('formation'));
        $pdfContent = $pdf->output();

        // Sauvegarder
        $fileName = 'programme_' . Str::slug($formation->title) . '_' . time() . '.pdf';
        $folder = "formations/{$formation->id}/programme";

        // Créer le dossier s'il n'existe pas
        if (!Storage::disk('public')->exists($folder)) {
            Storage::disk('public')->makeDirectory($folder, 0755, true);
        }

        $path = $folder . '/' . $fileName;
        Storage::disk('public')->put($path, $pdfContent);

        $formation->update([
            'programme_pdf' => $path,
            'programme_pdf_generated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'PDF généré et sauvegardé avec succès.');
    }
}
