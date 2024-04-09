<?php

namespace App\Services\Export\Excel;

use App\Exceptions\NotDetectionClass;
use ReflectionClass;

class ExportFactory
{
    private array $classList = [
        'detail-applicant' => 'App\Exports\DetailApplicant',
    ];

    private string $className = '';

    public function create()
    {
        $this->setClassName();
        return resolve($this->className);
    }

    public function setClassName()
    {
        $exportType = request()->input('export_type');
        $this->className = $this->classList[$exportType];

        throw_if(empty($this->className) || !class_exists($this->className), NotDetectionClass::class);

        return $this->className;
    }
}
