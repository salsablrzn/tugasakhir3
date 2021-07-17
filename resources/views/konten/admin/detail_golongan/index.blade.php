@extends('layout/index')
@section('konten')

<div id="content">
               <div class="panel box-shadow-none content-header">
                  <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">Data Detail Golongan</h3>
                        <p class="animated fadeInDown">
                          Table <span class="fa-angle-right fa"></span> Data Detail Golongan
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
                        <a href="createdetailgolongan" class="btn btn-success d-none d-md-inline-block text-white" target="_blank">Add Gaji Pokok</a>
                        @endif
                       
                      <table id="datatables-example" class="table table-striped table-bordered" width="100%" cellspacing="0">
                       <thead>
                                            <tr>
                                                <th class="border-top-0">ID DETAIL GOLONGAN</th>
                                                <th class="border-top-0">NAMA DETAIL GOLONGAN</th>
                                                <th class="border-top-0">NAMA GOLONGAN</th>
                                                <th class="border-top-0">NAMA NILAI</th>
                                                <th class="border-top-0">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            
                                            @foreach($DETAIL_GOLONGAN as $DG)
                                              <tr>
                                                <td>{{ $DG-> ID_DETAIL_GOLONGAN}}</td>
                                                <td>{{ $DG -> NAMA_DETAIL_GOLONGAN }}</td>
                                                <td>{{ $DG -> NAMA_GOLONGAN }}</td>
                                                <td>{{ $DG -> NILAI}}</td>
                                                @if (session()->get('TIPE_AKUN') == "ADMIN")                                         
                                                <td class="glyphicon glyphicon-pencil" style="padding: 5px"><a href="editdetailgolongan{{ $DG -> ID_DETAIL_GOLONGAN }}">Edit</td>
                                                @endif
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
          title: 'Data Karyawan Berhasil Disimpan',
          showConfirmButton: false,
          timer: 2000
      }); 
  </script>
  @endif
@endsection