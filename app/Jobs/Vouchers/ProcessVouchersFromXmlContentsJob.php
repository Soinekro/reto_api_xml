<?php

namespace App\Jobs\Vouchers;

use App\Models\User;
use App\Services\VoucherService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessVouchersFromXmlContentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public VoucherService $voucherService;
    public function __construct(
        private $xmlContent,
        private User $user,
    ) {
        // $this->xmlContent = $xmlContent;
        // $this->user = $user;
        $this->voucherService = new VoucherService();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->voucherService->storeVouchersFromXmlContents($this->xmlContent, $this->user);
    }
}
