$(document).ready(function() {

    $("#gaji_pokok").keyup(function(){
        $('#total_gaji').val(parseInt($("#total_tunjangan").val())+parseInt($('#gaji_pokok').val()));
      });

    // Tampilkan kota saat provinsi dipilih
    $('.select-pegawai').on('change', function () { 
       var token = $('meta[name="csrf-token"]').attr('content');
       const id_kehadiran = jQuery(this).val();
       console.log('id_kehadiran '+id_kehadiran);
       
           $.ajax({
               type: 'POST',
               headers: {
                   'X-CSRF-TOKEN': token
               },
               url: '/data-pegawai-non',
               data: {id : id_kehadiran},
               dataType: 'json',
               success: function(data){
                  document.getElementById('golongan').value=data[0].NAMA_DETAIL_GOLONGAN;
                  document.getElementById('lama_kerja').value=data[1]+" Tahun";

                  document.getElementById('tunjangan').value=data[2][0].tunjangan;
                  document.getElementById('potongan_tunjangan').value=data[0].POTONGAN_TUNJANGAN*100+" %";

                  $tunjangan        = data[2][0].tunjangan;
                  $potongan         = $tunjangan*data[0].POTONGAN_TUNJANGAN;
                  $total_tunjangan  = $tunjangan-$potongan;
                  
                  document.getElementById('total_tunjangan').value=$total_tunjangan;
                  
                  $total_gaji = $gaji_utama + $total_tunjangan;
                  document.getElementById('total_gaji').value=$total_gaji;
               }
           });
   });
});