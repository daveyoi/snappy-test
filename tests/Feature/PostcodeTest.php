<?php

use App\Services\Postcodes\PostcodeService;
use Illuminate\Support\Facades\Storage;

it('can download a valid postcodes file', function () {

    ini_set('memory_limit', '512M');

    Storage::fake('local');

    $postcodeService = new PostcodeService(new ZipArchive());
    $postcodeService->downloadPostcodeFile();

    Storage::disk('local')->assertExists('postcodes.zip');
});
