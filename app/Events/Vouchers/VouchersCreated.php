<?php

namespace App\Events\Vouchers;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VouchersCreated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @param array $vouchers
     * @param User $user
     * @param array $vouchers_error
     */
    public function __construct(
        public readonly array $vouchers,
        public readonly User $user,
        public readonly array $vouchers_error
    ) {}
}
