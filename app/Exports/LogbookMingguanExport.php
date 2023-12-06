<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class LogbookMingguanExport implements FromView
{
    public $reports;
    public $location;
    public $minggu;
    public $profile;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($reports, $location, $minggu, $profile)
    {
        $this->reports = $reports;
        $this->location = $location;
        $this->minggu = $minggu;
        $this->profile = $profile;
    }

    public function view(): View
    {
        return view('export_mingguan', [
            'reports' => $this->reports,
            'location' => $this->location,
            'minggu' => $this->minggu,
            'profile' => $this->profile,
        ]);
    }
}
