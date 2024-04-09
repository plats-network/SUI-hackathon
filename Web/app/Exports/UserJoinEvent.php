<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserJoinEvent implements FromCollection,WithHeadings,WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }
    public function headings(): array
    {
        $heading = ['Name', 'Email', 'Phone', 'Status', 'Type'];
        return $heading;

    }

    public function map($data): array
    {
        if ($data->is_checkin == 1){
            $checkIn = 'Join';
        }else{
            $checkIn = 'Check In';
        }
        if ($data->type == 1){
            $type = 'Guest';
        }else{
            $type = 'User';
        }
        $map = [
            $data->name,
            $data->email,
            $data->phone,
            $checkIn,
            $type,
        ];
        return $map;
    }
}
