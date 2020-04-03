<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\RecordExport;
use Illuminate\Support\Carbon;
use \Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\URL;

class ExportController extends Controller
{
    public function records(Request $request)
    {
        $request->validate([
            'start' => 'date',
            'end' => 'date',
            'type' => 'int'
        ]);

        $dateRange = [];
        $appendName = ' ';

        if ($request->start && $request->end) {
            $appendName .= $request->start.' to '.$request->end;
            $dateRange[0] = Carbon::parse($request->start)->format('Y-m-d').' 00:00:00';
            $dateRange[1] = Carbon::parse($request->end)->format('Y-m-d').' 23:59:59';
        }

        $filename = 'VAWC Record'.$appendName;

        return $this->resolveDownload($request->type, [
            'dateRange' => $dateRange,
            'filename' => $filename
        ]);
    }

    private function resolveDownload($type, $options)
    {
        if ($type == 2) {
            return (new RecordExport($options['dateRange']))->download(
                $options['filename'].'.csv',
                Excel::CSV,
                ['Content-Type' => 'text/csv']
            );
        } elseif ($type == 3) {
            return (new RecordExport($options['dateRange']))->download(
                $options['filename'].'.tsv',
                Excel::TSV
            );
        } elseif ($type == 4) {
            return (new RecordExport($options['dateRange']))->download(
                $options['filename'].'.ods',
                Excel::ODS
            );
        } elseif ($type == 5) {
            return (new RecordExport($options['dateRange']))->download(
                $options['filename'].'.xls',
                Excel::XLS
            );
        } elseif ($type == 6) {
            return (new RecordExport($options['dateRange']))->download(
                $options['filename'].'.html',
                Excel::HTML
            );
        } else {
            return (new RecordExport($options['dateRange']))->download(
                $options['filename'].'.xlsx',
                Excel::XLSX
            );
        }
    }
}
