<?php

namespace App\Mail;

use App\Enums\StatusEnum;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        private readonly StatusEnum $old,
        private readonly StatusEnum $new,
        private readonly Order $order,
    ) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->view('order-status-changed', [
            'old' => $this->old,
            'new' => $this->new,
            'order' => $this->order
        ]);
    }
}
