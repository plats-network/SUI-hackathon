<?php

namespace App\Exports;

use App\Models\CodeVoucher;
use Maatwebsite\Excel\Concerns\FromCollection;

class CodeExport implements FromCollection
{
    public function collection()
    {
        return CodeVoucher::select('code')->whereType(2)->get();
    }
}
