@extends('layout/index')
@section('konten')

<div id="content">
               <div class="panel box-shadow-none content-header">
                  <div class="panel-body">
                    <div class="col-md-12">
                        <h3 class="animated fadeInLeft">Data Nilai</h3>
                        <p class="animated fadeInDown">
                          Table <span class="fa-angle-right fa"></span> Data Nilai
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
                        <a href="createnilai" class="btn btn-success d-none d-md-inline-block text-white" target="_blank">Add Nilai</a>
                        @endif
                       
                      <table id="datatables-example" class="table table-striped table-bordered" width="100%" cellspacing="0">
                       <thead>
                                            <tr>
                                                <th class="border-top-0">ID Nilai</th>
                                                <th class="border-top-0">Nilai</th>
                                                <th class="border-top-0">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            
                                            @foreach($NILAI as $NIL)
                                              <tr>
                                                <td>{{ $NIL -> ID_NILAI}}</td>
                                                <td>{{ $NIL -> NILAI }}</td>
                                                @if (session()->get('TIPE_AKUN') == "ADMIN")                                         
                                                <td class="glyphicon glyphicon-pencil" style="padding: 5px"><a href="editnilai{{ $NIL -> ID_NILAI}}">Edit</td>
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


@endsection