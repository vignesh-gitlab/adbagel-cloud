<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'created',
    'campaign',
    'details',
    'quantity',
    'start_date',
    'end_date',
    'bill_rate',
    'yes_no'
    ];

$sIndexColumn = 'id';
$sTable       = db_prefix().'campaign_manager';
$where        = [];
$is_admin     = is_admin();



if (!has_permission('campaign', '', 'view')) {
    array_push($where, 'AND (addedfrom =' . get_staff_user_id() . ')');
}

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, [
    'id',
    
    ]);

$output   = $result['output'];
$rResult  = $result['rResult'];
$is_admin = is_admin();
foreach ($rResult as $aRow) {
    $row = [];
    $row[] = '<div class="checkbox"><input type="checkbox" value="' . $aRow['id'] . '"><label></label></div>';
    $row[] = '<span data-toggle="tooltip" data-title="' . _dt($aRow['created']) . '" class="text-has-action is-date">' . time_ago($aRow['created']) . '</span>';
    $row[] = $aRow['campaign'];
    $row[] = $aRow['details'];
    $row[] = $aRow['quantity'];
    if($aRow['start_date']=="1970-01-01")
    {
        $row[] = '-';
    }else
    {
        $row[] = date("d-m-Y",strtotime($aRow['start_date']));
    }
    
    if($aRow['end_date']=="1970-01-01")
    {
        $row[] = '-';
    }else
    {
        $row[] = date("d-m-Y",strtotime($aRow['end_date']));
    }
    
    
    $row[] = $aRow['bill_rate'];
    $row[] = $aRow['yes_no'];
    $outputAction = '';
    if (has_permission('campaign', '', 'edit')) {
        $outputAction .= '<a onclick="edit_campaign(this,' . $aRow['id'] . ');return false;" data-campaign="'.$aRow['campaign'].'" data-details="'.$aRow['details'].'" data-quantity="'.$aRow['quantity'].'" data-start_date="'.date("d-m-Y",strtotime($aRow['start_date'])).'" data-end_date="'.date("d-m-Y",strtotime($aRow['end_date'])).'" data-bill_rate="'.$aRow['bill_rate'].'" data-yes_no="'.$aRow['yes_no'].'"><i class="fa fa-edit" aria-hidden="true" style="color:green;font-size:17px;margin-right:10px;cursor:pointer"></i></a>';
    }
    if (has_permission('campaign', '', 'delete')) {
    $outputAction .='<a href="' . admin_url('leads/campaignDelete/' . $aRow['id']) . '"  title="Delete"><i class="fa fa-trash" aria-hidden="true" style="color:red;font-size:17px;margin-right:10px"></i></a>';
    }
    $row[] = $outputAction;

    $row['DT_RowClass'] = 'has-row-options';

    if (!$aRow['is_dismissed'] && $aRow['showtostaff'] == '1') {
        $row['DT_RowClass'] .= ' alert-info';
    }

    $output['aaData'][] = $row;
}
