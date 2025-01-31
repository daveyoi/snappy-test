<?php

namespace App\Console\Commands;

use App\Services\Postcodes\PostcodeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportPostcodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-postcodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(PostcodeService $postcodeService)
    {
        ini_set('memory_limit', '512M');

        $this->output->text('Downloading postcodes...');
        $postcodeService->downloadPostcodeFile();

        $this->output->text('Extracting postcodes...');
        $postcodeService->extractPostcodeZip();

        $this->output->title('Importing Postcodes');
        foreach(Storage::disk('local')->files('extracted/Data/multi_csv') as $file) {
            $postcodeService->importPostcodes(Storage::disk('local')->path($file));
        }
    }
}
