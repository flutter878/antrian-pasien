<?php

namespace App\Notifications;

use App\Models\Pendaftaran;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PanggilanAntrian extends Notification
{
    use Queueable;

    protected Pendaftaran $pendaftaran;

    public function __construct(Pendaftaran $pendaftaran)
    {
        $this->pendaftaran = $pendaftaran;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => 'Panggilan Antrian',
            'message' => "Nomor antrian Anda {$this->pendaftaran->no_antrian} untuk Dr. {$this->pendaftaran->dokter->nama} sedang dipanggil.",
            'pendaftaran_id' => $this->pendaftaran->id,
        ];
    }

    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
