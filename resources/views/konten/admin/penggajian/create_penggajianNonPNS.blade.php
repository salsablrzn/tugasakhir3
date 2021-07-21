@extends('layout/index')
@section('konten')


<div id="content">
                <div class="panel box-shadow-none content-header">
                  <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">Data Penggajian</h3>
                        <p class="animated fadeInDown">
                          Form <span class="fa-angle-right fa"></span> Data Penggajian
                        </p>
                    </div>
                  </div>
                </div>
                <div class="form-element">
                  <div class="col-md-12 padding-0">
                    <div class="col-md-12">
                      <div class="panel form-element-padding">
                        <div class="panel-heading">
                         <h4>Data Penggajian</h4>
                        </div>
                         <div class="panel-body" style="padding-bottom:30px;">
                          <div class="col-md-12">

                            <form class="form-horizontal form-material" action="storegajinon" method="post">
                                    <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                                    {{ @csrf_field() }}
                                    <div class="form-group">
                                        <label class="col-sm-12 mb-0">Pegawai</label>
                                        <div class="col-sm-12">
                                        <select class="form-control pl-0 form-control-line select-pegawai" name="pegawai">
                                            <option disabled selected style="padding: 10px">Select Pegawai</option>
                                            
                                            @foreach($kehadiran as $k)
                                            @if($k->STATUS_KEPEGAWAIAN==1)
                                            <option value="{{$k->ID_DAFTAR_HADIR}}">{{ $k->NAMA_PEGAWAI }}</option>
                                            @endif
                                            @endforeach
                                            </select>   
                                        </div>
                                      </div>

                                      <div class="form-group">
                                        <label for="example-nama" class="col-md-12">Golongan</label>
                                        <div class="col-md-12">
                                            <input type="text" id="golongan" name="golongan" class="form-control pl-0 form-control-line" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="example-nama" class="col-md-12">Lama Kerja</label>
                                        <div class="col-md-12">
                                            <input type="text" readonly id="lama_kerja" class="form-control pl-0 form-control-line" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="example-nama" class="col-md-12">Gaji Pokok</label>
                                        <div class="col-md-12">
                                            <input type="text" id="gaji_pokok" name="gaji_pokok" class="form-control pl-0 form-control-line" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="example-nama" class="col-md-12">Tunjangan</label>
                                        <div class="col-md-12">
                                            <input type="text" readonly id="tunjangan" class="form-control pl-0 form-control-line" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="example-nama" class="col-md-12">Potongan Tunjangan</label>
                                        <div class="col-md-12">
                                            <input type="text" readonly id="potongan_tunjangan" class="form-control pl-0 form-control-line" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="example-nama" class="col-md-12">Total Tunjangan</label>
                                        <div class="col-md-12">
                                            <input type="text" readonly id="total_tunjangan" name="total_tunjangan" class="form-control pl-0 form-control-line" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="example-nama" class="col-md-12">Total Gaji</label>
                                        <div class="col-md-12">
                                            <input type="text" readonly id="total_gaji" name="total_gaji" class="form-control pl-0 form-control-line" >
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12 d-flex">
                                            <center>
                                            <button class="btn btn-success mx-auto mx-md-0 text-white">Submit</button>
                                            </center>
                                        </div>
                                    </div>
                                </form>
                          </div>
                        </div>
                      </div>
                    </div>
              </div>
            </div>



@endsection
@section('script')
<script src="{{ asset('/assets/select-gaji-pokok.js') }}"></script>
<script src="{{ asset('/assets/select-pegawai-non.js') }}"></script>
@if (session('success'))
  <script>
      Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Data Penggajian Non PNS Berhasil Disimpan',
          showConfirmButton: false,
          timer: 2000
      }); 
  </script>
  @endif
@endsection