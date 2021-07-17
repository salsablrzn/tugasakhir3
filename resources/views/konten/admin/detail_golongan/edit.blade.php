@extends('layout/index')
@section('konten')

<div id="content">
                <div class="panel box-shadow-none content-header">
                  <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">Data Detail Golongan</h3>
                        <p class="animated fadeInDown">
                          Edit <span class="fa-angle-right fa"></span> Data Detail Golongan
                        </p>
                    </div>
                  </div>
                </div>
                <div class="form-element">
                  <div class="col-md-12 padding-0">
                    <div class="col-md-12">
                      <div class="panel form-element-padding">
                        <div class="panel-heading">
                         <h4>Data Detail Golongan</h4>
                        </div>
                         <div class="panel-body" style="padding-bottom:30px;">
                          <div class="col-md-12">
                            
                          @foreach ($DETAIL_GOLONGAN as $DG)
                            <form class="form-horizontal form-material" action="storegajipokok" method="post">
                                    <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                                    {{ @csrf_field() }}
                                    <div class="form-group">
                                        <label class="col-sm-12 mb-0">Golongan</label>
                                        <div class="col-sm-12">
                                        <select class="form-control pl-0 form-control-line" name="ID_DETAIL_GOLONGAN">
                                            <option disabled selected style="padding: 10px">Select Detail Golongan</option>
                                            @foreach($detail as $d)
                                            <option value="{{ $d->ID_DETAIL_GOLONGAN }}">{{$d->NAMA_DETAIL_GOLONGAN}}</option>
                                            @endforeach
                                            </select>  
                                        </div>
                                      </div>
                        
                                    <div class="form-group">
                                        <label for="example-nama" class="col-md-12">Nama Detail Golongan</label>
                                        <div class="col-md-12">
                                            <input type="name" placeholder="Please input nama detail golongan"
                                                class="form-control pl-0 form-control-line" name="NAMA_DETAIL_GOLONGAN"
                                                id="NAMA_DETAIL_GOLONGAN" required value="{{ $dg->ID_NAMA_GOLONGAN }}">
                                        </div>
                                    </div>                                    
                                    <div class="form-group">
                                        <label class="col-sm-12 mb-0">Golongan</label>
                                        <div class="col-sm-12">
                                        <select class="form-control pl-0 form-control-line" name="ID_GOLONGAN">
                                            <option disabled selected style="padding: 10px">Select Golongan</option>
                                            @foreach($golongan as $g)
                                            <option value="{{ $g->ID_GOLONGAN }}">{{$g->NAMA_GOLONGAN}}</option>
                                            @endforeach
                                            </select>  
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <label class="col-sm-12 mb-0">Nilai</label>
                                        <div class="col-sm-12">
                                        <select class="form-control pl-0 form-control-line" name="ID_NILAI">
                                            <option disabled selected style="padding: 10px">Select Nilai</option>
                                            @foreach($nilai as $n)
                                            <option value="{{ $n->ID_NILAI }}">{{$n->NAMA_NILAI}}</option>
                                            @endforeach
                                            </select>  
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