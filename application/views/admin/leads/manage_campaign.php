<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <div class="_buttons">
                      <?php 
                      if (has_permission('campaign', '', 'create')) {
                        ?>
                        <a href="#" onclick="init_campaign(); return false;" class="btn mright5 btn-info pull-left display-block">
                     <?php echo 'New Campaign'; ?>
                     </a>
                     <a href="<?php echo admin_url('leads/campaignimport'); ?>" class="btn btn-info pull-left display-block mright5 hidden-xs">
                     <?php echo _l('import'); ?></a>
                        <?php
                          
                      }
                      ?>
                     
                     
                     
                     <div class="clearfix"></div>
                     <div class="row hide leads-overview">
                        <!--<hr class="hr-panel-heading" />-->
                        <div class="col-md-12">
                           <h4 class="no-margin"><?php echo _l('leads_summary'); ?></h4>
                        </div>
                        <?php
                           foreach($summary as $status) { ?>
                              <div class="col-md-2 col-xs-6 border-right">
                                    <h3 class="bold">
                                       <?php
                                          if(isset($status['percent'])) {
                                             echo '<span data-toggle="tooltip" data-title="'.$status['total'].'">'.$status['percent'].'%</span>';
                                          } else {
                                             // Is regular status
                                             echo $status['total'];
                                          }
                                       ?>
                                    </h3>
                                   <span style="color:<?php echo $status['color']; ?>" class="<?php echo isset($status['junk']) || isset($status['lost']) ? 'text-danger' : ''; ?>"><?php echo $status['name']; ?></span>
                              </div>
                           <?php } ?>
                     </div>
                  </div>
                  <hr class="hr-panel-heading" />
                  <div class="tab-content">
                     <?php
                        if($this->session->has_userdata('leads_kanban_view') && $this->session->userdata('leads_kanban_view') == 'true') { ?>
                     
                     <?php } else { ?>
                     <div class="row" id="leads-table">
                        
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="col-md-12">
                           <a href="#" data-toggle="modal" data-table=".table-campaign" data-target="#leads_bulk_actions" class="hide bulk-actions-btn table-btn"><?php echo _l('bulk_actions'); ?></a>
                           <div class="modal fade bulk_actions" id="leads_bulk_actions" tabindex="-1" role="dialog">
                              <div class="modal-dialog" role="document">
                                 <div class="modal-content">
                                    <div class="modal-header">
                                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                       <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                                    </div>
                                    <div class="modal-body">
                                       <?php if(has_permission('campaign','','delete')){ ?>
                                       <div class="checkbox checkbox-danger">
                                          <input type="checkbox" name="mass_delete" id="mass_delete">
                                          <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                                       </div>
                                       <hr class="mass_delete_separator" />
                                       <?php } ?>
                                      
                                    </div>
                                    <div class="modal-footer">
                                       <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                                       <a href="#" class="btn btn-info" onclick="campaign_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                                    </div>
                                 </div>
                                 <!-- /.modal-content -->
                              </div>
                              <!-- /.modal-dialog -->
                           </div>
                           <!-- /.modal -->
                           <?php

                              $table_data = array();
                              $_table_data = array(
                                '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="campaign"><label></label></div>',
                                array(
                                 'name'=>'Created',
                                 'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-number')
                               ),
                                array(
                                 'name'=>'Campaign',
                                 'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-name')
                               ),
                              );
                              
                              $_table_data[] = array(
                               'name'=>'Details',
                               'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-company')
                              );
                              $_table_data[] =   array(
                               'name'=>'Quantity',
                               'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-email')
                              );
                              $_table_data[] =  array(
                               'name'=>'Start Date',
                               'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-phone')
                              );
                              $_table_data[] =  array(
                                 'name'=>'End Date',
                                 'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-lead-value')
                                );
                              $_table_data[] =  array(
                               'name'=>'Bill Rate',
                               'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-tags')
                              );
                              $_table_data[] = array(
                              'name'=>'Yes/No',
                              'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-assigned')
                              );
                              
                             $_table_data[] = array(
                              'name'=>'Action',
                              'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-assigned')
                              );
                              foreach($_table_data as $_t){
                               array_push($table_data,$_t);
                              }
                              $custom_fields = get_custom_fields('campaign',array('show_on_table'=>1));
                              foreach($custom_fields as $field){
                              array_push($table_data,$field['name']);
                              }
                              $table_data = hooks()->apply_filters('campaign_table_columns', $table_data);
                              render_datatable($table_data,'campaign',
                              array('customizable-table'),
                              array(
                               'id'=>'table-campaign',
                               'data-last-order-identifier'=>'campaign',
                               'data-default-order'=>get_table_last_order('campaign'),
                               )); ?>
                        </div>
                     </div>
                     <?php } ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="campaign" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('leads/campaign'), ['id' => 'campaign-form']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="edit-title">Edit Campaign</span>
                    <span class="add-title">New Campaign</span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div id="additional"></div>
                        <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
                        <?php echo form_hidden('id'); ?>
                        <?php echo render_input('campaign','Campaign'); ?>
                        <?php echo render_input('details','Details'); ?>
                        <?php echo render_input('quantity','Quantity','','number'); ?>
                        <?php echo render_date_input('start_date','task_add_edit_start_date'); ?>
                        <?php 
                        $value = _d(date('Y-m-d', strtotime('+' . get_option('invoice_due_after') . ' DAY', strtotime(date('Y-m-d')))));
                        echo render_date_input('end_date','End Date',$value); ?>
                        <?php echo render_input('bill_rate','Bill Rate','','number'); ?>
                        <div class="form-group">
                        <label for="priority" class="control-label">Status</label>
                        <select name="yes_no" class="selectpicker" id="yes_no" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                           
                           <option value="No">No</option>
                           <option value="Yes">Yes</option>
                           
                        </select>
                     </div>
                        <br />
                        
                        
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                </div>
            </div><!-- /.modal-content -->
            <?php echo form_close(); ?>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php init_tail(); ?>
<script>
   
   $(function(){
      var CampaignServerParams = {};
     $.each($('._hidden_inputs._filters input'),function(){
       CampaignServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
     });
    initDataTable('.table-campaign', admin_url+'leads/campaigntable', ['undefined'], ['undefined'], CampaignServerParams, [[4,'desc'], [0,'desc']]);
    
    appValidateForm($('form'),{campaign:'required'},manage_campaign);
   });
   
   
   
   function manage_campaign(form) {
        var data = $(form).serialize();
        var url = form.action;
        $.post(url, data).done(function(response) {
            
            response = JSON.parse(response);
            if(response.success == true){
                alert_float('success',response.message);
                
            }
            $("input[name='id']").val('');
            $("input[name='campaign']").val('');
            $("input[name='details']").val('');
            $("input[name='quantity']").val('');
            $("input[name='start_date']").val('');
            $("input[name='bill_rate']").val('');
            $("select[name='yes_no']").selectpicker('refresh');
            $('.table-campaign').DataTable().ajax.reload();
            $('#campaign').modal('hide');
        }).fail(function(data){
            var error = JSON.parse(data.responseText);
            alert_float('danger',error.message);
        });
        return false;
    }
   
    function init_campaign(){
        $('#campaign').modal('show');
        $('.edit-title').addClass('hide');
        $("input[name='id']").val('');
        $("input[name='campaign']").val('');
        $("input[name='details']").val('');
        $("input[name='quantity']").val('');
        $("input[name='start_date']").val('');
        $("input[name='bill_rate']").val('');
        $("select[name='yes_no']").selectpicker('refresh');
    }
    
    function edit_campaign(i,e)
    {
        $('input[name="id"]').val(e),$('input[name="campaign"]').val($(i).attr("data-campaign")),$("input[name='details']").val($(i).attr("data-details")),$("input[name='quantity']").val($(i).attr("data-quantity")),$("input[name='start_date']").val($(i).attr("data-start_date")),$("input[name='bill_rate']").val($(i).attr("data-bill_rate")),$("input[name='end_date']").val($(i).attr("data-end_date")),$('select[name="yes_no"]').selectpicker('val',$(i).attr("data-yes_no")),
        $('.add-title').addClass('hide'),
        $('.edit-title').addClass('show'),
        $("#campaign").modal("show");
    }
    
    function campaign_bulk_action(event) {
    if (confirm_delete()) {
        var mass_delete = $('#mass_delete').prop('checked');
        var ids = [];
        var data = {};
        data.mass_delete = true;
        
        var rows = $('.table-campaign').find('tbody tr');
        $.each(rows, function () {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
                ids.push(checkbox.val());
            }
        });
        data.ids = ids;
        $(event).addClass('disabled');
        setTimeout(function () {
            $.post(admin_url + 'leads/campaignbulk_action', data).done(function () {
                window.location.reload();
            }).fail(function (data) {
                $('#lead-modal').modal('hide');
                alert_float('danger', data.responseText);
            });
        }, 200);
    }
}
    
</script>
</body>
</html>
