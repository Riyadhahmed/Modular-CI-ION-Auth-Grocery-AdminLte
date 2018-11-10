<?php
if ($single_user_details) {

    foreach ($single_user_details as $s_user_value) {

        $user_id = $s_user_value['user_id'];
    }
    if($s_user_value['file_path']==''){
        $image = base_url('assets/images/user/1482829335.png');
    }else{
        $image = base_url($s_user_value['file_path']);
    }
    ?>
    <div class = "tab-content">
        <div id = "user_details" class = "tab-pane fade in active col-md-9">
            <div class="col-md-2"><strong>User Name  </strong></div><div class="col-md-10"> :   <?php echo $s_user_value['user_name']; ?> </div>
            <div class="col-md-2"><strong>User Email  </strong></div><div class="col-md-10"> :   <?php echo $s_user_value['user_email']; ?> </div>
            <div class="col-md-2"><strong>Gender  </strong></div><div class="col-md-10"> :   <?php echo $s_user_value['gender']; ?> </div>
            <div class="col-md-2"><strong>Date of Birth  </strong></div><div class="col-md-10"> : <?php echo  $s_user_value['dob'] ?> </div>
            <div class="col-md-2"><strong>Contact  </strong></div><div class="col-md-10"> : <?php echo $s_user_value['contact'] ?></div>
            <div class="col-md-2"><strong>Country  </strong></div><div class="col-md-10"> : <?php echo $s_user_value['country'] ?></div>
            <div class="col-md-2"><strong>State  </strong></div><div class="col-md-10"> : <?php echo  $s_user_value['state'] ?></div>
            <div class="col-md-2"><strong>Address  </strong></div><div class="col-md-10"> : <?php echo  $s_user_value['address'] ?></div>
            <div class="col-md-2"><strong>User Details  </strong></div><div class="col-md-10"> : <?php echo $s_user_value['user_details'] ?></div>
        </div>
        <div class = "col-md-3">
            <img src="<?php echo $image; ?>" width="220px" class="img-thumbnail"/>
        </div>
    </div>
    <div class="clearfix"></div>

<?php

} else {
    echo '<i class="icon fa fa-times"></i><strong> Sorry ! </strong>  No records have found .';
}
?>