<?php

namespace App\Services\Export\Excel;

use App\Services\Export\ExportInterface;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;
use ReflectionClass;

class ExportService implements ExportInterface
{
    protected ExportFactory $exportFactory;

    public function __construct(ExportFactory $exportFactory)
    {
        $this->exportFactory = $exportFactory;
    }

    public function download($fileName = '')
    {
        $exportable = $this->exportFactory->create();

        return Excel::download($exportable, $fileName ?? 'excel.xlsx');
    }
}
