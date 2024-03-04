<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/import/App_import.php');

class Import_campaign extends App_import
{
    protected $notImportableFields = [];

    private $countryFields = ['country', 'billing_country', 'shipping_country'];

    protected $requiredFields = ['firstname', 'lastname', 'email'];

    public function __construct()
    {
        $this->notImportableFields = hooks()->apply_filters('not_importable_campaign_fields', ['id', 'created', 'addedfrom']);

        
        parent::__construct();
    }

    public function perform()
    {
        $this->initialize();

        $databaseFields      = $this->getImportableDatabaseFields();
        
        $totalDatabaseFields = count($databaseFields);

        foreach ($this->getRows() as $rowNumber => $row) {
            $insert    = [];
            $duplicate = false;
            
            for ($i = 0; $i < $totalDatabaseFields; $i++) {
                if (!isset($row[$i])) {
                    continue;
                }

                $row[$i] = $this->checkNullValueAddedByUser($row[$i]);
                if ($databaseFields[$i] == 'start_date' || $databaseFields[$i] == 'end_date') {
                    $row[$i] = date("Y-m-d",strtotime($row[$i]));
                }
               
                $insert[$databaseFields[$i]] = $row[$i];
            }

            if ($duplicate) {
                continue;
            }

            $insert = $this->trimInsertValues($insert);

            if (count($insert) > 0) {
                $this->incrementImported();

                $id = null;

                if (!$this->isSimulation()) {
                    $insert['created']           = date('Y-m-d H:i:s');
                    
                    $insert['addedfrom'] = get_staff_user_id();
                    
                    $id = $this->ci->db->insert(db_prefix().'campaign_manager',$insert, true);

                    
                } 

                $this->handleCustomFieldsInsert($id, $row, $i, $rowNumber, 'campaign');
            }

            if ($this->isSimulation() && $rowNumber >= $this->maxSimulationRows) {
                break;
            }
        }
    }

    public function formatFieldNameForHeading($field)
    {
        if (strtolower($field) == 'title') {
            return 'Position';
        }

        return parent::formatFieldNameForHeading($field);
    }

    protected function email_formatSampleData()
    {
        return uniqid() . '@example.com';
    }

    protected function failureRedirectURL()
    {
        return admin_url('clients/import');
    }

    protected function afterSampleTableHeadingText($field)
    {
        $contactFields = [
            'firstname', 'lastname', 'email', 'contact_phonenumber', 'title',
        ];

        if (in_array($field, $contactFields)) {
            return '<br /><span class="text-info">' . _l('import_contact_field') . '</span>';
        }
    }

    private function insertCustomerGroups($groups, $customer_id)
    {
        foreach ($groups as $group) {
            $this->ci->db->insert(db_prefix() . 'customer_groups', [
                                                    'customer_id' => $customer_id,
                                                    'groupid'     => $group,
                                                ]);
        }
    }

    private function shouldAddContactUnderCustomer($data)
    {
        return (isset($data['company']) && $data['company'] != '' && $data['company'] != '/')
        && (total_rows(db_prefix() . 'clients', ['company' => $data['company']]) === 1);
    }

    private function addContactUnderCustomer($data)
    {
        $contactFields = $this->getContactFields();
        $this->ci->db->where('company', $data['company']);

        $existingCompany = $this->ci->db->get(db_prefix() . 'clients')->row();
        $tmpInsert       = [];

        foreach ($data as $key => $val) {
            foreach ($contactFields as $tmpContactField) {
                if (isset($data[$tmpContactField])) {
                    $tmpInsert[$tmpContactField] = $data[$tmpContactField];
                }
            }
        }
        $tmpInsert['donotsendwelcomeemail'] = true;

        if (isset($data['contact_phonenumber'])) {
            $tmpInsert['phonenumber'] = $data['contact_phonenumber'];
        }

        $this->ci->clients_model->add_contact($tmpInsert, $existingCompany->userid, true);
    }

    private function getContactFields()
    {
        return $this->ci->db->list_fields(db_prefix() . 'contacts');
    }

    private function isDuplicateContact($email)
    {
        return total_rows(db_prefix() . 'contacts', ['email' => $email]);
    }

    private function formatValuesForSimulation($values)
    {
        // ATM only country fields
        foreach ($this->countryFields as $country_field) {
            if (array_key_exists($country_field, $values)) {
                if (!empty($values[$country_field]) && is_numeric($values[$country_field])) {
                    $country = $this->getCountry(null, $values[$country_field]);
                    if ($country) {
                        $values[$country_field] = $country->short_name;
                    }
                }
            }
        }

        return $values;
    }

    private function getCountry($search = null, $id = null)
    {
        if ($search) {
            $searchSlug = slug_it($search);

            if (empty($search)) {
                return null;
            }

            if ($country = $this->ci->app_object_cache->get('import-country-search-' . $searchSlug)) {
                return $country;
            }

            $this->ci->db->where('iso2', $search);
            $this->ci->db->or_where('short_name', $search);
            $this->ci->db->or_where('long_name', $search);
        } else {
            if (empty($id)) {
                return null;
            }

            if ($country = $this->ci->app_object_cache->get('import-country-id-' . $id)) {
                return $country;
            }

            $this->ci->db->where('country_id', $id);
        }

        $country = $this->ci->db->get(db_prefix() . 'countries')->row();

        if ($search) {
            $this->ci->app_object_cache->add('import-country-search-' . $searchSlug, $country);
        } else {
            $this->ci->app_object_cache->add('import-country-id-' . $id, $country);
        }

        return $country;
    }

    private function countryValue($value)
    {
        if ($value != '') {
            if (!is_numeric($value)) {
                $country = $this->getCountry($value);
                $value   = $country ? $country->country_id : 0;
            }
        } else {
            $value = 0;
        }

        return $value;
    }
}
