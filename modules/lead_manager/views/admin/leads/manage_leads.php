<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="_filters _hidden_inputs hidden">
               <?php
               foreach($statuses as $status){
                  echo form_hidden('status_'.$status['id']);
               }
               foreach($staffs as $staff){
                  echo form_hidden('assigned_'.$staff['staffid']);  
               }
               foreach($sources as $source){
                  echo form_hidden('source_'.$source['id']);
               }
               foreach($lm_follow_ups as $lm_follow_up){
                  echo form_hidden('lm_follow_up_'.$lm_follow_up['id']);
               }
               foreach($years as $year){
                  echo form_hidden('period_year_'.$year['year']);
               }
               for ($m = 1; $m <= 12; $m++) { 
                echo form_hidden('period_month_'.$m);
             }
             echo form_hidden('period_from');
             echo form_hidden('period_to');
             ?>
          </div>
          <div class="panel_s mbot5">
            <div class="panel-body">
               <div class="_buttons">
                   <div class="row">
                       
                   </div>
                   
                  <div class="row mtop0">
                     <div class="col-lg-2">
                         <a href="#" onclick="init_lead(); return false;" class="btn mright5 btn-info pull-left display-block">
                         <?php echo _l('new_lead'); ?>
                        </a>
                         <?php //echo _l('manage_leads_heading'); ?></div>
                     <div class="col-lg-8">
                        <div class="row">
                            <div class="col-md-4"><h3 class="bold" style="margin: 0px;" id="leadcount">0</h3><span style="color: #272323;">Actual FB count</span>
                    
                    </div>
                           <div class="col-md-4"><?php echo render_date_input('date_f','','',['placeholder' => _l('lm_follow_up_from_placeholder')]); ?></div>
                           <div class="col-md-4"><?php echo render_date_input('date_t','','',['placeholder' => _l('lm_follow_up_to_placeholder')]); ?></div>
                        </div>
                     </div>
                     <div class="col-lg-2"><div class="btn-group pull-right btn-with-tooltip-group _filter_data" data-toggle="tooltip" data-title="Filter by">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           <i class="fa fa-filter" aria-hidden="true"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-left" style="width:300px;">
                           <li class="active"><a href="#" data-cview="all" onclick="dt_custom_view('','.table-lead-managerd',''); return false;">All</a>
                           </li>
                           <li class="divider"></li>
                           <li class="dropdown-submenu pull-left status">
                              <a href="#" tabindex="-1"><?php echo _l('leads_dt_status'); ?></a>
                              <ul class="dropdown-menu dropdown-menu-left">
                                 <?php foreach($statuses as $key => $status) { ?>
                                    <li>
                                       <a href="#" data-cview="status_<?php echo $status['id']; ?>" onclick="dt_custom_view('<?php echo $status['id']; ?>','.table-lead-managerd','status_<?php echo $status['id']; ?>'); return false;"><?php echo $status['name']; ?></a>
                                    </li>
                                 <?php } ?>
                              </ul>
                           </li>
                           <div class="clearfix"></div>
                           <li class="divider"></li>
                           <li class="dropdown-submenu pull-left assigned">
                              <a href="#" tabindex="-1"><?php echo _l('leads_dt_assigned'); ?></a>
                              <ul class="dropdown-menu dropdown-menu-left">
                                 <?php if(has_permission('leads','','view')){
                                   foreach($staffs as $staff) { ?>
                                    <li>
                                       <a href="#" data-cview="assigned_<?php echo $staff['staffid']; ?>" onclick="dt_custom_view('<?php echo $staff['staffid']; ?>','.table-lead-managerd','assigned_<?php echo $staff['staffid']; ?>'); return false;"><?php echo $staff['full_name']; ?></a>
                                    </li>
                                 <?php }} ?>
                              </ul>
                           </li>
                           <div class="clearfix"></div>
                           <li class="divider"></li>
                           <li class="dropdown-submenu pull-left source">
                              <a href="#" tabindex="-1"><?php echo _l('lead_sources'); ?></a>
                              <ul class="dropdown-menu dropdown-menu-left">
                                 <?php foreach($sources as $source) { ?>
                                    <li>
                                       <a href="#" data-cview="source_<?php echo $source['id']; ?>" onclick="dt_custom_view('<?php echo $source['id']; ?>','.table-lead-managerd','source_<?php echo $source['id']; ?>'); return false;"><?php echo $source['name']; ?></a>
                                    </li>
                                 <?php } ?>
                              </ul>
                           </li>
                           <div class="clearfix"></div>
                           <li class="divider"></li>
                           <li class="dropdown-submenu pull-left lm_follow_up">
                              <a href="#" tabindex="-1"><?php echo _l('lead_manger_dt_follow_up'); ?></a>
                              <ul class="dropdown-menu dropdown-menu-left">
                                 <?php foreach($lm_follow_ups as $lm_follow_up) { ?>
                                    <li>
                                       <a href="#" data-cview="lm_follow_up_<?php echo $lm_follow_up['id']; ?>" onclick="dt_custom_view('<?php echo $lm_follow_up['id']; ?>','.table-lead-managerd','lm_follow_up_<?php echo $lm_follow_up['id']; ?>'); return false;"><?php echo $lm_follow_up['name']; ?></a>
                                    </li>
                                 <?php } ?>
                              </ul>
                           </li>
                           <div class="clearfix"></div>
                           <li class="divider"></li>
                           <li class="dropdown-submenu pull-left period_year">
                              <a href="#" tabindex="-1"><?php echo _l('lead_manager_period_year'); ?></a>
                              <ul class="dropdown-menu dropdown-menu-left">
                                <?php foreach($years as $year) { ?>
                                 <li>
                                    <a href="#" data-cview="period_year_<?php echo $year['year']; ?>" onclick="dt_custom_view('<?php echo $year['year']; ?>','.table-lead-managerd','period_year_<?php echo $year['year']; ?>'); return false;"><?php echo $year['year']; ?></a>
                                 </li>
                              <?php } ?>
                           </ul>
                        </li>
                        <div class="clearfix"></div>
                        <li class="divider"></li>
                        <li class="dropdown-submenu pull-left period_month">
                           <a href="#" tabindex="-1"><?php echo _l('lead_manager_period_month'); ?></a>
                           <ul class="dropdown-menu dropdown-menu-left">
                              <?php for ($m = 1; $m <= 12; $m++) { ?>
                               <li><a href="#" data-cview="period_month_<?php echo $m; ?>" onclick="dt_custom_view(<?php echo $m; ?>,'.table-lead-managerd','period_month_<?php echo $m; ?>'); return false;"><?php echo _l(date('F', mktime(0, 0, 0, $m, 1))); ?></a></li>
                            <?php } ?>
                         </ul>
                      </li>
                   </ul>
                </div>
             </div>
          </div>
       </div>
       <div class="clearfix"></div>
       <hr class="hr-panel-heading" />
       <div class="tab-content">
         <div class="row" id="lead_manager-table">
            <div class="col-md-12">
                <a href="#" data-toggle="modal" data-table=".table-lead-managerd" data-target="#leads_bulk_actions" class="hide bulk-actions-btn table-btn"><?php echo _l('bulk_actions'); ?></a>
              <?php  if (has_permission('lead_manager', '', 'can_sms')) { ?>
               <a href="#" data-toggle="modal" data-table=".table-lead-managerd" data-target="#lead_manager_bulk_actions" class="hide bulk-actions-btn table-btn"><?php echo _l('lead_manager_bulk_sms'); ?></a>
            <?php } ?>
            <div class="row">
                
              <div class="col-md-12">
                 <p class="bold"><?php echo _l('filter_by'); ?></p>
              </div>
              <?php if(has_permission('leads','','view')){ ?>
              <div class="col-md-3 leads-filter-column">
                 <?php echo render_select('view_assigned',$staffs,array('staffid',array('firstname','lastname')),'','',array('data-width'=>'100%','data-none-selected-text'=>_l('leads_dt_assigned')),array(),'no-mbot'); ?>
              </div>
              <?php } ?>
              <div class="col-md-3 leads-filter-column">
                 <?php
                    $selected = array();
                    if($this->input->get('status')) {
                     $selected[] = $this->input->get('status');
                    } else {
                     foreach($statuses as $key => $status) {
                       if($status['isdefault'] == 0) {
                         $selected[] = $status['id'];
                       } else {
                         $statuses[$key]['option_attributes'] = array('data-subtext'=>_l('leads_converted_to_client'));
                       }
                     }
                    }
                    echo '<div id="leads-filter-status">';
                    echo render_select('view_status[]',$statuses,array('id','name'),'',$selected,array('data-width'=>'100%','data-none-selected-text'=>_l('leads_all'),'multiple'=>true,'data-actions-box'=>true),array(),'no-mbot','',false);
                    echo '</div>';
                    ?>
              </div>
              <div class="col-md-3 leads-filter-column">
                 <?php
                    echo render_select('view_source',$sources,array('id','name'),'','',array('data-width'=>'100%','data-none-selected-text'=>_l('leads_source')),array(),'no-mbot');
                    ?>
              </div>
              <div class="col-md-3 leads-filter-column">
                 <div class="select-placeholder">
                    <select name="custom_view" title="<?php echo _l('additional_filters'); ?>" id="custom_view" class="selectpicker" data-width="100%">
                       <option value=""></option>
                       <option value="lost"><?php echo _l('lead_lost'); ?></option>
                       <option value="junk"><?php echo _l('lead_junk'); ?></option>
                       <option value="public"><?php echo _l('lead_public'); ?></option>
                       <option value="contacted_today"><?php echo _l('lead_add_edit_contacted_today'); ?></option>
                       <option value="created_today"><?php echo _l('created_today'); ?></option>
                       <?php if(has_permission('leads','','edit')){ ?>
                       <option value="not_assigned"><?php echo _l('leads_not_assigned'); ?></option>
                       <?php } ?>
                       <?php if(isset($consent_purposes)) { ?>
                         <optgroup label="<?php echo _l('gdpr_consent'); ?>">
                             <?php foreach($consent_purposes as $purpose) { ?>
                             <option value="consent_<?php echo $purpose['id']; ?>">
                                <?php echo $purpose['name']; ?>
                             </option>
                             <?php } ?>
                         </optgroup>
                       <?php } ?>
                    </select>
                 </div>
              </div>
           </div>
           <div class="clearfix"></div>
           <hr class="hr-panel-heading" />
            <div class="modal fade bulk_actions" id="lead_manager_bulk_actions" tabindex="-1" role="dialog">
               <div class="modal-dialog" role="document">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><?php echo _l('lead_manager_bulk_sms'); ?></h4>
                     </div>
                     <div class="modal-body">
                        <?= render_textarea('bulk_message_content',_l('lead_manager_message_data'),'',['required'=>'required']); ?>
                     </div>
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                        <a href="#" class="btn btn-info" onclick="lead_manager_bulk_sms_actions(this); return false;"><?php echo _l('confirm'); ?></a>
                     </div>
                  </div>
                  <!-- /.modal-content -->
               </div>
               <!-- /.modal-dialog -->
            </div>
            
            <div class="modal fade bulk_actionss" id="leads_bulk_actions" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
                 <div class="modal-content">
                    <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                    </div>
                    <div class="modal-body">
                       <?php if(has_permission('leads','','delete')){ ?>
                       <div class="checkbox checkbox-danger">
                          <input type="checkbox" name="mass_delete" id="mass_delete">
                          <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                       </div>
                       <hr class="mass_delete_separator" />
                       <?php } ?>
                       <div id="bulk_change">
                       <div class="form-group">
                              <div class="checkbox checkbox-primary checkbox-inline">
                                <input type="checkbox" name="leads_bulk_mark_lost" id="leads_bulk_mark_lost" value="1">
                                <label for="leads_bulk_mark_lost">
                                <?php echo _l('lead_mark_as_lost'); ?>
                                </label>
                             </div>
                         </div>
                          <?php echo render_select('move_to_status_leads_bulk',$statuses,array('id','name'),'ticket_single_change_status'); ?>
                          <?php
                             echo render_select('move_to_source_leads_bulk',$sources,array('id','name'),'lead_source');
                             echo render_datetime_input('leads_bulk_last_contact','leads_dt_last_contact');
                             echo render_select('assign_to_leads_bulk',$staffs,array('staffid',array('firstname','lastname')),'leads_dt_assigned');
                             ?>
                          <div class="form-group">
                             <?php echo '<p><b><i class="fa fa-tag" aria-hidden="true"></i> ' . _l('tags') . ':</b></p>'; ?>
                             <input type="text" class="tagsinput" id="tags_bulk" name="tags_bulk" value="" data-role="tagsinput">
                          </div>
                          <hr />
                          <div class="form-group no-mbot">
                             <div class="radio radio-primary radio-inline">
                                <input type="radio" name="leads_bulk_visibility" id="leads_bulk_public" value="public">
                                <label for="leads_bulk_public">
                                <?php echo _l('lead_public'); ?>
                                </label>
                             </div>
                             <div class="radio radio-primary radio-inline">
                                <input type="radio" name="leads_bulk_visibility" id="leads_bulk_private" value="private">
                                <label for="leads_bulk_private">
                                <?php echo _l('private'); ?>
                                </label>
                             </div>
                          </div>
                       </div>
                    </div>
                    <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                       <a href="#" class="btn btn-info" onclick="leads_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
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
             '<span class="hide"> - </span><div class="checkbox mass_select_all_lm_wrap"><input type="checkbox" id="mass_select_all_lm" data-to-table="lead_manager"><label></label></div>',
             );
            $_table_data[] = array(
             'name'=>_l('leads_dt_datecreated'),
             'th_attrs'=>array('class'=>'date-created toggleable','id'=>'th-date-created')
          );
             $_table_data[] = array(
               'name'=>_l('leads_dt_name'),
               'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-name')
            );
          $_table_data[] =   array(
               'name'=>_l('leads_dt_email'),
               'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-email')
              );
            if(is_gdpr() && get_option('gdpr_enable_consent_for_leads') == '1') {
             $_table_data[] = array(
               'name'=>_l('gdpr_consent') .' ('._l('gdpr_short').')',
               'th_attrs'=>array('id'=>'th-consent', 'class'=>'not-export')
            );
          }
          $_table_data[] =  array(
           'name'=>_l('leads_dt_phonenumber'),
           'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-phone')
        );
          $_table_data[] =   array(
           'name'=>_l('lead_manager_dt_connect'),
           'th_attrs'=>array('class'=>'toggleable not-export', 'id'=>'th-connect')
        );
          $_table_data[] = array(
           'name'=>'Remarks',
           'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-company')
        );
        $_table_data[] =  array(
         'name'=>_l('leads_dt_lead_value'),
         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-lead-value')
        );
      $_table_data[] =  array(
       'name'=>_l('tags'),
       'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-tags')
      );
          
          $_table_data[] = array(
           'name'=>_l('leads_dt_assigned'),
           'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-assigned')
        );
          $_table_data[] = array(
           'name'=>_l('leads_dt_status'),
           'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-status')
        );
        //   $_table_data[] = array(
        //   'name'=>_l('leads_dt_last_contact'),
        //   'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-last-contact')
        // );
        $_table_data[] = array(
           'name'=>_l('leads_source'),
           'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-source')
          );
          $_table_data[] = array(
           'name'=>_l('lead_manger_dt_follow_up'),
           'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-follow_up')
        );
        
          
        //   $_table_data[] = array(
        //      'name'=>_l('lm_remark_th'),
        //      'th_attrs'=>array('class'=>'toggleable not-export','id'=>'th-remark')
        //   );
        //   $_table_data[] = array(
        //      'name'=>_l('lm_last_remark_th'),
        //      'th_attrs'=>array('class'=>'toggleable','id'=>'th-remark-last')
        //   );
          foreach($_table_data as $_t){
           array_push($table_data,$_t);
        }
        $custom_fields = get_custom_fields('lead_manager',array('show_on_table'=>1));
        foreach($custom_fields as $field){
         array_push($table_data,$field['name']);
      }
      $table_data = hooks()->apply_filters('leads_table_columns', $table_data);
      render_datatable($table_data,'lead-managerd',
         array('customizable-table'),
         array(
           'id'=>'table-lead-managerd',
           'data-last-order-identifier'=>'lead_manager',
           'data-default-order'=>get_table_last_order('lead_manager'),
        )); ?>
     </div>
  </div>
</div>
</div>
</div>
</div>
</div> 
</div>
</div>
<div class="modal fade lead-modal" id="lead-manager-activity-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
 <div class="modal-dialog <?php echo get_option('lead_modal_class'); ?>">
  <div class="modal-content data">

  </div>
</div> 
</div>
<div class="modal fade" id="lead-manager-sms-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog">
  <div class="modal-content data">

  </div>
</div>
</div>
<div class="modal fade" id="lead-manager-zoom-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog">
  <div class="modal-content data">

  </div>
</div>
</div>
<div class="modal fade" id="lead-manager-meeting-remark" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog">
  <div class="modal-content data">

  </div>
</div>
</div>
<div class="modal fade lead-modal" id="lead-manager-meeting-show_remark" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog">
  <div class="modal-content data">

  </div>
</div>
</div>
<div class="modal fade" id="lead-manager-mail-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 
</div>
<?php init_tail(); ?>
<script>
    var newArray = [];
    var array = [];
  window.fbAsyncInit = function() {
    FB.init({
      appId            : '<?php echo $facebook->appId;?>',
      xfbml            : true,
      version          : 'v18.0'
    });
    
    FB.api(
      '/<?php echo $facebook->facebookPageId;?>/leadgen_forms',
      'GET',
      {  "access_token" : "<?php echo $facebook->appAccessToken;?>" },
      function(response) {
         
          for(var i=0;i<response.data.length;i++)
          {
              newArray.push(response.data[i].id);
                
          }
          
          if(newArray.length > 0)
        {
            
            for(var i=0;i<newArray.length;i++)
            {
                
                FB.api(
                  '/'+newArray[i]+'/leads',
                  'GET',
                  {"level":"ads","fields":"campaign_id,campaign_name,adset_id,adset_name,ad_id,ad_name,created_time,id,field_data","limit":"1000","access_token" : "<?php echo $facebook->appAccessToken;?>"},
                 
                  function(response) {
                     
                      //if(response.data.length > 0){
                         // console.log(response.data);
                          for(var i=0;i<response.data.length;i++)
                          {
                              
                              
                              if(response.data[i].ad_name){
                                  array.push(response.data[i]);
                              $.post(admin_url+'leads/facebookleads', {
                                    array:JSON.stringify(response.data[i]),
                                    
                                })
                                .done(function(response){
                                    
                                  
                                });
                              }
                               $("#leadcount").html(array.length);
                             // console.log(array.length);
                          }
                      //}
                      
                   
                     
                  }
                );
                
                
            }
            
             console.log(array.length);
        
            
        }
        
          
          
    }
    );
    
    
    
    
    
  };
  
  
    
  //console.log(array);
</script>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
<script type="text/javascript">
   $.each($('._hidden_inputs._filters input'),function(){
    LeadsManagerServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
 });
   var date_t_value = (new Date()).toISOString().split('T')[0];
   $('#date_t').on('change',function(){
    date_t_value = $('#date_t').val()
 });
   $('#date_f,#date_t').on('change',function(){
    $('input[name="period_from"]').val($('#date_f').val());
    $('input[name="period_to"]').val(date_t_value);
    $('.table-lead-managerd').DataTable().ajax.reload(); 
 });
   //lead_manager_table_api.column(12).visible(false);
</script>
</body>
</html>