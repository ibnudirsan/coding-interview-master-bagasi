<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Voucher;
use Illuminate\Console\Command;
use App\Jobs\ActivateVouchersJob;
use Illuminate\Support\Facades\Log;

class ActivateVouchers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vouchers:activate';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate vouchers at their start time';

    /**
     * Execute the console command.
     */
    public function handle()
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
