<?php

namespace App\Services\Postcodes;

use App\Imports\PostcodesImport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;

class PostcodeService
{

    /**
     * @var string
     */
    protected string $url = "https://parlvid.mysociety.org/os/ONSPD/2022-11.zip";

    /**
     * @var string
     */
    protected string $zip_file = "postcodes.zip";

    /**
     * @var string
     */
    protected string $extracted_folder = "extracted";

    /**
     * @var string
     */
    protected string $csv_folder = "extracted/Data/multi_csv";

    /**
     * @param \ZipArchive $zip
     */
    public function __construct(protected \ZipArchive $zip) {}

    /**
     * @return void
     */
    public function downloadPostcodeFile(): void {
        $contents = Http::get('https://parlvid.mysociety.org/os/ONSPD/2022-11.zip')->body();

        if ($contents) {
            Storage::put($this->zip_file, $contents);
        }
    }

    /**
     * @return void
     */
    public function extractPostcodeZip(): void {
        if ($this->zip->open(Storage::path($this->zip_file)) === TRUE) {
            $this->zip->extractTo(Storage::path($this->extracted_folder));
        }
    }

    /**
     * @param $path
     * @return void
     */
    public function importPostcodes($path) {
        (new PostcodesImport)->import($path, null, Excel::CSV);
    }
}
