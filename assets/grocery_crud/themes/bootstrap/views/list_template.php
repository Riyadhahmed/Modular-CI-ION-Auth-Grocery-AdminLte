<?php
    $this->set_css($this->default_theme_path.'/bootstrap/css/bootstrap/bootstrap.min.css');
    $this->set_css($this->default_theme_path.'/bootstrap/css/font-awesome/css/font-awesome.min.css');    
    $this->set_css($this->default_theme_path.'/bootstrap/css/common.css');    
    $this->set_css($this->default_theme_path.'/bootstrap/css/list.css');
    $this->set_css($this->default_theme_path.'/bootstrap/css/general.css');
    $this->set_css($this->default_theme_path.'/bootstrap/css/plugins/animate.min.css');
	
	


    if ($this->config->environment == 'production') {
        $this->set_js_lib($this->default_javascript_path.'/'.grocery_CRUD::JQUERY);
        $this->set_js_lib($this->default_theme_path.'/bootstrap/build/js/global-libs.min.js');
    } else {
        $this->set_js_lib($this->default_javascript_path.'/'.grocery_CRUD::JQUERY);
        $this->set_js_lib($this->default_theme_path.'/bootstrap/js/jquery-plugins/jquery.form.js');
        $this->set_js_lib($this->default_theme_path.'/bootstrap/js/common/cache-library.js');
        $this->set_js_lib($this->default_theme_path.'/bootstrap/js/common/common.js');
    }

    //section libs
    $this->set_js_lib($this->default_theme_path.'/bootstrap/js/bootstrap/dropdown.min.js');
    $this->set_js_lib($this->default_theme_path.'/bootstrap/js/bootstrap/modal.min.js');
    $this->set_js_lib($this->default_theme_path.'/bootstrap/js/jquery-plugins/bootstrap-growl.min.js');
    $this->set_js_lib($this->default_theme_path.'/bootstrap/js/jquery-plugins/jquery.print-this.js');
	


    //page js
    $this->set_js_lib($this->default_theme_path.'/bootstrap/js/datagrid/gcrud.datagrid.js');
    $this->set_js_lib($this->default_theme_path.'/bootstrap/js/datagrid/list.js');

    $colspans = (count($columns) + 2);

    //Start counting the buttons that we have:
    $buttons_counter = 0;

    if (!$unset_edit) {
        $buttons_counter++;
    }

    if (!$unset_read) {
        $buttons_counter++;
    }

    if (!$unset_delete) {
        $buttons_counter++;
    }

    if (!empty($list[0]) && !empty($list[0]->action_urls)) {
        $buttons_counter = $buttons_counter +  count($list[0]->action_urls);
    }

    $list_displaying = str_replace(
        array(
            '{start}',
            '{end}',
            '{results}'
        ),
        array(
            '<span class="paging-starts">1</span>',
            '<span class="paging-ends">10</span>',
            '<span class="current-total-results">'. $this->get_total_results() . '</span>'
        ),
        $this->l('list_displaying'));
?>
<script type='text/javascript'>
    var base_url = '<?php echo base_url();?>';

    var subject = '<?php echo $subject?>';
    var ajax_list_info_url = '<?php echo $ajax_list_info_url; ?>';
    var ajax_list_url = '<?php echo $ajax_list_url;?>';
    var unique_hash = '<?php echo $unique_hash; ?>';

    var message_alert_delete = "<?php echo $this->l('alert_delete'); ?>";

</script>
    <br/>
    <div class="container gc-container">
        <div class="success-message hidden"><?php
        if($success_message !== null){?>
           <?php echo $success_message; ?> &nbsp; &nbsp;
        <?php }
        ?></div>

 		<div class="row">
        	<div class="table-section">
                <div class="table-label">
                    <div class="floatL l5">
                        <?php echo $subject_plural; ?>
                    </div>                  
                    <div class="floatR r5 minimize-maximize-container minimize-maximize">
                        <i class="fa fa-caret-up"></i>
                    </div>
                    <div class="floatR r5 gc-full-width">
                        <i class="fa fa-expand"></i>                        
                    </div>                      
                    <div class="clear"></div>
                </div>
                <div class="table-container">
                    <?php echo form_open("", 'method="post" autocomplete="off" id="gcrud-search-form"'); ?>
                        <div class="header-tools">
                            <?php if(!$unset_add){?>
                                <div class="floatL t5">
                                    <a class="btn btn-default" href="<?php echo $add_url?>"><i class="fa fa-plus"></i> &nbsp; <?php echo $this->l('list_add'); ?> <?php echo $subject?></a>
                                </div>
                            <?php } ?>
                            <div class="floatR">
                                <?php if(!$unset_export) { ?>
                                    <a class="btn btn-default t5 gc-export" data-url="<?php echo $export_url; ?>">
                                        <i class="fa fa-cloud-download floatL t3"></i>
                                        <span class="hidden-xs floatL l5">
                                            <?php echo $this->l('list_export');?>
                                        </span>
                                        <div class="clear"></div>
                                    </a>
                                <?php } ?>
                                <?php if(!$unset_print) { ?>
                                    <a class="btn btn-default t5 gc-print" data-url="<?php echo $print_url; ?>">
                                        <i class="fa fa-print floatL t3"></i>
                                        <span class="hidden-xs floatL l5">
                                            <?php echo $this->l('list_print');?>
                                        </span>
                                        <div class="clear"></div>
                                    </a>
                                <?php }?>

                                <a class="btn btn-primary search-button t5">
                                    <i class="fa fa-search"></i>
                                    <input type="text" name="search" class="search-input" />
                                </a>
                            </div>
                            <div class="clear"></div>
                        </div>
        			    <table class="table table-bordered grocery-crud-table table-hover">
        					<thead>
        						<tr>
        							<th colspan="2" <?php if ($buttons_counter === 0) {?>class="hidden"<?php }?>>
                                        <?php echo $this->l('list_actions'); ?>
                                    </th>
                                    <?php foreach($columns as $column){?>
                                        <th class="column-with-ordering" data-order-by="<?php echo $column->field_name; ?>"><?php echo $column->display_as; ?></th>
                                    <?php }?>
        						</tr>
        						
        						<tr class="filter-row gc-search-row">
        							<td class="no-border-right <?php if ($buttons_counter === 0) {?>hidden<?php }?>">
                                        <?php if (!$unset_delete) { ?>
            							     <div class="floatL t5">
            							         <input type="checkbox" class="select-all-none" />
            							     </div>
                                         <?php } ?>
        							 </td>
        							<td class="no-border-left <?php if ($buttons_counter === 0) {?>hidden<?php }?>">
                                        <div class="floatL">
                                            <a href="javascript:void(0);" title="<?php echo $this->l('list_delete')?>"
                                               class="hidden btn btn-default delete-selected-button">
                                                <i class="fa fa-trash-o text-danger"></i>
                                                <span class="text-danger"><?php echo $this->l('list_delete')?></span>
                                            </a>
                                        </div>
                                        <div class="floatR l5">
                                            <a href="javascript:void(0);" class="btn btn-default gc-refresh">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </div>
                                        </div>
                                        <div class="clear"></div>
                                    </td>
                                    <?php foreach($columns as $column){?>
                                        <td>
                                            <input type="text" class="form-control searchable-input floatL" placeholder="Search <?php echo $column->display_as; ?>" name="<?php echo $column->field_name; ?>" />
                                        </td>
                                    <?php }?>
        						</tr>

        					</thead>
        					<tbody>
                                <?php include(__DIR__."/list_tbody.php"); ?>
        					</tbody>
                            </table>
                            <!-- Table Footer -->
        					<div class="footer-tools">

                                            <!-- "Show 10/25/50/100 entries" (dropdown per-page) -->
                                            <div class="floatL t20 l5">
                                                <div class="floatL t10">
                                                    <?php list($show_lang_string, $entries_lang_string) = explode('{paging}', $this->l('list_show_entries')); ?>
                                                    <?php echo $show_lang_string; ?>
                                                </div>
                                                <div class="floatL r5 l5 t3">
                                                    <select name="per_page" class="per_page form-control">
                                                        <?php foreach($paging_options as $option){?>
                                                            <option value="<?php echo $option; ?>"
                                                                    <?php if($option == $default_per_page){?>selected="selected"<?php }?>>
                                                                        <?php echo $option; ?>&nbsp;&nbsp;
                                                            </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="floatL t10">
                                                    <?php echo $entries_lang_string; ?>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                            <!-- End of "Show 10/25/50/100 entries" (dropdown per-page) -->


                                            <div class="floatR r5">

                                                <!-- Buttons - First,Previous,Next,Last Page -->
                                                <ul class="pagination">
                                                    <li class="disabled paging-first"><a href="#"><i class="fa fa-step-backward"></i></a></li>
                                                    <li class="prev disabled paging-previous"><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                                                    <li>
                                                        <span class="page-number-input-container">
                                                            <input type="number" value="1" class="form-control page-number-input" />
                                                        </span>
                                                    </li>
                                                    <li class="next paging-next"><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                                                    <li class="paging-last"><a href="#"><i class="fa fa-step-forward"></i></a></li>
                                                </ul>
                                                <!-- End of Buttons - First,Previous,Next,Last Page -->

                                                <input type="hidden" name="page_number" class="page-number-hidden" value="1" />

                                                <!-- Start of: Settings button -->
                                                <div class="btn-group floatR t20 l10 settings-button-container">
                                                    <button type="button" class="btn btn-default dropdown-toggle settings-button" data-toggle="dropdown">
                                                        <i class="fa fa-cog r5"></i>
                                                        <span class="caret"></span>
                                                    </button>

                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        <li>
                                                            <a href="javascript:void(0)" class="clear-filtering">
                                                                <i class="fa fa-eraser"></i> Clear filtering
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <!-- End of: Settings button -->

                                            </div>


                                            <!-- "Displaying 1 to 10 of 116 items" -->
                                            <div class="floatR r10 t30">
                                                <?php echo $list_displaying; ?>
                                                <span class="full-total-container hidden">
                                                    <?php echo str_replace(
                                                                "{total_results}",
                                                                "<span class='full-total'>" . $this->get_total_results() . "</span>",
                                                                $this->l('list_filtered_from'));
                                                    ?>
                                                </span>
                                            </div>
                                            <!-- End of "Displaying 1 to 10 of 116 items" -->

                                            <div class="clear"></div>
                            </div>
                            <!-- End of: Table Footer -->

                    <?php echo form_close(); ?>
                </div>
        	</div>

            <!-- Delete confirmation dialog -->
            <div class="delete-confirmation modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><?php echo $this->l('list_delete'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <p><?php echo $this->l('alert_delete'); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->l('form_cancel'); ?></button>
                            <button type="button" class="btn btn-danger delete-confirmation-button"><?php echo $this->l('list_delete'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Delete confirmation dialog -->

            <!-- Delete Multiple confirmation dialog -->
            <div class="delete-multiple-confirmation modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><?php echo $this->l('list_delete'); ?></h4>
                        </div>
                        <div class="modal-body">
                            <p><?php echo $this->l('alert_delete'); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <?php echo $this->l('form_cancel'); ?>
                            </button>
                            <button type="button" class="btn btn-danger delete-multiple-confirmation-button"
                                    data-target="<?php echo $delete_multiple_url; ?>">
                                <?php echo $this->l('list_delete'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Delete Multiple confirmation dialog -->

            </div>
        </div>