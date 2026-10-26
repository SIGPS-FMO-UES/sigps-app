<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ExpedienteRecibidoNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly int $proyectoId,
        public readonly string $proyecto,
        public readonly string $fase,
        public readonly string $codigoAval,
    ) {}

    /**
     * Store the notification for the authenticated UPS staff member.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, int|string>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Nuevo expediente recibido',
            'message' => $this->proyecto.' · '.str_replace('_', ' ', ucfirst($this->fase)),
            'proyecto_id' => $this->proyectoId,
            'proyecto' => $this->proyecto,
            'fase' => $this->fase,
            'codigo_aval' => $this->codigoAval,
        ];
    }
}
