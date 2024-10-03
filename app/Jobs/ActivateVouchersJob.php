<?php

namespace App\Jobs;

use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ActivateVouchersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $vouchers = Voucher::where('starts_at', Carbon::now()->format('Y-m-d'))
                           ->where('status', 'inactive')
                           ->get();
    
        if ($vouchers->isEmpty()) {
            Log::info('No vouchers to activate');
            return;
        }
    
        foreach ($vouchers as $voucher) {
            $voucher->status = 'active';
            $voucher->save();
        }
            Log::info('Vouchers activated');
    }    
}
