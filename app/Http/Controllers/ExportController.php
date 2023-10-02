<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    private $token;

    private function loginApi()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.kampusmerdeka.kemdikbud.go.id/user/auth/login/mbkm',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
    "email": "herlycp@students.amikom.ac.id",
    "password": "Mbahcip123"
    }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
            CURLOPT_SSL_VERIFYPEER => false,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        //change to stdClass
        $response = json_decode($response);

        $this->token = $response->data->token;
        return;
    }

    private function fetchApi($method, $endpoint)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.kampusmerdeka.kemdikbud.go.id/' . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->token
            ),
            CURLOPT_SSL_VERIFYPEER => false,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        //change to stdClass
        $response = json_decode($response);

        // return $response->data;

        return $response->data;
    }

    private function getIdMagang()
    {
        $response = $this->fetchApi('GET', 'mbkm/mahasiswa/activities');

        return $response[0]->id;
    }

    public function index(Request $request)
    {
        $minggu = $request->minggu;
        $token = $request->token;
        // $this->loginApi();
        $this->token = $token ?? "eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiYTgxM2RhZDctYzYwYi00Mzc2LWJhNGQtZTYwNWVkNGQ4ZGY0IiwicnQiOmZhbHNlLCJleHAiOjE2OTYyMTU5ODUsImlhdCI6MTY5NjIxNDE4NSwiaXNzIjoiV2FydGVrLUlEIiwibmFtZSI6Ikhlcmx5IENoYWh5YSBQdXRyYSIsInJvbGVzIjpbIm1haGFzaXN3YSJdLCJwdF9jb2RlIjoiMDUxMDI0IiwiaGFzX2FkbWluX3JvbGUiOmZhbHNlLCJtaXRyYV9pZCI6IjAwMDAwMDAwLTAwMDAtMDAwMC0wMDAwLTAwMDAwMDAwMDAwMCIsImVtYWlsIjoiIiwic2Vrb2xhaF9ucHNuIjoiIn0.LxO35xmdDH3hQYQOO-H65SNkLvJk0OfuIUgQMedYBMprU2zwlOdJ_XXZBElizG8qie4wHucNJ33kUfTDF-kqOA";

        $id_activity = $this->getIdMagang();

        $reports = $this->fetchApi('GET', 'magang/report/allweeks/' . $id_activity);

        $location = $this->fetchApi('GET', 'magang/report/summary/' . $id_activity);
        $location = $location->activity->brand_name;

        $profile = $this->fetchApi('GET', 'mbkm/mahasiswa/profile');

        $newReports = [];
        setlocale(LC_ALL, 'IND');

        foreach ($reports as $report) {
            foreach ($report->daily_report as $d) {
                $d->report_date = Carbon::parse($d->report_date)->formatLocalized('%A, %d %B %Y');
                $newReports[] = [
                    'report' => $d->report,
                    'minggu' => $report->counter,
                    'tanggal' => $d->report_date,
                ];
            }
        }
        //sort by minggu
        usort($newReports, function ($a, $b) {
            return $a['minggu'] <=> $b['minggu'];
        });

        $reports = json_encode($newReports);
        $reports = json_decode($reports);



        //if minggu is set
        if ($minggu) {
            $reports = array_filter($reports, function ($report) use ($minggu) {
                return $report->minggu == $minggu;
            });
        }

        //render dompdf
        $pdf = PDF::loadView('report', compact('reports', 'location', 'minggu', 'profile'));

        return $pdf->stream();

        // return view('report', compact('reports', 'location', 'minggu', 'profile'));
    }
}
