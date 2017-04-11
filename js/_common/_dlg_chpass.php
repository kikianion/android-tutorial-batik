<script type="text/javascript">
    function dlg_chpass_yes(){

        //todo implementasi md5 password, harus diganti processing function ini

        var password_lama=$("#password-lama").val();
        var password_baru=$("#password-baru").val();
        var password_baru2=$("#password-baru2").val();


        if(password_baru!=password_baru2){
            alert("Password baru dan perulangan tidak sama, silahkan isi dengan benar");
            return;
        }

        data={
            lama: password_lama,
            baru: password_baru,
        };

        $.ajax({
            url: 'index.php?mod=common-ds&f=chpass_save&ajax=1&',
            dataType: 'json',
            data: data,
            type: 'post',
            success: function (res) {
                if (res.result == 'ok') {
                    flashMessage("Berhasil","success");
                    $("#dlg-chpass" ).modal("hide");
                }
                else {
                    flashMessage("Gagal","error");
                }
            },
            error: function () {
                flashMessage("Gagal mengirim data","error");
            }
        });


    }

    function dlg_chpass_open(){
        $("#password-lama").val("");
        $("#password-baru").val("");
        $("#password-baru2").val("");
        $( "#dlg-chpass" ).modal("show");
    }

    $(function(){
        $('#dlg-chpass').modal({ show: false});
    });    

</script>

<!-- Modal -->

<div id="dlg-chpass" class="modal" role="dialog" data-backdrop="static" style="display: none;">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header alert-info">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ganti Password</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <p>
                        <label for="Bidang Urusan:">Password lama</label>
                        <input type="password" id="password-lama" class="form-control">
                    </p>
                    <p>
                        <label for="Bidang Urusan:">Password baru</label>
                        <input type="password" id="password-baru" class="form-control">
                    </p>
                    <p>
                        <label for="Bidang Urusan:">Password baru (ulangi)</label>
                        <input type="password" id="password-baru2" class="form-control">
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-default" onclick="dlg_chpass_yes()">Simpan</button>
            </div>
        </div>

    </div>
</div>

