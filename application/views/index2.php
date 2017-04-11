<?php
$this->load->view('_head');
?>
<style>
    .center-navbar{
        display: block; 
        text-align: center; 
        color: white; 
        padding: 15px; 
        /* adjust based on your layout */
        margin-left: 0px; 
        margin-right: 26px;
        font-weight: bolder;
        text-transform: uppercase;
        /*letter-spacing: 3px;*/
    }
</style>
<div class="wrapper" ng-app="aneon-manager">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="javascript: return false;" class="logo hidden-xs">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>NE</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Aneon</b>MGR</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="javascript:return false;" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="javascript: return false" ng-click="reloadPage()"><i class="fa fa-refresh"></i></a>
                    </li>
                    <!-- /.messages-menu -->

                </ul>
            </div>
            <div class="center-navbar">
                <div class="hidden-sm hidden-md hidden-lg" style="font-size: 0.9em">{{titleDoc}}</div>
                <div class="hidden-xs">{{titleDoc}}</div>
            </div>

        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">



            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <!-- Optionally, you can add icons to the links -->
                <li><a href="#/page/home/Beranda"><i class='fa fa-home'></i> <span>Beranda</span></a></li>

                <?php
                $this->load->helper('directory');

                $menuGroup = array();
                $allFiles = directory_map('application/views/page/', 1);
                foreach ($allFiles as $key => $val) {
                    if (strpos($val, "/") === false) {//is not directory
                        if (strpos($val, "_view") > -1) {//is a page
                            ob_start();
                            $buffer = ob_get_contents();
                            require_once("application/views/page/" . $val);
                            ob_end_clean();
                            echo $buffer;
                            if (!isset($menuGroup[$pageParams['menuGroup']])) {
                                $menuGroup[$pageParams['menuGroup']] = array();
                            }
                            $menuGroup[$pageParams['menuGroup']][] = array(
                                'filename' => $pageInfo['filename'],
                                'title' => $pageParams['title'],
                                'order' => $pageParams['order'],
                                'customUrl' => isset($pageParams['customUrl']) ? $pageParams['customUrl'] : "",
                                'openNewWindow' => isset($pageParams['openNewWindow']) ? $pageParams['openNewWindow'] : 0,
                            );
                        }
                    }
                }
                //                    $menuGroup = from($menuGroup)->orderBy(function ($menu) {
                //                        return $menu["order"];
                //                    })->toArray();
                foreach ($menuGroup as $key => $val) {
                    ?>
                    <!-- dynamic menu loader abc4455-->
                    <li class="treeview">
                        <a href="javascript:return false;"><i class='fa fa-folder'></i> <span><?php echo $key ?></span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <?php
                            foreach ($val as $keyItemMenu => $itemMenu) {
                                $targetBlank = "";
                                if ($itemMenu['openNewWindow'] == 1) {
                                    $targetBlank = "target='_blank'";
                                }

                                if ($itemMenu['openNewWindow'] != "") {
                                    ?>
                                    <li><a <?php echo $targetBlank ?> href="<?php echo $itemMenu['customUrl'] ?>"><i
                                                class='fa fa-circle-o'></i>
                                            <span><?php echo $itemMenu['title'] ?></span></a>
                                    </li>
                                    <?php
                                } else {
                                    ?>
                                    <li><a <?php echo $targetBlank ?> href="#/page/<?php echo $itemMenu['filename'] ?>/<?php echo $itemMenu['title'] ?>"><i
                                                class='fa fa-circle-o'></i>
                                            <span><?php echo $itemMenu['title'] ?></span></a>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                }
                ?>

            </ul>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <style>
        .main-footer {
            border-top: 1px solid white !important;
            height: 30px !important;
            padding: 2px !important;
        }

        .content {
            min-height: 250px;
            /* padding: 15px; */
            margin-right: auto;
            margin-left: auto;
            padding-left: 1px !important;
            padding-right: 1px !important;
            padding-bottom: 1px !important;
        }
    </style>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div id="ngview" ng-view></div>
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs" style="padding: 0px;">
        </div>
        <!-- Default to the left -->
        <strong>&copy; 2017 <a href="#">Aneon System</a>.</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a>
            </li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane active" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class='control-sidebar-menu'>
                    <li>
                        <a href='javascript::;'>
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class='control-sidebar-menu'>
                    <li>
                        <a href='javascript::;'>
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="label label-danger pull-right">70%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked/>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div>
                    <!-- /.form-group -->
                </form>
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
    <div class='control-sidebar-bg'></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.1.4 -->
<!--<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>-->
<!-- Bootstrap 3.3.2 JS -->
<!--<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>-->
<!-- AdminLTE App -->
<!--<script src="dist/js/app.min.js" type="text/javascript"></script>-->

<!-- Optionally, you can add Slimscroll and FastClick plugins.
  Both of these plugins are recommended to enhance the
  user experience. Slimscroll is required when using the
  fixed layout. -->

<?php
$this->load->view('_foot');
?>