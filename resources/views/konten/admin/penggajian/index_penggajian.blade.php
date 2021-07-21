@extends('layout/index')
@section('konten')

<div id="content">
               <div class="panel box-shadow-none content-header">
                  <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">Data Penggajian</h3>
                        <p class="animated fadeInDown">
                          Table <span class="fa-angle-right fa"></span> Data Penggajian
                        </p>
                    </div>
                  </div>
              </div>
              <div class="col-md-12 top-20 padding-0">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-heading"><h3>Data Tables</h3>
                    </div>
                    <div class="panel-body">
                      <div class="responsive-table">
                       
                        @if (session()->get('TIPE_AKUN') == "ADMIN")
                          <a href="createpenggajian" class="btn btn-success d-none d-md-inline-block text-white" target="_blank">Add Penggajian PNS</a>
                          <a href="createpenggajianNONPNS" class="btn btn-success d-none d-md-inline-block text-white" target="_blank">Add Penggajian NON PNS</a>
                        @endif
                       
                      <table id="datatables-example" class="table table-striped table-bordered" width="100%" cellspacing="0">
                       <thead>
                                            <tr>
                                                <th class="border-top-0">Tanggal Penggajian</th>
                                                <th class="border-top-0">NIP</th>
                                                <th class="border-top-0">Nama Pegawai</th>
                                                <th class="border-top-0">Golongan</th>
                                                <th class="border-top-0">Gaji Pokok</th>
                                                <th class="border-top-0">Tunjangan</th>
                                                <th class="border-top-0">Potongan Tunjangan</th>
                                                <th class="border-top-0">Total Tunjangan</th>
                                                <th class="border-top-0">Total Penggajian</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            
                                           @foreach($penggajian as $p)
                                            <tr>
                                              <td>{{\Carbon\Carbon::parse($p->CREATE_AT)->translatedFormat('l, d F Y')}}</td>
                                              <td>{{$p->NIP}}</td>
                                              <td>{{$p->NAMA_PEGAWAI}}</td>
                                              <td>{{$p->NAMA_DETAIL_GOLONGAN}}</td>
                                              <td>{{$p->GAJI_POKOK}}</td>
                                              @foreach($tunjangan as $t)
                                              <td>{{$t->TOTAL_TUNJANGAN}}</td>
                                              @endforeach
                                              <td>{{$p->POTONGAN_TUNJANGAN}}</td>
                                              <td>{{$p->TOTAL_TUNJANGAN_PENGGAJIAN}}</td>
                                              <td>{{$p->TOTAL_GAJI}}</td>
                                            </tr>
                                           @endforeach

                                        </tbody>

                        </table>
                      </div>
                  </div>
                </div>
              </div>  
              </div>
            </div>

  @if (session('success'))
    <script>
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Data Penggajian Berhasil Disimpan',
            showConfirmButton: false,
            timer: 2000
        }); 
    </script>
  @endif
@endsection