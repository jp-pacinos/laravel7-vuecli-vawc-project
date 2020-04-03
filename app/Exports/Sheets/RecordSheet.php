<?php

namespace App\Exports\Sheets;

use App\VawcRecord;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class RecordSheet implements FromQuery, WithTitle, WithHeadings, ShouldAutoSize, WithMapping
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
        return VawcRecord::with(['involved' => function ($query) {
            $query
                ->wherePivot('position_status', '!=', 3)
                ->orderBy('position_status');
        }])
            ->when($this->dateRange, function ($query) {
                $query->whereBetween('created_at', $this->dateRange);
            });
    }

    public function headings(): array
    {
        return [
            'Record No.',
            'Filled Date',
            'Complainant Lastname',
            'Complainant Firstname',
            'Complainant Middlename',
            'Complainant Suffix',
            'Complainant Birthdate',
            'Complainant Sex',
            'Complainant Contact Number',
            'Respondent Lastname',
            'Respondent Firstname',
            'Respondent Middlename',
            'Respondent Suffix',
            'Respondent Birthdate',
            'Respondent Sex',
            'Respondent Contact Number',
            'Happened Date',
            'Statement Description',
            'Statement',
        ];
    }

    public function map($records): array
    {
        $respondent = $records->involved[0];
        $complainant = $records->involved[1];

        return [
            $records->id,
            $records->created_at,
            $respondent->lastname,
            $respondent->firstname,
            $respondent->middlename,
            $respondent->suffix,
            $respondent->birthdate,
            $respondent->sex == 1 ? 'Male' : 'Female',
            $respondent->contact_number,
            $complainant->lastname,
            $complainant->firstname,
            $complainant->middlename,
            $complainant->suffix,
            $complainant->birthdate,
            $complainant->sex == 1 ? 'Male' : 'Female',
            $complainant->contact_number,
            $records->datetime_happened,
            $records->title,
            $records->body
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Records';
    }
}
