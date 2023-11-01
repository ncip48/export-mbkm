<?php

namespace App\Http\Controllers;

use App\Exports\LogbookExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class ExportController extends Controller
{
    private $token;
    private $refresh_token;

    private function loginApi($email, $password)
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
    "email": "' . $email . '",
    "password": "' . $password . '"
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

        $this->token = $response->data->access_token;
        return;
    }

    private function refreshToken()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.kampusmerdeka.kemdikbud.go.id/user/auth/refresh_token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
    "refresh_token": "' . $this->refresh_token . '"
    }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        //change to stdClass
        $response = json_decode($response);

        $this->token = $response->data->access_token;
        $this->refresh_token = $response->data->refresh_token;
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

        //if response has error then refresh token
        if (isset($response->error)) {
            $this->refreshToken();
            $this->fetchApi($method, $endpoint);
        }
        return $response->data;
    }

    private function getIdMagang()
    {
        $response = $this->fetchApi('GET', 'mbkm/mahasiswa/activities');

        //if response length > 0 then get the 1 index
        if (count($response) > 1) {
            $id_activity = $response[1]->id;
        } else {
            $id_activity = $response[0]->id;
        }

        return $id_activity;
    }

    public function index()
    {
        return View('home');
    }

    public function export(Request $request)
    {
        $minggu = $request->minggu;
        $email = $request->email;
        $password = $request->password;

        //explode minggu jika ada ,
        if (strpos($minggu, ',') !== false) {
            $minggu = explode(',', $minggu);
        }

        if ($request->token) {
            $this->token = $request->token;
        }

        if ($this->token == null) {
            $this->loginApi($email, $password);
        }

        $id_activity = $this->getIdMagang();

        $reports = $this->fetchApi('GET', 'magang/report/allweeks/' . $id_activity);

        $location = $this->fetchApi('GET', 'magang/report/summary/' . $id_activity);
        $location = $location->activity->brand_name;

        $profile = $this->fetchApi('GET', 'mbkm/mahasiswa/profile');

        $newReports = [];
        setlocale(LC_ALL, 'IND');
        //set locale for vps
        setlocale(LC_TIME, 'id_ID.utf8');
        Carbon::setLocale('id');

        foreach ($reports as $report) {
            foreach ($report->daily_report as $d) {
                //set locale carbon to INDONESIA
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

        //if minggu is set and more than 1 separated by comma then get data
        if (is_array($minggu)) {
            $newReports = [];
            foreach ($minggu as $m) {
                foreach ($reports as $report) {
                    if ($report->minggu == $m) {
                        $newReports[] = $report;
                    }
                }
            }
            $reports = $newReports;
        } else if (!is_array($minggu)) {
            if (!$minggu) {
                $reports = $reports;
            } else {
                $newReports = [];
                foreach ($reports as $report) {
                    if ($report->minggu == $minggu) {
                        $newReports[] = $report;
                    }
                }
                $reports = $newReports;
            }
        }

        $signature = new stdClass();
        $signature->paraf_mahasiswa = $request->file('paraf_mahasiswa') ? "data:image/png;base64," . base64_encode(file_get_contents($request->file('paraf_mahasiswa'))) : '';
        $signature->paraf_pembimbing = $request->file('paraf_pembimbing') ? "data:image/png;base64," . base64_encode(file_get_contents($request->file('paraf_pembimbing'))) : '';
        $signature->paraf_dosen = $request->file('paraf_dosen') ? "data:image/png;base64," . base64_encode(file_get_contents($request->file('paraf_dosen'))) : '';

        if (is_array($minggu)) {
            //jika minggu lebih dari 2 maka dibuat 7-8 misal
            if (count($minggu) > 2) {
                $minggu = min($minggu) . ' - ' . max($minggu);
            } else {
                $minggu = implode(', ', $minggu);
            }
        } else {
            $minggu = $minggu;
        }

        //render dompdf
        $pdf = PDF::loadView('report', compact('reports', 'location', 'minggu', 'profile', 'signature'));

        $random = rand(1, 1000000);
        return $pdf->stream('logbook' . $random . '.pdf');

        // return view('report', compact('reports', 'location', 'minggu', 'profile'));
    }

    public function ExportExcel(Request $request)
    {
        $minggu = $request->minggu;
        $email = $request->email;
        $password = $request->password;

        //explode minggu jika ada ,
        if (strpos($minggu, ',') !== false) {
            $minggu = explode(',', $minggu);
        }

        if ($request->token) {
            $this->token = $request->token;
        }

        if ($this->token == null) {
            $this->loginApi($email, $password);
        }

        $id_activity = $this->getIdMagang();

        $reports = $this->fetchApi('GET', 'magang/report/allweeks/' . $id_activity);

        $location = $this->fetchApi('GET', 'magang/report/summary/' . $id_activity);
        $location = $location->activity->brand_name;

        $profile = $this->fetchApi('GET', 'mbkm/mahasiswa/profile');

        $newReports = [];
        setlocale(LC_ALL, 'IND');
        //set locale for vps
        setlocale(LC_TIME, 'id_ID.utf8');
        Carbon::setLocale('id');

        foreach ($reports as $report) {
            foreach ($report->daily_report as $d) {
                //set locale carbon to INDONESIA
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

        //if minggu is set and more than 1 separated by comma then get data
        if (is_array($minggu)) {
            $newReports = [];
            foreach ($minggu as $m) {
                foreach ($reports as $report) {
                    if ($report->minggu == $m) {
                        $newReports[] = $report;
                    }
                }
            }
            $reports = $newReports;
        } else if (!is_array($minggu)) {
            if (!$minggu) {
                $reports = $reports;
            } else {
                $newReports = [];
                foreach ($reports as $report) {
                    if ($report->minggu == $minggu) {
                        $newReports[] = $report;
                    }
                }
                $reports = $newReports;
            }
        }

        $signature = new stdClass();
        $signature->paraf_mahasiswa = $request->file('paraf_mahasiswa') ? "data:image/png;base64," . base64_encode(file_get_contents($request->file('paraf_mahasiswa'))) : '';
        $signature->paraf_pembimbing = $request->file('paraf_pembimbing') ? "data:image/png;base64," . base64_encode(file_get_contents($request->file('paraf_pembimbing'))) : '';
        $signature->paraf_dosen = $request->file('paraf_dosen') ? "data:image/png;base64," . base64_encode(file_get_contents($request->file('paraf_dosen'))) : '';

        if (is_array($minggu)) {
            //jika minggu lebih dari 2 maka dibuat 7-8 misal
            if (count($minggu) > 2) {
                $minggu = min($minggu) . ' - ' . max($minggu);
            } else {
                $minggu = implode(', ', $minggu);
            }
        } else {
            $minggu = $minggu;
        }

        $random_name = rand(1000, 9999);

        Excel::store(new LogbookExport(
            $reports,
            $location,
            $minggu,
            $profile,
            $signature
        ), 'public/excel/' . $random_name . '.xlsx');
        
        //redirect to file
        return redirect()->to('storage/excel/' . $random_name . '.xlsx');

    }

}
