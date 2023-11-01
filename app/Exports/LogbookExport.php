<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LogbookExport implements FromView
{   
    public $reports;
    public $location;
    public $minggu;
    public $profile;
    public $signature;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($reports, $location, $minggu, $profile, $signature)
    {
        $this->reports = $reports;
        $this->location = $location;
        $this->minggu = $minggu;
        $this->profile = $profile;
        $this->signature = $signature;
    }

    public function view(): View {
        return view('export',[
            'reports' => $this->reports,
            'location' => $this->location,
            'minggu' => $this->minggu,
            'profile' => $this->profile,
            'signature' => $this->signature,
        ]);
    }
}
