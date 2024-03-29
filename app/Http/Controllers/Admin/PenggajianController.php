<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\MPenggajian;
use App\Models\MPresensi;
use App\pegawai;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenggajianController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (!$request->session()->has('login') && $request->session()->get('TIPE_AKUN') != 'ADMIN') {
                return redirect('/');
            }
            return $next($request);
        });
    }
    public function data($tipe)
    {
        $tdy = date('Y-m');
        $history = DB::table('penggajian')->selectRaw("DISTINCT(BULAN_GAJIAN) as bulan")->get();

        // $data = DB::table('penggajian')->whereRaw(" penggajian.BULAN_GAJIAN like '%$tdy%'")
        // ->join('presensi as p','penggajian.ID_DAFTAR_HADIR','p.ID_DAFTAR_HADIR')
        // ->join('pegawai as b', 'p.ID_PEGAWAI', 'b.ID_PEGAWAI')
        // ->selectRaw('b.NAMA_PEGAWAI, penggajian.*, b.NIP')
        // ->orderBy('penggajian.CREATE_AT', 'ASC')->get();

        $data = DB::table('penggajian')->whereRaw(" penggajian.BULAN_GAJIAN like '%$tdy%'")
                    ->join('presensi as p','penggajian.ID_DAFTAR_HADIR','p.ID_DAFTAR_HADIR')
                    ->join('pegawai as peg','p.ID_PEGAWAI','peg.ID_PEGAWAI')
                    ->join('detail_golongan as dg','peg.ID_DETAIL_GOLONGAN','dg.ID_DETAIL_GOLONGAN')
                    ->join('golongan as g','dg.ID_GOLONGAN','g.ID_GOLONGAN')
                    ->join('tujangan as t','g.ID_GOLONGAN','t.ID_GOLONGAN')
                    ->groupBy('penggajian.ID_PENGGAJIAN')
                    ->orderBy('penggajian.CREATE_AT', 'ASC')
                    ->get();


        // $penggajian  = DB::table('penggajian')
        //                 ->join('presensi as p','penggajian.ID_DAFTAR_HADIR','p.ID_DAFTAR_HADIR')
        //                 ->join('pegawai as peg','p.ID_PEGAWAI','peg.ID_PEGAWAI')
        //                 ->join('detail_golongan as dg','peg.ID_DETAIL_GOLONGAN','dg.ID_DETAIL_GOLONGAN')
        //                 ->join('gaji_utama as gu','dg.ID_DETAIL_GOLONGAN','gu.ID_DETAIL_GOLONGAN')
        //                 ->join('golongan as g','dg.ID_GOLONGAN','g.ID_GOLONGAN')
        //                 ->join('tujangan as t','g.ID_GOLONGAN','t.ID_GOLONGAN')
        //                 ->selectRaw('penggajian.CREATE_AT, peg.NIP, peg.NAMA_PEGAWAI, dg.NAMA_DETAIL_GOLONGAN, gu.NOMINAL_GAJI_UTAMA,t.POTONGAN_TUNJANGAN, penggajian.TOTAL_TUNJANGAN_PENGGAJIAN, penggajian.TOTAL_GAJI')
        //                 ->groupBy('penggajian.ID_PENGGAJIAN')
        //                 ->get();
        
        // $tunjangan = DB::table('presensi')
        //     ->join('pegawai as p','presensi.ID_PEGAWAI','p.ID_PEGAWAI')
        //     ->selectRaw('SUM(presensi.TOTAL_TUNJANGAN) as TOTAL_TUNJANGAN')
        //     ->groupBy(DB::raw("p.ID_PEGAWAI"))
        //     ->get();

        // return $data;
        $arrtipe = ['keseluruhan' => 'Gaji Keseluruhan', 'utama' => 'Gaji Utama', 'tunjangan' => 'Tunjangan'];
        $title = "Rekap " . $arrtipe[$tipe] . " Bulan Ini";
        return view('konten/admin/rekappenggajian/penggajian', compact('data', 'history', 'title', 'tipe', 'tdy'));
    }
    public function exportpdf($id,$tipe)
    {
        $data = DB::table('penggajian')->whereRaw(" penggajian.ID_PENGGAJIAN like '%$id%'")
                    ->join('presensi as p','penggajian.ID_DAFTAR_HADIR','p.ID_DAFTAR_HADIR')
                    ->join('pegawai as peg','p.ID_PEGAWAI','peg.ID_PEGAWAI')
                    ->join('detail_golongan as dg','peg.ID_DETAIL_GOLONGAN','dg.ID_DETAIL_GOLONGAN')
                    ->join('golongan as g','dg.ID_GOLONGAN','g.ID_GOLONGAN')
                    ->join('tujangan as t','g.ID_GOLONGAN','t.ID_GOLONGAN')
                    ->groupBy('penggajian.ID_PENGGAJIAN')
                    ->orderBy('penggajian.CREATE_AT', 'ASC')
                    ->get();

        $tunjangan = DB::table('presensi')
                        ->join('pegawai as p','presensi.ID_PEGAWAI','p.ID_PEGAWAI')
                        ->selectRaw('SUM(presensi.TOTAL_TUNJANGAN) as TOTAL_TUNJANGAN')
                        ->get();

        $arrtipe = ['keseluruhan' => 'Keseluruhan', 'utama' => 'Utama', 'tunjangan' => 'Tunjangan'];
        $title = "Gaji ".$arrtipe[$tipe] ." ".$data[0]->NAMA_PEGAWAI." Pada Bulan ".Helper::bulantahun($data[0]->BULAN_GAJIAN);
        $pdf = PDF::loadView('konten.admin.rekappenggajian.pdf', compact('data', 'tunjangan', 'title', 'tipe'))->setPaper('a4', 'landscape');
        return $pdf->download($title.'.pdf');
    }

    public function exportexcel($tipe, $tdy)
    {

        $data = DB::table('penggajian')->whereRaw(" penggajian.BULAN_GAJIAN like '%$tdy%'")
                    ->join('presensi as p','penggajian.ID_DAFTAR_HADIR','p.ID_DAFTAR_HADIR')
                    ->join('pegawai as peg','p.ID_PEGAWAI','peg.ID_PEGAWAI')
                    ->join('detail_golongan as dg','peg.ID_DETAIL_GOLONGAN','dg.ID_DETAIL_GOLONGAN')
                    ->join('golongan as g','dg.ID_GOLONGAN','g.ID_GOLONGAN')
                    ->join('tujangan as t','g.ID_GOLONGAN','t.ID_GOLONGAN')
                    ->groupBy('penggajian.ID_PENGGAJIAN')
                    ->orderBy('penggajian.CREATE_AT', 'ASC')
                    ->get();

        $tunjangan = DB::table('presensi')
                ->join('pegawai as p','presensi.ID_PEGAWAI','p.ID_PEGAWAI')
                ->selectRaw('SUM(presensi.TOTAL_TUNJANGAN) as TOTAL_TUNJANGAN')
                ->get();

        // return $data;
        $arrtipe = ['keseluruhan' => 'Gaji Keseluruhan', 'utama' => 'Gaji Utama', 'tunjangan' => 'Tunjangan'];
        $title = "Rekap " . $arrtipe[$tipe] . " Bulan"." ".Helper::bulantahun($tdy);
        return view('konten/admin/rekappenggajian/export', compact('data', 'title', 'tipe', 'tdy', 'tunjangan'));

    }

    public function buat()
    {
        // return password_hash("123456", PASSWORD_BCRYPT);
        $tdy = date('Y-m');

        $data = pegawai::where(['TIPE_AKUN' => 'PEGAWAI', 'STATUS' => 1])
            ->orderBy('NAMA_PEGAWAI', 'ASC')->get();
        // return $data;
        return view('konten/admin/rekappenggajian/formgaji', compact('data', 'tdy'));
    }

    public function savegaji(Request $request)
    {
        try {
            $id_pegawai = $request->id_pegawai;
            $tdy = date('Y-m');


            $data = MPresensi::whereRaw("presensi.ID_PEGAWAI='$id_pegawai' and presensi.TANGGAL like '%$tdy%'")
            ->selectRaw('SUM(presensi.TOTAL_TUNJANGAN) as TOTAL_TUNJANGAN,
                COUNT(IF(STATUS_MASUK = "Hadir",1,NULL)) as Hadirmasuk,
                COUNT(IF(STATUS_KELUAR = "Hadir",1,NULL)) as Hadirkeluar,
                COUNT(IF(STATUS_MASUK = "Sakit",1,NULL)) as Sakitmasuk,
                COUNT(IF(STATUS_KELUAR = "Sakit",1,NULL)) as Sakitkeluar,
                COUNT(IF(STATUS_MASUK = "Izin",1,NULL)) as Izinmasuk,
                COUNT(IF(STATUS_KELUAR = "Izin",1,NULL)) as Izinkeluar,
                COUNT(IF(STATUS_MASUK = "Telat",1,NULL)) as Telatmasuk,
                COUNT(IF(STATUS_KELUAR = "Telat",1,NULL)) as Telatkeluar
                ')
                ->groupBy(DB::raw("presensi.ID_PEGAWAI"))
                ->first();
            $pegawai = pegawai::where('ID_PEGAWAI', $id_pegawai)->first();
            $tunjangan = 0;
            if ($data) {
                $tunjangan = ($data->TOTAL_TUNJANGAN == null ? 0 : (int)$data->TOTAL_TUNJANGAN);
            }
            $potongan = $request->potongan;
            $potongan = ($potongan == "" || $potongan == 0 ? 0 : $potongan / 100 * $tunjangan);
            $totalgaji = (int)$pegawai->GAJI_POKOK + $tunjangan - $potongan;
            $kode = Str::random(8);
            $arr = [
                'ID_PENGGAJIAN' => $kode,
                'ID_PEGAWAI' => $id_pegawai,
                'BULAN_GAJIAN' => $tdy,
                'GAJI_POKOK' => $pegawai->GAJI_POKOK,
                'TUNJANGAN_MAMIN' => $tunjangan,
                'POTONGAN' => ($request->potongan == "" ? 0 : $request->potongan),
                'TOTAL_GAJI' => $totalgaji,
                'CREATE_AT' => date('Y-m-d H:i:s'),
            ];
            $cek = MPenggajian::where(['ID_PEGAWAI' => $id_pegawai,'BULAN_GAJIAN'=>$tdy])->first();
            if ($cek) {

                MPenggajian::where(['ID_PENGGAJIAN' => $cek->ID_PENGGAJIAN])->update($arr);
            }else{
                MPenggajian::create($arr);
            }
            return redirect('admin/penggajian/keseluruhan')->with('status', ['success', 'Informasi', 'Berhasil Menyimppan Data Penggajian']);

        } catch (\Throwable $th) {
            return $th;
            return redirect('admin/penggajian/buat')->with('status', ['danger', 'Informasi', 'Gagal Menyimppan Data Penggajian']);
            //throw $th;
        }
    }

    
}
