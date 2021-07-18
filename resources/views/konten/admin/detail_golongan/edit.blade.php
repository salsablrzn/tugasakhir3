@extends('layout/index')
@section('konten')


<div id="content">
                <div class="panel box-shadow-none content-header">
                  <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">Data Gaji Pokok</h3>
                        <p class="animated fadeInDown">
                          Edit <span class="fa-angle-right fa"></span> Data Gaji Pokok
                        </p>
                    </div>
                  </div>
                </div>
                <div class="form-element">
                  <div class="col-md-12 padding-0">
                    <div class="col-md-12">
                      <div class="panel form-element-padding">
                        <div class="panel-heading">
                         <h4>Data Gaji Pokok</h4>
                        </div>
                         <div class="panel-body" style="padding-bottom:30px;">
                          <div class="col-md-12">

                            @foreach ($DETAIL_GOLONGAN as $DETAIL_GOLONGAN)
                            
                            <form class="form-horizontal form-material" role="form" action="/updatedetailgolongan/{{$DETAIL_GOLONGAN->ID_DETAIL_GOLONGAN}}" method="post">
                                   
                            {{ @csrf_field() }}
                                    <div class="form-group">
                                        <label for="example-id" class="col-md-12">ID Detail Golongan</label>
                                        <div class="col-md-12">
                                            <input type="id" placeholder="Auto ID"
                                                class="form-control pl-0 form-control-line" name="ID_DETAIL_GOLONGAN"
                                                id="ID_DETAIL_GOLONGAN" disabled="" required value=" {{ $DETAIL_GOLONGAN->ID_DETAIL_GOLONGAN}}" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="example-nama" class="col-md-12">Nama Detail Golongan</label>
                                        <div class="col-md-12">
                                            <input type="name" placeholder="Please input nama detail golongan"
                                                class="form-control pl-0 form-control-line" name="NAMA_DETAIL_GOLONGAN"
                                                id="NAMA_DETAIL_GOLONGAN" required value=" {{ $DETAIL_GOLONGAN->NAMA_DETAIL_GOLONGAN}}">
                                        </div>
                                    </div>                                                                        
                                    <div class="form-group">
                                        <label class="col-sm-12 mb-0">Nama Golongan</label>
                                        <div class="col-sm-12">
                                        <select class="form-control pl-0 form-control-line" id="ID_GOLONGAN" name="ID_GOLONGAN">                                            
                                          @foreach($GOLONGAN as $g) 
                                          <option value=" {{ $g->ID_GOLONGAN}}" 
                                            @if($DETAIL_GOLONGAN->ID_GOLONGAN === $g->ID_GOLONGAN) 
                                            selected
                                            @endif>
                                            {{ $g->NAMA_GOLONGAN}}
                                          </option>
                                          @endforeach
                                            </select>  
                                        </div>
                                      </div>  
                                      <div class="form-group">
                                        <label class="col-sm-12 mb-0">Nilai</label>
                                        <div class="col-sm-12">
                                        <select class="form-control pl-0 form-control-line" id="ID_NILAI" name="ID_NILAI">                                            
                                          @foreach($NILAI as $n) 
                                          <option value=" {{ $n->ID_NILAI}}" 
                                            @if($DETAIL_GOLONGAN->ID_NILAI === $n->ID_NILAI) 
                                            selected
                                            @endif>
                                            {{ $n->NILAI}}
                                          </option>
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

                                @endforeach
                          </div>
                        </div>
                      </div>
                    </div>
              </div>
            </div>



@endsection