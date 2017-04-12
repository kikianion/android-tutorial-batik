<!-- Main content -->
<section class="content" style="text-align: center; ">
    <div style="padding: 10px; max-width: 400px; margin: 0 auto">
        <?php
        $this->load->database();
        $CI =& get_instance();
        $CI->load->model('Crud_cat_model');
        $rs = $CI->Crud_cat_model->allObj("order by order_");

//        $this->load->helper('dataprocessing');

        foreach ($rs as $row) {
            ?>
            <div style="padding-top: 5px; padding-bottom: 5px">
                <a class="btn btn-primary btn-block"><?php echo $row->name ?></a>
            </div>

            <?php
        }
        ?>

    </div>


</section>
<!-- /.content -->
