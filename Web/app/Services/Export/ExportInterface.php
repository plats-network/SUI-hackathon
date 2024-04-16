<?php

namespace App\Services\Export;

interface ExportInterface
{
    public function download(string $fileName);
}
