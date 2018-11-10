<?php foreach ($single_users as $key => $value) {
    $id = $value['user_id'];
}
?>
<form id='assign_user_role' action="" enctype="multipart/form-data" method="post"
      accept-charset="utf-8">
    <div class="box-body">
        <div id="status"></div>
        <div class="form-group">
            <label for=""> User Name </label>
            <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo $value["user_name"]; ?>"
                   placeholder="" readonly>
            <input type="hidden" name="updateId" id="updateId" value="<?php echo $id ?>">
            <span id="error_user_name" class="has-error"></span>
        </div>
        <div class = "form-group">
            <label for=""> Roll Name </label>
            <select class="form-control" name="roll_name">
                <option value="" selected disabled>Choose Roll</option>
                <?php
                foreach ($all_roll as $roll) {
                    ?>
                    <option  <?php if ($value['role_id'] == $roll['roll_id']) echo "selected" ?>
                        value = "<?php echo $roll['roll_id']; ?>">
                        <?php echo $roll['roll_name']; ?></option>
                <?php
                }
                ?>
            </select>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        <input type="submit" id="submit" name="submit" value="Save" class="btn btn-primary">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <small><img id="loader" src="<?php echo site_url('assets/images/loadingg.gif'); ?>"/></small>
    </div>
</form>
<script src="<?php echo base_url(); ?>assets/js/Custom_Validation/user/assign_user_role_validation.js"></script>