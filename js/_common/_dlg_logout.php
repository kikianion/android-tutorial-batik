<script type="text/javascript">
    function dlg_logout_yes(){
        $("#stat1").html("Tunggu, sedang keluar dari aplikasi...")
        window.location="_auth/logout.php";
    }

    function dlg_logout_open(){
        $( "#dlg-logout" ).modal("show");
    }

    //init dialog

    $(function(){
        $('#dlg-logout').modal({ show: false});

    })


</script>

<!-- Modal -->

<div id="dlg-logout" class="modal" role="dialog" data-backdrop="static" style="display: none;">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header alert-info">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Keluar aplikasi</h4>
            </div>
            <div class="modal-body">
                <p>
                    Apakah ingin keluar dari aplikasi ?
                </p>
                <p id="stat1">
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-default" onclick="dlg_logout_yes()">Ya</button>
            </div>
        </div>

    </div>
</div>

