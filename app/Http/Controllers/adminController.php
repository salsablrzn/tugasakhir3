<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
use App\pegawai;
use App\jabatan;
use App\hari_kerja;
use App\gaji_pokok;
use App\tunjangan;
use App\nilai;
use App\golongan;
use App\detail_golongan;

class adminController extends Controller
{
    //--- Laporan Penggajian ---//

	public function laporanpenggajian(){
		return view('konten/admin/laporanpenggajian/laporanpenggajian');
	}

    public function gajiutama(){
        return view('konten/admin/laporanpenggajian/gajiutama');
    }

     public function tunjanganmamin(){
        return view('konten/admin/laporanpenggajian/tunjanganmamin');
    }

    //--- Rekap Presensi ---//

	public function rekappresensi(){
        return view('konten/admin/rekappresensi/rekappresensi');
  //       $rekappresensi = rekappresensi::all();
  //       $hadir = presensi::where('daftar_hadir', 'hadir')->where('id_pegawai', 'idnyasipegawai')->count();
		// return view('konten/admin/rekappresensi/rekappresensi',['rekappresensi'=>$rekappresensi]);
	}

    public function storerekappresensi(Request $request)
    {
            rekappresensi::create([
            'id_rekappresensi'  => $request->id_rekappresensi,
            'hadir'             => $request->hadir,
            'sakit'             => $request->sakit,
            'izin'              => $request->izin,            
            'foto'              => base64_encode($request->foto)
        ]);
        return redirect('adminrekappresensi');
    }

    //--- Data Akun ---//

	public function dataakun(){
        $dataakun = dataakun::all();
		return view('konten/admin/dataakun/dataakun',['dataakun'=>$dataakun]);
	}

	public function createdataakun()
    {
        return view('konten/admin/dataakun/create');
        
    }

    public function storedataakun(Request $request)
    {
        dataakun::create([
            'id_dataakun'		    => $request->id_dataakaun,
            'nama_dataakun'	        => $request->nama_dataakun,
            'username_dataakun'		=> $request->username_dataakun,
            'password_dataakun'		=> $request->password_dataakun,
            // 'status_dataakun'		=> $request->status_dataakun
        ]);
        return redirect('admindataakun');
        
    }
    
    public function editdataakun($id)
    {
        $dataakun=dataakun::where('id_dataakun',$id)->get();
        
       return view("master/User/edit",['dataakun'=>$dataakun]);
        
    }

   
    public function updatedataakun(Request $request)
    {
         $dataakun=dataakun::where('id_dataakun',$request->id_dataakun)
            ->update([ 
            'id_dataakaun'          => $request->id_dataakaun,
            'nama_dataakun'         => $request->nama_dataakun,
            'username_dataakun'     => $request->username_dataakun,
            'password_dataakun'     => $request->password_dataakun,
            'status_dataakun'       => $request->status_dataakun
            ]);  
        
            return redirect('admindataakun');
    }

        public function destroydataakun($id)
    {
         DB::table('dataakun')
            ->where('id_dataakun', $id)
            ->delete();     
        return redirect('admindataakun');
        //
    }

    //--- Data Pegawai ---//
     

	public function datapegawai(){
        // $PEGAWAI = DB::table('pegawai')->get;
        // $data = Carbon::now()->format('d-m-Y h:i:s');
        // dd($data);
        $PEGAWAI=pegawai::join('jabatan','pegawai.ID_JABATAN','=','jabatan.ID_JABATAN')->get(); 
        
		return view('konten/admin/datapegawai/datapegawai',['PEGAWAI'=>$PEGAWAI]);
	}

    public function dataguru(){
        return view('konten/admin/datapegawai/dataguru');
    }

    public function datatu(){
        return view('konten/admin/datapegawai/datatu');
    }

    public function createdatapegawai(){

        
        $jabatan = DB::table('jabatan')->pluck("NAMA_JABATAN","ID_JABATAN");
        $detail = DB::table('detail_golongan')->pluck('NAMA_DETAIL_GOLONGAN','ID_DETAIL_GOLONGAN');

        return view('konten/admin/datapegawai/createdatapegawai',compact('jabatan','detail'));
    }


    public function inputPenggajian(){
        $date= date('Ymd');
    	$d = $date.'%';
		$id = (DB::table('penggajian')->where('ID_PENGGAJIAN','like', $d)->count('ID_PENGGAJIAN'))+1;
	    $ID_PENGGAJIAN = $date."P".str_pad($id,3,"0",STR_PAD_LEFT);
        penggajian::create([
            'ID_PENGGAJIAN'=>$ID_PENGGAJIAN,

        ]);
    }

    public function getgolongan()
    {   
        $id_detail = $_POST['id'];
        $gaji = DB::table('gaji_utama')->where("ID_DETAIL_GOLONGAN",$id_detail)->pluck('NOMINAL_GAJI_UTAMA','ID_GAJU_UTAMA');
        return  response()->json($gaji);
    }


    // pengelolaan penggajian

    public function penggajian(){
        $penggajian  = DB::table('penggajian')
                        ->join('presensi as p','penggajian.ID_DAFTAR_HADIR','p.ID_DAFTAR_HADIR')
                        ->join('pegawai as peg','p.ID_PEGAWAI','peg.ID_PEGAWAI')
                        ->join('detail_golongan as dg','peg.ID_DETAIL_GOLONGAN','dg.ID_DETAIL_GOLONGAN')
                        ->join('gaji_utama as gu','dg.ID_DETAIL_GOLONGAN','gu.ID_DETAIL_GOLONGAN')
                        ->join('golongan as g','dg.ID_GOLONGAN','g.ID_GOLONGAN')
                        ->join('tujangan as t','g.ID_GOLONGAN','t.ID_GOLONGAN')
                        ->selectRaw('peg.NIP, peg.NAMA_PEGAWAI, dg.NAMA_DETAIL_GOLONGAN, gu.NOMINAL_GAJI_UTAMA,t.POTONGAN_TUNJANGAN, penggajian.TOTAL_TUNJANGAN_PENGGAJIAN, penggajian.TOTAL_GAJI')
                        ->groupBy('penggajian.ID_PENGGAJIAN')
                        ->get();
        
        $tunjangan = DB::table('presensi')
            ->join('pegawai as p','presensi.ID_PEGAWAI','p.ID_PEGAWAI')
            ->selectRaw('SUM(presensi.TOTAL_TUNJANGAN) as TOTAL_TUNJANGAN')
            ->get();

        return view('konten/admin/penggajian/index_penggajian')->with(compact('penggajian','tunjangan'));
    }

    public function createpenggajian(){

        // $detail = DB::table('detail_golongan')->pluck('NAMA_DETAIL_GOLONGAN','ID_DETAIL_GOLONGAN');
        // $pegawai = DB::table('pegawai')->pluck('NAMA_PEGAWAI','ID_PEGAWAI');
        // $golpegawai = DB::table('pegawai')
        //                 ->join('detail_golongan as dg','dg.ID_DETAIL_GOLONGAN','pegawai.ID_DETAIL_GOLONGAN')
        //                 ->get();
        $kehadiran  = DB::table('presensi')
                        ->join('pegawai as p','presensi.ID_PEGAWAI','p.ID_PEGAWAI')
                        ->groupBy('p.ID_PEGAWAI')
                        ->get();


        return view('konten/admin/penggajian/create_penggajian')->with(compact('kehadiran'));
    }

    public function gaji(){
        $id_kehadiran = $_POST['id'];
        // $data = DB::table('pegawai')
        //             ->join('detail_golongan as dg','pegawai.ID_DETAIL_GOLONGAN','dg.ID_DETAIL_GOLONGAN')
        //             ->join('golongan as g','dg.ID_GOLONGAN','g.ID_GOLONGAN')
        //             ->join('gaji_utama as gu','dg.ID_DETAIL_GOLONGAN','gu.ID_DETAIL_GOLONGAN')
        //             ->join('tujangan as t','dg.ID_GOLONGAN','t.ID_GOLONGAN')
        //             ->where('ID_PEGAWAI','=',$id_pegawai)
        //             ->first();
        //id kehadiran

        $data = DB::table('presensi')
                    ->join('pegawai as p','presensi.ID_PEGAWAI','p.ID_PEGAWAI')
                    ->join('detail_golongan as dg','p.ID_DETAIL_GOLONGAN','dg.ID_DETAIL_GOLONGAN')
                    ->join('golongan as g','dg.ID_GOLONGAN','g.ID_GOLONGAN')
                    ->join('gaji_utama as gu','dg.ID_DETAIL_GOLONGAN','gu.ID_DETAIL_GOLONGAN')
                    ->join('tujangan as t','dg.ID_GOLONGAN','t.ID_GOLONGAN')
                    ->where('ID_DAFTAR_HADIR','=',$id_kehadiran)
                    ->first();

        // $data = DB::table('pegawai')
        // ->join('detail_golongan as dg','pegawai.ID_DETAIL_GOLONGAN','dg.ID_DETAIL_GOLONGAN')
        // ->join('golongan as g','dg.ID_GOLONGAN','g.ID_GOLONGAN')
        // ->join('gaji_utama as gu','dg.ID_DETAIL_GOLONGAN','gu.ID_DETAIL_GOLONGAN')
        // ->join('presensi as p','pegawai.ID_PEGAWAI','p.ID_PEGAWAI')
        // ->join('tujangan as t','dg.ID_GOLONGAN','t.ID_GOLONGAN')
        // ->selectRaw('pegawai.NAMA_PEGAWAI, pegawai.TGL_MASUK_KERJA, dg.NAMA_DETAIL_GOLONGAN, gu.NOMINAL_GAJI_UTAMA, SUM(TOTAL_TUNJANGAN) as TUNJANGAN, t.POTONGAN_TUNJANGAN')
        // ->first();

        $now = Carbon::now();
        $lamakerja = date_diff(date_create($data->TGL_MASUK_KERJA),date_create($now));
        $lamakerja=$lamakerja->y;

        $tunjangan = DB::table('presensi')
                        ->join('pegawai as p','presensi.ID_PEGAWAI','p.ID_PEGAWAI')
                        ->selectRaw('SUM(presensi.TOTAL_TUNJANGAN) as tunjangan')
                        ->get();

        return response()->json([$data,$lamakerja,$tunjangan]);
        
    }

    //penggajian non pns

    public function createpenggajianNON(){

        // $detail = DB::table('detail_golongan')->pluck('NAMA_DETAIL_GOLONGAN','ID_DETAIL_GOLONGAN');
        $pegawai = DB::table('pegawai')->get();
        $golpegawai = DB::table('pegawai')
                        ->join('detail_golongan as dg','dg.ID_DETAIL_GOLONGAN','pegawai.ID_DETAIL_GOLONGAN')
                        ->get();
        return view('konten/admin/penggajian/create_penggajianNonPNS',compact('pegawai','golpegawai'));
    }

    

    public function storegaji(Request $request){
        DB::table('penggajian')
            ->join('presensi as p','penggajian.ID_DAFTAR_HADIR','p.ID_DAFTAR_HADIR')
            ->join('pegawai as peg','p.ID_PEGAWAI','peg.ID_PEGAWAI')
            ->insert([
            'ID_DAFTAR_HADIR'               => $request->id_kehadiran,
            'TOTAL_TUNJANGAN_PENGGAJIAN'    => $request->total_tunjangan,
            'TOTAL_GAJI'                    => $request->total_gaji,
        ]);

        return redirect('penggajian')->with('success','success');
    }

    public function createdataguru(){
        return view('konten/admin/datapegawai/createdataguru');
    }

    public function createdatatu(){
        return view('konten/admin/datapegawai/createdatatu');
    }

    public function storedatapegawai(Request $request)
    {
            pegawai::create([
            'ID_PEGAWAI'            => $request->ID_PEGAWAI,
            'ID_JABATAN'            => $request->ID_JABATAN,
            'NIP'                   => $request->NIP,
            'NAMA_PEGAWAI'          => $request->NAMA_PEGAWAI,
            'GAJI_POKOK'            => $request->GAJI_POKOK,
            'TUNJANGAN'             => $request->TUNJANGAN,
            'TIPE_AKUN'             => $request->TIPE_AKUN,
            'USERNAME'              => $request->USERNAME,
            'PASSWORD'              => bcrypt($request->PASSWORD),
            'JENIS_KELAMIN'         => $request->JENIS_KELAMIN,
            'STATUS_KEPEGAWAIAN'    => $request->STATUS_KEPEGAWAIAN,
            'ALAMAT'                => $request->ALAMAT,
            'TELEPON'               => $request->TELEPON,            
            'TANGGAL_LAHIR'         => $request->TANGGAL_LAHIR,
            'STATUS'                => $request->STATUS
        ]);
            $id = DB::table('pegawai')->max('ID_PEGAWAI');
            $data = Carbon::now()->format('d-m-Y h:i:s');
            DB::table('history_pegawai')->insert([
            'ID_PEGAWAI'             => $id,  
            'ID_JABATAN'             => $request->ID_JABATAN,
            'NIP'                    => $request->NIP,
            'NAMA_PEGAWAI'           => $request->NAMA_PEGAWAI,
            'GAJI_POKOK'             => $request->GAJI_POKOK,
            'TUNJANGAN'              => $request->TUNJANGAN,
            'TIPE_AKUN'              => $request->TIPE_AKUN,
            'USERNAME'               => $request->USERNAME,
            'PASSWORD'               => bcrypt($request->PASSWORD),
            'JENIS_KELAMIN'          => $request->JENIS_KELAMIN,
            'STATUS_KEPEGAWAIAN'     => $request->STATUS_KEPEGAWAIAN,
            'ALAMAT'                 => $request->ALAMAT,
            'TELEPON'                => $request->TELEPON,            
            'TANGGAL_LAHIR'          => $request->TANGGAL_LAHIR,
            'STATUS'                 => $request->STATUS,
            'STATUS_HISTORY_PEGAWAI' => "insert",
            'TANGGAL_HISTORY_PEGAWAI'=> $data
            ]);  

        return redirect('admindatapegawai');
        
    }

    public function editdatapegawai($id)
    {
        $PEGAWAI=pegawai::where('ID_PEGAWAI',$id)->get();
        $JABATAN=DB::table('jabatan')->get();
        //
       return view('konten/admin/datapegawai/editdatapegawai',['PEGAWAI'=>$PEGAWAI, 'JABATAN'=>$JABATAN]);
        //
    }

    public function updatedatapegawai(Request $request)
    {
         
         DB::table('pegawai')->where('ID_PEGAWAI',$request->ID_PEGAWAI)->update([
            'ID_JABATAN'            => $request->ID_JABATAN,
            'NIP'                   => $request->NIP,
            'NAMA_PEGAWAI'          => $request->NAMA_PEGAWAI,
            'GAJI_POKOK'            => $request->GAJI_POKOK,
            'TUNJANGAN'             => $request->TUNJANGAN,
            'TIPE_AKUN'             => $request->TIPE_AKUN,
            'USERNAME'              => $request->USERNAME,
            'PASSWORD'              => bcrypt($request->PASSWORD),
            'JENIS_KELAMIN'         => $request->JENIS_KELAMIN,
            'STATUS_KEPEGAWAIAN'    => $request->STATUS_KEPEGAWAIAN,
            'ALAMAT'                => $request->ALAMAT,
            'TELEPON'               => $request->TELEPON,            
            'TANGGAL_LAHIR'         => $request->TANGGAL_LAHIR,
            'STATUS'                => $request->STATUS
            ]);  
            $data = Carbon::now()->format('d-m-Y h:i:s');
            DB::table('history_pegawai')->insert([
            'ID_PEGAWAI'             => $request->ID_PEGAWAI,  
            'ID_JABATAN'             => $request->ID_JABATAN,
            'NIP'                    => $request->NIP,
            'NAMA_PEGAWAI'           => $request->NAMA_PEGAWAI,
            'GAJI_POKOK'             => $request->GAJI_POKOK,
            'TUNJANGAN'              => $request->TUNJANGAN,
            'TIPE_AKUN'              => $request->TIPE_AKUN,
            'USERNAME'               => $request->USERNAME,
            'PASSWORD'               => bcrypt($request->PASSWORD),
            'JENIS_KELAMIN'          => $request->JENIS_KELAMIN,
            'STATUS_KEPEGAWAIAN'     => $request->STATUS_KEPEGAWAIAN,
            'ALAMAT'                 => $request->ALAMAT,
            'TELEPON'                => $request->TELEPON,            
            'TANGGAL_LAHIR'          => $request->TANGGAL_LAHIR,
            'STATUS'                 => $request->STATUS,
            'STATUS_HISTORY_PEGAWAI' => "edit",
            'TANGGAL_HISTORY_PEGAWAI'=> $data
            ]);  

            return redirect('admindatapegawai');
    }

    //--- Waktu Presensi ---//
     
    public function waktupresensi(){
        $id = session()->get('ID_HARI');
        $HARI_KERJA = hari_kerja::all();           
        return view('konten/admin/waktupresensi/waktupresensi',['HARI_KERJA'=>$HARI_KERJA]);
    }
   
    public function storewaktupresensi(Request $request)
    {
            pegawai::create([
            'ID_HARI'            => $request->ID_HARI,
            'NAMA_HARI'          => $request->NAMA_HARI,
            'STATUS_KERJA'       => $request->STATUS_KERJA,
            'MASUK_AWAL'         => $request->MASUK_AWAL,
            'MASUK_AKHIR'        => $request->MASUK_AKHIR,
            'KELUAR_AWAL'        => $request->KELUAR_AWAL,
            'KELUAR_AKHIR'       => $request->KELUAR_AKHIR,            
        ]);
        return redirect('waktupresensi');
        
    }

    public function editwaktupresensi($id)
    {        
        $HARI_KERJA=hari_kerja::where('ID_HARI',$id)->get();
        //
       return view('konten/admin/waktupresensi/editwaktupresensi',['HARI_KERJA'=>$HARI_KERJA]);
        //
    }

    public function updatewaktupresensi(Request $request)
    {
         
         DB::table('hari_kerja')->where('ID_HARI',$request->ID_HARI)->update([
            'ID_HARI'            => $request->ID_HARI,
            'NAMA_HARI'          => $request->NAMA_HARI,
            'STATUS_KERJA'       => $request->STATUS_KERJA,
            'MASUK_AWAL'         => $request->MASUK_AWAL,
            'MASUK_AKHIR'        => $request->MASUK_AKHIR,
            'KELUAR_AWAL'        => $request->KELUAR_AWAL,
            'KELUAR_AKHIR'       => $request->KELUAR_AKHIR,    
            ]);  

            return redirect('waktupresensi');
    }

     public function history_pegawai(Request $request)
    {
        $history_pegawai= DB::table('history_pegawai')
        ->join('jabatan','jabatan.ID_JABATAN','=','history_pegawai.ID_JABATAN')
        ->get();
        return view('konten/admin/datapegawai/history_pegawai',compact('history_pegawai'));
    }

    //--- Golongan ---//
     
    public function golongan(){
        $id = session()->get('ID_GOLONGAN');
        $GOLONGAN = golongan::all();           
        return view('konten/admin/golongan/index',['GOLONGAN'=>$GOLONGAN]);
    }

    public function creategolongan(){
        return view('konten/admin/golongan/create');
    }
   
    public function storegolongan(Request $request)
    {
            golongan::create([
            'ID_GOLONGAN'            => $request->ID_GOLONGAN,
            'NAMA_GOLONGAN'          => $request->NAMA_GOLONGAN,   
        ]);
        return redirect('golongan');
        
    }

    public function editgolongan($id)
    {        
        $GOLONGAN=golongan::where('ID_GOLONGAN',$id)->get();
        //
       return view('konten/admin/golongan/edit',['GOLONGAN'=>$GOLONGAN]);
        //
    }

    public function updategolongan(Request $request)
    {
         
         DB::table('golongan')->where('ID_GOLONGAN',$request->ID_GOLONGAN)->update([
            'ID_GOLONGAN'            => $request->ID_GOLONGAN,
            'NAMA_GOLONGAN'          => $request->NAMA_GOLONGAN,   
            ]);  

            return redirect('golongan');
    }

    //--- Gaji Pokok ---//
     
    public function gajipokok(){
        $GAJI_POKOK = DB::table('gaji_utama')
                    ->join('detail_golongan as dg','gaji_utama.ID_DETAIL_GOLONGAN','dg.ID_DETAIL_GOLONGAN')
                    ->join('nilai','dg.ID_NILAI','=','nilai.ID_NILAI')
                    ->join('golongan','dg.ID_GOLONGAN','=','golongan.ID_GOLONGAN')->get();

        $id = session()->get('ID_GAJU_UTAMA');
        // $GAJI_POKOK = gaji_pokok::all();           
        return view('konten/admin/gajipokok/index',['GAJI_POKOK'=>$GAJI_POKOK]);
    }

    public function creategajipokok(){

        $detail = DB::table('detail_golongan')
                    ->join('golongan as g','detail_golongan.ID_GOLONGAN','g.ID_GOLONGAN')
                    ->join('nilai as n','detail_golongan.ID_NILAI','n.ID_NILAI')
                    ->select('detail_golongan.ID_DETAIL_GOLONGAN','n.NILAI','g.NAMA_GOLONGAN')
                    ->get();
        // $nilai = DB::table('nilai')->pluck("NILAI","ID_NILAI");
        // $golongan = DB::table('golongan')->pluck("NAMA_GOLONGAN","ID_GOLONGAN");
        return view('konten/admin/gajipokok/create')->with(compact('detail'));
    }
   
    public function storegajipokok(Request $request)
    {
            
            DB::table('gaji_utama')->insert([
            'ID_DETAIL_GOLONGAN'=>$request->ID_DETAIL_GOLONGAN,
            'NAMA_GAJI_UTAMA'   => $request->NAMA_GAJI_POKOK,
            'MASSA_KERJA'       => $request->MASSA_KERJA,
            'NOMINAL_GAJI_UTAMA'=> $request->NOMINAL_GAJI_POKOK,      
        ]);
        return redirect('gajipokok')->with('success','success');
        
    }

    public function editgajipokok($id)
    {        
        
        $detail = DB::table('detail_golongan')
                    ->join('golongan as g','detail_golongan.ID_GOLONGAN','g.ID_GOLONGAN')
                    ->join('nilai as n','detail_golongan.ID_NILAI','n.ID_NILAI')
                    ->select('detail_golongan.ID_DETAIL_GOLONGAN','n.NILAI','g.NAMA_GOLONGAN')
                    ->get();
        $GAJI_POKOK=DB::table('gaji_utama')->where('ID_GAJU_UTAMA',$id)->get();
        //
       return view('konten/admin/gajipokok/edit',['GAJI_POKOK'=>$GAJI_POKOK,'detail'=>$detail]);
        //
    }

    public function updategajipokok(Request $request)
    {
         
         DB::table('gaji_utama')->where('ID_GAJU_UTAMA',$request->ID_GAJI_POKOK)->update([
            'ID_DETAIL_GOLONGAN'    => $request->ID_DETAIL_GOLONGAN,
            'NAMA_GAJI_UTAMA'   => $request->NAMA_GAJI_POKOK,
            'MASSA_KERJA'       => $request->MASSA_KERJA,
            'NOMINAL_GAJI_UTAMA'=> $request->NOMINAL_GAJI_POKOK  
            ]);  

            return redirect('gajipokok')->with('success','success');
    }

    //--- Tunjangan ---//
     
    public function tunjangan(){
        $TUNJANGAN = DB::table('tujangan')
                   ->join('golongan','tujangan.ID_GOLONGAN','=','golongan.ID_GOLONGAN')->get();
        $id = session()->get('ID_TUNJANGAN');
        $TUNJANGAN = DB::table('tujangan')->get();           
        return view('konten/admin/tunjangan/index',['TUNJANGAN'=>$TUNJANGAN]);
    }

    public function createtunjangan(){
        $golongan = DB::table('golongan')->pluck("NAMA_GOLONGAN","ID_GOLONGAN");
        return view('konten/admin/tunjangan/create',compact('golongan'));
    }
   
    public function storetunjangan(Request $request)
    {
            $potongan = $request->potongan;
            $potongan2 = $potongan/100;

            DB::table('golongan')->get();
            DB::table('tujangan')->insert([
            'ID_TUNJANGAN'         => $request->ID_TUNJANGAN,
            'ID_GOLONGAN'          => $request->ID_GOLONGAN,
            'NAMA_TUNJANGAN'       => $request->NAMA_TUNJANGAN,
            'NOMINAL_TUNJANGAN'    => $request->NOMINAL_TUNJANGAN,
            'POTONGAN_TUNJANGAN'   => $potongan2,           
        ]);
        return redirect('tunjangan')->with('success','success');
        
    }

    public function edittunjangan($id)
    {        
        $GOLONGAN=golongan::where('ID_GOLONGAN',$id)->get();
        $TUNJANGAN=tunjangan::where('ID_TUNJANGAN',$id)->get();
        //
       return view('konten/admin/tunjangan/edit',['TUNJANGAN'=>$TUNJANGAN,'GOLONGAN'=>$GOLONGAN]);
        //
    }

    public function updatetunjangan(Request $request)
    {
        $potongan = $request->potongan;
        $potongan2 = $potongan/100;
       

         DB::table('tujangan')->where('ID_TUNJANGAN',$request->ID_TUNJANGAN)->update([
            'ID_TUNJANGAN'         => $request->ID_TUNJANGAN,
            'ID_GOLONGAN'          => $request->ID_GOLONGAN,
            'NAMA_TUNJANGAN'       => $request->NAMA_TUNJANGAN,
            'NOMINAL_TUNJANGAN'    => $request->NOMINAL_TUNJANGAN,
            'POTONGAN_TUNJANGAN'   => $potongan2,  
            ]);  

            return redirect('tunjangan');
    }

    //--- Nilai ---//
     
    public function nilai(){
        $id = session()->get('ID_NILAI');
        $NILAI = nilai::all();           
        return view('konten/admin/nilai/index',['NILAI'=>$NILAI]);
    }

    public function createnilai(){
        return view('konten/admin/nilai/create');
    }
   
    public function storenilai(Request $request)
    {  
            nilai::create([
            'ID_NILAI'       => $request->ID_NILAI,
            'NILAI'          => $request->NILAI,    
        ]);
        return redirect('nilai');
        
    }

    public function editnilai($id)
    {        
        $NILAI=nilai::where('ID_NILAI',$id)->get();
        //
       return view('konten/admin/nilai/edit',['NILAI'=>$NILAI]);
        //
    }

    public function updatenilai(Request $request)
    {
         
         DB::table('nilai')->where('ID_NILAI',$request->ID_NILAI)->update([
            'ID_NILAI'       => $request->ID_NILAI,
            'NILAI'          => $request->NILAI,  
            ]);  

            return redirect('nilai');
    }

    //--- Detail Golongan ---//
     
    public function detailgolongan(){
        $GOLONGAN = DB::table('golongan')->get();
        $NILAI= DB::table('nilai')->get();
        $DETAIL_GOLONGAN = DB::table('detail_golongan')
        ->join('golongan as g','detail_golongan.ID_GOLONGAN','g.ID_GOLONGAN')
        ->join('nilai as n','detail_golongan.ID_NILAI','n.ID_NILAI')
        ->get();
        // $id = session()->get('ID_HARI');
        // $DETAIL_GOLONGAN = detail_golongan::all();           
        return view('konten/admin/detail_golongan/index',['DETAIL_GOLONGAN'=>$DETAIL_GOLONGAN,'GOLONGAN'=>$GOLONGAN,'NILAI'=>$NILAI]);
    }

    public function createdetailgolongan(){
        $golongan = DB::table('golongan')->get();
        $nilai= DB::table('nilai')->get();
        return view('konten/admin/detail_golongan/create',['golongan'=>$golongan,'nilai'=>$nilai]);
    }
   
    public function storedetailgolongan(Request $request)
    {
            detail_golongan::create([
            'ID_DETAIL_GOLONGAN'  => $request->ID_DETAIL_GOLONGAN,   
            'NAMA_DETAIL_GOLONGAN'=> $request->NAMA_DETAIL_GOLONGAN,
            'ID_NILAI'            => $request->ID_NILAI,
            'ID_GOLONGAN'         => $request->ID_GOLONGAN,       
        ]);
        return redirect('detailgolongan');
        
    }

    public function editdetailgolongan($id)
    {   
        // $DETAIL_GOLONGAN=detail_golongan::where('ID_DETAIL_GOLONGAN',$id)->get();
        $GOLONGAN = DB::table('golongan')->get();
        $NILAI= DB::table('nilai')->get();
        $DETAIL_GOLONGAN = DB::table('detail_golongan')
        ->join('golongan as g','detail_golongan.ID_GOLONGAN','g.ID_GOLONGAN')
        ->join('nilai as n','detail_golongan.ID_NILAI','n.ID_NILAI')
        ->where('ID_DETAIL_GOLONGAN',$id)
        ->get();
        // $DETAIL_NILAI=detail_nilai::where('ID_HARI',$id)->get();
        //
       return view('konten/admin/detail_golongan/edit',['DETAIL_GOLONGAN'=>$DETAIL_GOLONGAN,'GOLONGAN'=>$GOLONGAN,'NILAI'=>$NILAI]);
        //
    }

    public function updatedetailgolongan(Request $request,$id)
    {
        // $GOLONGAN = DB::table('golongan');
        // $NILAI= DB::table('nilai');
        // $DETAIL_GOLONGAN = DB::table('detail_golongan')
        // ->join('golongan as g','detail_golongan.ID_GOLONGAN','g.ID_GOLONGAN')
        // ->join('nilai as n','detail_golongan.ID_NILAI','n.ID_NILAI')
        // ->get();
         DB::table('detail_golongan')->where('ID_DETAIL_GOLONGAN',$id)->update([
            'ID_DETAIL_GOLONGAN'  => $id,   
            'NAMA_DETAIL_GOLONGAN'=> $request->NAMA_DETAIL_GOLONGAN,
            'ID_NILAI'            => $request->ID_NILAI,
            'ID_GOLONGAN'         => $request->ID_GOLONGAN,       
            ]);  

            return redirect('/detailgolongan');
    }

}
