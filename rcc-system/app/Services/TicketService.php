<?php

namespace App\Services;

use App\Models\EventParticipation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Generator;

class TicketService
{
    public function generateAndSend(EventParticipation $participation): void
    {
        $uuid = $participation->ticket_uuid ?? (string) \Illuminate\Support\Str::uuid();
        $qrData = 'TICKET:'.$uuid;
        $qr = (new Generator)->size(300)->format('png')->generate($qrData);

        $qrPath = 'tickets/qr_'.$uuid.'.png';
        Storage::disk('local')->put($qrPath, $qr);

        $pdf = Pdf::loadView('tickets.pdf', [
            'participation' => $participation,
            'qrPath' => storage_path('app/'.$qrPath),
        ]);
        $pdfPath = 'tickets/ticket_'.$uuid.'.pdf';
        Storage::disk('local')->put($pdfPath, $pdf->output());

        $participation->update([
            'ticket_uuid' => $uuid,
            'ticket_qr_hash' => hash('sha256', $qrData),
        ]);

        \Mail::to($participation->user->email)->send(new \App\Mail\TicketMailable($participation, storage_path('app/'.$pdfPath)));

        \App\Models\WaMessage::create([
            'user_id' => $participation->user_id,
            'message' => 'Seu ingresso foi gerado.',
            'payload' => ['ticket_pdf' => storage_path('app/'.$pdfPath)],
            'status' => 'pending',
        ]);
    }

    public function generateAndSendTicket(EventParticipation $participation): void
    {
        $this->generateAndSend($participation);
    }
}
