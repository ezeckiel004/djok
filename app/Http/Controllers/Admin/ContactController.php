<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReplied;
use App\Mail\NewContactAdmin;

class ContactController extends Controller
{
    /**
     * Afficher la liste des messages de contact
     */
    public function index()
    {
        $contacts = ContactMessage::with('service')
            ->orderBy('is_read', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Afficher un message spécifique
     */
    public function show(ContactMessage $contact)
    {
        // Marquer le message comme lu quand on l'affiche
        if (!$contact->is_read) {
            $contact->markAsRead();
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Mettre à jour un message
     */
    public function update(Request $request, ContactMessage $contact)
    {
        $validated = $request->validate([
            'is_read' => 'nullable|boolean',
            'is_replied' => 'nullable|boolean',
            'replied_at' => 'nullable|date',
            'reply_message' => 'nullable|string',
        ]);

        // Sauvegarder l'ancien statut
        $wasReplied = $contact->is_replied;

        // Si on marque comme répondu, mettre à jour la date
        if (isset($validated['is_replied']) && $validated['is_replied'] && !$contact->replied_at) {
            $validated['replied_at'] = now();
        }

        $contact->update($validated);

        // Si on vient de marquer comme répondu, envoyer un email
        if (!$wasReplied && $contact->is_replied) {
            $this->sendReplyNotification($contact);
        }

        return back()->with('success', 'Message mis à jour avec succès');
    }

    /**
     * Supprimer un message
     */
    public function destroy(ContactMessage $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Message supprimé avec succès');
    }

    /**
     * Mise à jour rapide via AJAX
     */
    public function quickUpdate(Request $request, ContactMessage $contact)
    {
        $validated = $request->validate([
            'is_read' => 'nullable|boolean',
            'is_replied' => 'nullable|boolean',
        ]);

        // Sauvegarder l'ancien statut
        $wasReplied = $contact->is_replied;

        // Gérer la date de réponse automatiquement
        if (isset($validated['is_replied']) && $validated['is_replied'] && !$contact->replied_at) {
            $validated['replied_at'] = now();
        }

        $contact->update($validated);

        // Si on vient de marquer comme répondu, envoyer un email
        if (!$wasReplied && $contact->is_replied) {
            $this->sendReplyNotification($contact);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Envoyer une notification de réponse au client
     */
    private function sendReplyNotification(ContactMessage $contact)
    {
        try {
            Mail::to($contact->email)->send(new ContactReplied($contact));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email réponse contact: ' . $e->getMessage());
        }
    }

    /**
     * Envoyer une notification de nouveau message à l'admin
     * Méthode statique pour être appelée depuis n'importe où
     */
    public static function notifyAdmin(ContactMessage $contact)
    {
        try {
            $adminEmail = config('mail.admin_email', 'admin@djokprestige.com');
            \Log::info('Notification admin - tentative d\'envoi', [
                'contact_id' => $contact->id,
                'contact_nom' => $contact->nom,
                'contact_email' => $contact->email,
                'admin_to' => $adminEmail,
                'mailer' => config('mail.default'),
                'mail_host' => config('mail.mailers.smtp.host'),
                'mail_port' => config('mail.mailers.smtp.port'),
                'mail_from' => config('mail.from.address'),
            ]);

            $mail = new NewContactAdmin($contact);
            Mail::to($adminEmail)->send($mail);

            \Log::info('Notification admin - envoi réussi', [
                'contact_id' => $contact->id,
                'admin_to' => $adminEmail,
                'subject' => $mail->subject,
            ]);
        } catch (\Exception $e) {
            \Log::error('Notification admin - ÉCHEC d\'envoi', [
                'contact_id' => $contact->id ?? null,
                'admin_to' => config('mail.admin_email', 'admin@djokprestige.com'),
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Marquer tous les messages comme lus
     */
    public function markAllAsRead()
    {
        ContactMessage::where('is_read', false)->update(['is_read' => true]);

        return back()->with('success', 'Tous les messages ont été marqués comme lus');
    }
}
