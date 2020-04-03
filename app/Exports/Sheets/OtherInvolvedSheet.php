<?php

namespace App\Exports\Sheets;

use App\VawcRecord;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class OtherInvolvedSheet implements FromQuery, WithTitle, WithHeadings, ShouldAutoSize, WithMapping
{
    private $dateRange = [];

    public function __construct($range = [])
    {
        $this->dateRange = $range;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return VawcRecord::select([
            'vawc_records.id as record_id',
            'vawc_users.id as user_id',
            'lastname',
            'firstname',
            'middlename',
            'suffix',
            'birthdate',
            'sex',
            'contact_number',
            'position'
        ])
            ->join('vawc_user_records', 'vawc_records.id', '=', 'vawc_user_records.record_id')
            ->join('vawc_users', 'vawc_user_records.user_id', '=', 'vawc_users.id')
            ->where('position_status', 3)
            ->when($this->dateRange, function ($query) {
                $query->whereBetween('vawc_records.created_at', $this->dateRange);
            })
            ->orderBy('vawc_records.id');
    }

    public function headings(): array
    {
        return [
            'Record No.',
            'Lastname',
            'Firstname',
            'Middlename',
            'Suffix',
            'Birthdate',
            'Sex',
            'Contact Number',
            'Relation'
        ];
    }

    public function map($records): array
    {
        return [
            $records->record_id,
            $records->lastname,
            $records->firstname,
            $records->middlename,
            $records->suffix,
            $records->birthdate,
            $records->sex == 1 ? 'Male' : 'Female',
            $records->contact_number,
            $records->position
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Persons Involved';
    }
}
