<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VouchersCreatedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public array $vouchers;
    public User $user;
    public array $vouchers_error;
    public function __construct(array $vouchers, User $user, array $vouchers_error)
    {
        $this->vouchers = $vouchers;
        $this->user = $user;
        $this->vouchers_error = $vouchers_error;
    }

    public function build(): self
    {
        return $this->view('emails.comprobante')
            ->with(
                [
                    'comprobantes' => $this->vouchers,
                    'user' => $this->user,
                    'vouchers_error' => $this->vouchers_error
                ]
            );
    }
}
