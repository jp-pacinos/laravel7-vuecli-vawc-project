<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RecordExport implements WithMultipleSheets
{
    use Exportable;

    protected $dateRange;

    public function __construct($range)
    {
        $this->dateRange = $range;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new \App\Exports\Sheets\RecordSheet($this->dateRange);
        $sheets[] = new \App\Exports\Sheets\OtherInvolvedSheet($this->dateRange);

        return $sheets;
    }
}
