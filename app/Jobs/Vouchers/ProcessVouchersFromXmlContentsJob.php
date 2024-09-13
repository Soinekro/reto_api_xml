<?php

namespace App\Jobs\Vouchers;

use App\Models\User;
use App\Services\VoucherService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class ProcessVouchersFromXmlContentsJob
 * @package App\Jobs\Vouchers
 */
class ProcessVouchersFromXmlContentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var VoucherService
     */
    public VoucherService $voucherService;
    /**
     * Create a new job instance.
     */
    public function __construct(
        private array $xmlContents,
        private User $user,
    ) {
        $this->voucherService = new VoucherService();
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle(): void
    {
        $this->voucherService->storeVouchersFromXmlContents($this->xmlContents, $this->user);
    }
}
