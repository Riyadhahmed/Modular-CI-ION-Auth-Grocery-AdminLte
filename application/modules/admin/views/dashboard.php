<!-- Info boxes -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-graduation-cap"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Students in <?php echo date('Y'); ?></span>
                <span class="info-box-number"></span>
                <span><a href="#" class="small-box-footer">View All <i
                                class="fa fa-arrow-circle-right"></i></a></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total Parents</span>
                <span class="info-box-number"></span>
                <a href="#" class="small-box-footer">View All <i
                            class="fa fa-arrow-circle-right"></i></a>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <!-- fix for small devices only -->
    <div class="clearfix visible-sm-block"></div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-address-book-o"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Current Teachers</span>
                <span class="info-box-number"></span>
                <a href="#" class="small-box-footer">View All <i
                            class="fa fa-arrow-circle-right"></i></a>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-orange"><i class="fa fa-graduation-cap"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">New Message</span>
                <span class="info-box-number"></span>
                <span><a href="#" class="small-box-footer">View All <i
                                class="fa fa-arrow-circle-right"></i></a></span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

<!-- /.row -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="col-md-12 col-sm-12">   
                    <h1 style="color: #7d8997;font-weight: bold;text-transform:uppercase;display:inline-block;">
                       <img src="<?php echo base_url('assets/images/credit/'.$_SESSION['settings_data']['settings_file_path']); ?>" width="80px" /> 
                        <?php echo $_SESSION['settings_data']['settings_name']; ?>
                     </h1>               
                </div>
            </div>
        </div>
    </div>
</div>