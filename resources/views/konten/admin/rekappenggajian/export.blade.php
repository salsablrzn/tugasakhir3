<?php
header('Content-type: application/vnd-ms-excel');

header("Content-Disposition: attachment; filename=$title.xls");

// header("Pragma: no-cache");

// header("Expires: 0");
?>
<style type="text/css">
    table {
        border-collapse: collapse;
    }

    table,
    tr,
    td {
        border: 1px solid black;
        padding: 5px;
    }
</style>
<table border="1" width="100%">
    <tr>
        @if($tipe=="utama")
            <td colspan="5" style="text-align:center; padding: 12px;">
                <?php echo $title; ?>
            </td>
        @elseif($tipe=="tunjangan")
            <td colspan="7" style="text-align:center; padding: 12px;">
                <?php echo $title; ?>
            </td>
        @else
            <td colspan="9" style="text-align:center; padding: 12px;">
                <?php echo $title; ?>
            </td>
        @endif
    </tr>
    <tr>
        <td class="border-top-0 text-center">No</td>
        <td class="border-top-0 text-center">Nama Pegawai</td>
        <td class="border-top-0 text-center">Bulan</td>
        @if ($tipe == 'utama' || $tipe == 'keseluruhan')
            <td class="border-top-0 text-center">Gaji Pokok</td>
        @endif
        @if ($tipe == 'tunjangan' || $tipe == 'keseluruhan')
            <td class="border-top-0 text-center">Total Tunjangan</td>
            <td class="border-top-0 text-center">Potongan Tunjangan</td>
            <td class="border-top-0 text-center">Tunjangan Bersih</td>
        @endif
        @if ($tipe == 'keseluruhan')
            <td class="border-top-0 text-center">Gaji Bersih</td>
        @endif
        <td class="border-top-0 text-center">Di Input Pada</td>
    </tr>
    @foreach ($data as $item)
        
        <tr>

            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->NAMA_PEGAWAI }}</td>
            <td>{{ Helper::bulantahun($item->BULAN_GAJIAN) }}</td>
            @if ($tipe == 'utama' || $tipe == 'keseluruhan')
                <td>{{$item->GAJI_POKOK}}</td>
            @endif
            @if ($tipe == 'tunjangan' || $tipe == 'keseluruhan')
      
                <td>{{$item->TUNJANGAN}}</td>
           
                <td>{{$item->POTONGAN_TUNJANGAN}}</td>
                <td>{{$item->TOTAL_TUNJANGAN_PENGGAJIAN}}</td>
                
            @endif
            @if ($tipe == 'keseluruhan')
                <td>{{$item->TOTAL_GAJI}}</td>
            @endif
            <td>
                {{ Helper::waktu($item->CREATE_AT) }}
                <a class="btn btn-sm" href="{{url('admin/penggajian/exportpdf/'.$item->ID_PENGGAJIAN.'/'.$tipe.'')}}"><i class="fa fa-print"></i> </a>
            </td>
        </tr>
    @endforeach
</table>