<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Compania;
use App\Models\BusinessName;
use App\Models\Location;
use App\Models\Department;
use App\Models\Employee;
use App\Models\ServiceProviderModule;
use App\Models\SyncLog;
use App\Http\Requests\CompaniaCreateRequest;
use App\Http\Requests\CompaniaUpdateRequest;
use App\Http\Requests\BusinessNameCreateRequest;
use App\Http\Requests\BusinessNameUpdateRequest;
use App\Http\Requests\LocationCreateRequest;
use App\Http\Requests\LocationUpdateRequest;
use App\Http\Requests\DepartmentCreateRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Http\Requests\EmployeeCreateRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use Illuminate\Support\Facades\Validator;

class FortiaController extends Controller
{
    public function getToken(Request $request) {

        $http_response_status_codes = [
            '200' => 'OK',
            '201' => 'Created',
            '404' => 'Bad Request',
        ];

        $tmp = DB::table('service_providers_tokens')
                        ->select('token')
                        ->where('service_provider_id', $request['service_provider_id'])
                        ->where(DB::raw('TIMESTAMPDIFF(MINUTE, created_at, now())'), '<=', 30)
                        ->limit(1)
                        ->get();

        $token = null;
        $status = null;
        $success = false;

        if($tmp->count()) {
            $token = $tmp[0]->token;
            $status = 200;
            $success = true;
        }
        else {
            
            $payload = json_encode([
                "Username" => env("FORTIA_USERNAME"),
                "Password" => env("FORTIA_PASSWORD"),
            ]);
    
            $ch = curl_init(env("FORTIA_API_AUTH"));
    
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    
            $response = curl_exec($ch);
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
            curl_close($ch);

            $token = str_replace('"','', $response);

            if(!$token) $token = null;

            if($status == 200) {

                DB::table('service_providers_tokens')
                        ->insert([
                            'service_provider_id' => $request['service_provider_id'],
                            'token' => $token,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
            }
        }

        return response()->json([
            'token' => $token,
            'status' => $status,
            'success' => $success,
            // 'message' => $http_response_status_codes[$status],
        ]);
    }

    public function syncCompany(Request $request) {
        
        try {

            $response = [
                'total' => 0,
                'created' => 0,
                'updated' => 0,
                'unchanged' => 0,
                'success' => false,
                'sync_log_id' => null,
            ];

            $json = $this->syncData($request);

            if($json) {
                
                $tmp = json_decode($json->content());

                $response['success'] = $tmp->success;
                
                if($tmp->success) {

                    $response['sync_log_id'] = $tmp->sync_log_id;

                    if(sizeof($tmp->data)) {

                        $response['total'] = sizeof($tmp->data);
                            
                        foreach($tmp->data as $row) {

                            $company = Compania::where('company_id', $row->company_id)->first();

                            if($company && $company->count()) {
                                $rick = new CompaniaUpdateRequest();
            
                                $rick->replace([
                                        'company_id' => $row->company_id,
                                        'company_name' => $row->company_name,
                                ]);

                                if($company->update($rick->all())) $response['updated']++;
                            }
                            else {

                                $rick = new CompaniaCreateRequest();
            
                                $rick->replace([
                                        'company_id' => $row->company_id,
                                        'company_name' => $row->company_name,
                                ]);
                                
                                Compania::create($rick->all());
                                $response['created']++;
                            }
                        }
        
                        SyncLog::where('id', $tmp->sync_log_id)->update(['finished_at' => date('Y-m-d h:i:s')]);

                    }
                }
                else $response = $tmp;
            }

            return response()->json($response); 


            /* // VERIFICAMOS SI EXISTE ACTUALIZACIÓN EN CURSO
            $module = 'company';

            $tmp = json_decode(($this->activeSyncLog($request))->content(), true);

            if($tmp && $tmp['id']) {
                
                return response()->json([
                    'success' => false,
                    'message' => 'Existe una actualización en curso','data' => $tmp
                ]);
            }

            // SE CREA EL LOG DE LA ACTUALIZACIÓN DE MÓDULO
            $sys_log = SyncLog::create([
                'service_provider_id' => $request['service_provider_id'],
                'module' => $module,
                'user_id' => auth()->user()->id,
            ]);

            // BUSCAMOS EL ENDPOINT DEL MÓDULO
            $service_provider_module = ServiceProviderModule::where('module', $module)
                                                    ->select('url')
                                                    ->where('service_provider_id', $request['service_provider_id'])
                                                    ->first();

            if(!$service_provider_module) {
                return response()->json([
                    'success' => false,
                    'message' => 'No existe el endpoint para el módulo `'.$module.'`'
                ]);
            }

            $tmp = $this->getToken($request);

            $get_token = json_decode($tmp->content(), true);

            if($get_token['status'] == 200) {

                $response = [
                    'sync_status' => null,
                    'total' => 0,
                    'created' => 0,
                    'updated' => 0,
                    'unchanged' => 0,
                ];

                $authorization = "Authorization: Bearer ".$get_token['token'];
                $ch = curl_init($service_provider_module->url);

                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $authorization));
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 240);
                curl_setopt($ch, CURLOPT_TIMEOUT, 240);

                $curl_response = curl_exec($ch);
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                $data = json_decode($curl_response);

                if($data && sizeof($data)) {

                    $response['total'] = sizeof($data);
                        
                    foreach($data as $row) {

                        $company = Compania::where('company_id', $row->company_id)->first();

                        if($company && $company->count()) {
                            $rick = new CompaniaUpdateRequest();
        
                            $rick->replace([
                                    'company_id' => $row->company_id,
                                    'company_name' => $row->company_name,
                            ]);

                            if($company->update($rick->all())) $response['updated']++;
                        }
                        else {

                            $rick = new CompaniaCreateRequest();
        
                            $rick->replace([
                                    'company_id' => $row->company_id,
                                    'company_name' => $row->company_name,
                            ]);
                            
                            Compania::create($rick->all());
                            $response['created']++;
                        }
                    }
                }

                $sys_log->update(['status' => 2]);
            }
            else {
                unset($response['token']);
            }
            */
        }
        catch (Exception $e) {
            return response()->json([
                'error' => $e
            ]);
        }
    }

    public function syncBusinessName(Request $request) {
        
        try {

            $response = [
                'total' => 0,
                'created' => 0,
                'updated' => 0,
                'unchanged' => 0,
                'success' => false,
                'sync_log_id' => null,

            ];

            $json = $this->syncData($request);

            if($json) {

                $tmp = json_decode($json->content());

                $response['success'] = $tmp->success;
                
                if($tmp->success) {
                    
                    $response['sync_log_id'] = $tmp->sync_log_id;

                    if(sizeof($tmp->data)) {

                        $response['total'] = sizeof($tmp->data);
                            
                        foreach($tmp->data as $row) {

                            $business_name = BusinessName::where('business_name_id', $row->business_name_id)->first();

                            $params = [
                                'company_id' => $row->company_id,
                                'company_name' => $row->company_name,
                                'business_name_id' => $row->business_name_id,
                                'business_name' => $row->business_name,
                                'company_id' => $row->company_id,
                                'company_name' => $row->company_name,
                                'short_name' => $row->short_name,
                                'rfc' => $row->rfc,
                                'line_of_business' => $row->line_of_business,
                                'phone_number' => $row->phone_number,
                                'country' => $row->country,
                                'country_id' => $row->country_id,
                                'state' => $row->state,
                                'city' => $row->city,
                                'municipality' => $row->municipality,
                                'neighborhood' => $row->neighborhood,
                                'street' => $row->street,
                                'interior_number' => $row->interior_number,
                                'exterior_number' => $row->exterior_number,
                                'between_street' => $row->between_street,
                                'and_street' => $row->and_street,
                                'zip_code' => $row->zip_code,
                                'representative_name' => $row->representative_name,
                                'representative_rfc' => $row->representative_rfc,
                                'representative_curp' => $row->representative_curp,
                                'ptu_decimals_for_factors' => $row->ptu_decimals_for_factors,
                                'ptu_days_factor' => $row->ptu_days_factor,
                                'ptu_income_limit' => $row->ptu_income_limit,
                                'ptu_salary_factor' => $row->ptu_salary_factor,
                                'payment_policy' => $row->payment_policy,
                                'accounting_apportionment_applies' => $row->accounting_apportionment_applies,
                                'accounting_segment' => $row->accounting_segment

                            ];

                            if($business_name && $business_name->count()) {

                                $rick = new BusinessNameUpdateRequest();
                                $rick->replace($params);

                                if($business_name->update($rick->all())) $response['updated']++;
                            }
                            else {

                                $rick = new BusinessNameCreateRequest();
                                $rick->replace($params);
                                
                                BusinessName::create($rick->all());
                                $response['created']++;
                            }
                        }
        
                        SyncLog::where('id', $tmp->sync_log_id)->update(['status' => 2]);
                    }
                }
                else $response = $tmp;
            }

            return response()->json($response); 

            /* // VERIFICAMOS SI EXISTE ACTUALIZACIÓN EN CURSO
            $module = 'business_name';

            $tmp = json_decode(($this->activeSyncLog($request))->content(), true);

            if($tmp && $tmp['id']) {
                
                return response()->json([
                    'success' => false,
                    'message' => 'Existe una actualización en curso','data' => $tmp
                ]);
            }

            // SE CREA EL LOG DE LA ACTUALIZACIÓN DE MÓDULO
            $sys_log = SyncLog::create([
                'service_provider_id' => $request['service_provider_id'],
                'module' => $module,
                'user_id' => auth()->user()->id,
            ]);

            // BUSCAMOS EL ENDPOINT DEL MÓDULO
            $service_provider_module = ServiceProviderModule::where('module', $module)
                                                    ->select('url')
                                                    ->where('service_provider_id', $request['service_provider_id'])
                                                    ->first();

            if(!$service_provider_module) {
                return response()->json([
                    'success' => false,
                    'message' => 'No existe el endpoint para el módulo `'.$module.'`'
                ]);
            }

            $tmp = $this->getToken($request);

            $get_token = json_decode($tmp->content(), true);

            if($get_token['status'] == 200) {

                $response = [
                    'sync_status' => null,
                    'total' => 0,
                    'created' => 0,
                    'updated' => 0,
                    'unchanged' => 0,
                ];

                $authorization = "Authorization: Bearer ".$get_token['token'];
                $ch = curl_init($service_provider_module->url);

                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $authorization));
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
                curl_setopt($ch, CURLOPT_TIMEOUT, 120);

                $curl_response = curl_exec($ch);
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                $data = json_decode($curl_response);

                if($data && sizeof($data)) {

                    $response['total'] = sizeof($data);
                        
                    foreach($data as $row) {

                        $business_name = BusinessName::where('business_name_id', $row->business_name_id)->first();

                        $params = [
                            'company_id' => $row->company_id,
                            'company_name' => $row->company_name,
                            'business_name_id' => $row->business_name_id,
                            'business_name' => $row->business_name,
                            'company_id' => $row->company_id,
                            'company_name' => $row->company_name,
                            'short_name' => $row->short_name,
                            'rfc' => $row->rfc,
                            'line_of_business' => $row->line_of_business,
                            'phone_number' => $row->phone_number,
                            'country' => $row->country,
                            'country_id' => $row->country_id,
                            'state' => $row->state,
                            'city' => $row->city,
                            'municipality' => $row->municipality,
                            'neighborhood' => $row->neighborhood,
                            'street' => $row->street,
                            'interior_number' => $row->interior_number,
                            'exterior_number' => $row->exterior_number,
                            'between_street' => $row->between_street,
                            'and_street' => $row->and_street,
                            'zip_code' => $row->zip_code,
                            'representative_name' => $row->representative_name,
                            'representative_rfc' => $row->representative_rfc,
                            'representative_curp' => $row->representative_curp,
                            'ptu_decimals_for_factors' => $row->ptu_decimals_for_factors,
                            'ptu_days_factor' => $row->ptu_days_factor,
                            'ptu_income_limit' => $row->ptu_income_limit,
                            'ptu_salary_factor' => $row->ptu_salary_factor,
                            'payment_policy' => $row->payment_policy,
                            'accounting_apportionment_applies' => $row->accounting_apportionment_applies,
                            'accounting_segment' => $row->accounting_segment

                        ];

                        if($business_name && $business_name->count()) {

                            $rick = new BusinessNameUpdateRequest();
                            $rick->replace($params);

                            if($business_name->update($rick->all())) $response['updated']++;
                        }
                        else {

                            $rick = new BusinessNameCreateRequest();
                            $rick->replace($params);
                            
                            BusinessName::create($rick->all());
                            $response['created']++;
                        }
                    }
                }
                
                $sys_log->update(['status' => 2]);
            }
            else {
                unset($response['token']);
            }

            return response()->json($response); */
        }
        catch (Exception $e) {
            return response()->json([
                'error' => $e
            ]);
        }
    }

    public function syncLocation(Request $request) {
        
        try {

            $response = [
                'sync_status' => null,
                'total' => 0,
                'created' => 0,
                'updated' => 0,
                'unchanged' => 0,
                'success' => false,
                'sync_log_id' => null,
            ];

            $json = $this->syncData($request);

            if($json) {

                $tmp = json_decode($json->content());

                $response['success'] = $tmp->success;
                
                if($tmp->success) {
                    
                    $response['sync_log_id'] = $tmp->sync_log_id;

                    if(sizeof($tmp->data)) {

                        $response['total'] = sizeof($tmp->data);
                            
                        foreach($tmp->data as $row) {

                            $business_name = Location::where('location_id', $row->location_id)->first();

                            $params = [
                                'company_id' => $row->company_id,
                                'company_name' => $row->company_name,
                                'location_id' => $row->location_id,
                                'alphanumeric_id_1' => $row->alphanumeric_id_1,
                                'alphanumeric_id_2' => $row->alphanumeric_id_2,
                                'alphanumeric_id_3' => $row->alphanumeric_id_3,
                                'location_name' => $row->location_name,
                                'alternative_location_name' => $row->alternative_location_name,
                                'wage_zone_id' => $row->wage_zone_id,
                                'state_register' => $row->state_register,
                                'state_tax' => $row->state_tax,
                                'additional_tax' => $row->additional_tax,
                                'accont_segment' => $row->accont_segment,
                                'use_tax_table' => $row->use_tax_table,
                                'current_tax_table_id' => $row->current_tax_table_id,
                                'current_tax_table_name' => $row->current_tax_table_name,
                                'phone' => $row->phone,
                                'zone' => $row->zone,
                                'establishment_class_id' => $row->establishment_class_id,
                                'establishment_class_name' => $row->establishment_class_name,
                                'address_street' => $row->address_street,
                                'address_neighborhood' => $row->address_neighborhood,
                                'address_city' => $row->address_city,
                                'address_state_id' => $row->address_state_id,
                                'address_state_name' => $row->address_state_name,
                                'address_postal_code' => $row->address_postal_code,
                                'representative_id' => $row->representative_id,
                                'representative_name' => $row->representative_name,
                                'representative_phone' => $row->representative_phone,
                                'representative_phone_extension' => $row->representative_phone_extension,
                                'representative_rfc' => $row->representative_rfc,
                                'representative_email' => $row->representative_email,
                                'representative_curp' => $row->representative_curp,
                                'representative_additional_address' => $row->representative_additional_address

                            ];

                            if($business_name && $business_name->count()) {

                                $rick = new LocationUpdateRequest();
            
                                $rick->replace($params);

                                if($business_name->update($rick->all())) $response['updated']++;
                            }
                            else {

                                $rick = new LocationCreateRequest();
            
                                $rick->replace($params);
                                
                                Location::create($rick->all());
                                $response['created']++;
                            }
                        }
        
                        SyncLog::where('id', $tmp->sync_log_id)->update(['status' => 2]);
                    }
                }
                else $response = $tmp;
            }

            return response()->json($response); 

            /* 
            // VERIFICAMOS SI EXISTE ACTUALIZACIÓN EN CURSO
            $module = 'location';

            $tmp = json_decode(($this->activeSyncLog($request))->content(), true);

            if($tmp && $tmp['id']) {
                
                return response()->json([
                    'success' => false,
                    'message' => 'Existe una actualización en curso','data' => $tmp
                ]);
            }

            // SE CREA EL LOG DE LA ACTUALIZACIÓN DE MÓDULO
            $sys_log = SyncLog::create([
                'service_provider_id' => $request['service_provider_id'],
                'module' => $module,
                'user_id' => auth()->user()->id,
            ]);

            // BUSCAMOS EL ENDPOINT DEL MÓDULO
            $service_provider_module = ServiceProviderModule::where('module', $module)
                                                    ->select('url')
                                                    ->where('service_provider_id', $request['service_provider_id'])
                                                    ->first();

            if(!$service_provider_module) {
                return response()->json([
                    'success' => false,
                    'message' => 'No existe el endpoint para el módulo `'.$module.'`'
                ]);
            }

            $tmp = $this->getToken($request);
            $get_token = json_decode($tmp->content(), true);

            if($get_token['status'] == 200) {

                $response = [
                    'sync_status' => null,
                    'total' => 0,
                    'created' => 0,
                    'updated' => 0,
                    'unchanged' => 0,
                ];

                $authorization = "Authorization: Bearer ".$request['token'];
                $ch = curl_init($request['url']);

                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $authorization));
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
                curl_setopt($ch, CURLOPT_TIMEOUT, 120);

                $curl_response = curl_exec($ch);
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                $data = json_decode($curl_response);

                if($data && sizeof($data)) {

                    $response['total'] = sizeof($data);
                        
                    foreach($data as $row) {

                        $business_name = Location::where('location_id', $row->location_id)->first();

                        $params = [
                            'company_id' => $row->company_id,
                            'company_name' => $row->company_name,
                            'location_id' => $row->location_id,
                            'alphanumeric_id_1' => $row->alphanumeric_id_1,
                            'alphanumeric_id_2' => $row->alphanumeric_id_2,
                            'alphanumeric_id_3' => $row->alphanumeric_id_3,
                            'location_name' => $row->location_name,
                            'alternative_location_name' => $row->alternative_location_name,
                            'wage_zone_id' => $row->wage_zone_id,
                            'state_register' => $row->state_register,
                            'state_tax' => $row->state_tax,
                            'additional_tax' => $row->additional_tax,
                            'accont_segment' => $row->accont_segment,
                            'use_tax_table' => $row->use_tax_table,
                            'current_tax_table_id' => $row->current_tax_table_id,
                            'current_tax_table_name' => $row->current_tax_table_name,
                            'phone' => $row->phone,
                            'zone' => $row->zone,
                            'establishment_class_id' => $row->establishment_class_id,
                            'establishment_class_name' => $row->establishment_class_name,
                            'address_street' => $row->address_street,
                            'address_neighborhood' => $row->address_neighborhood,
                            'address_city' => $row->address_city,
                            'address_state_id' => $row->address_state_id,
                            'address_state_name' => $row->address_state_name,
                            'address_postal_code' => $row->address_postal_code,
                            'representative_id' => $row->representative_id,
                            'representative_name' => $row->representative_name,
                            'representative_phone' => $row->representative_phone,
                            'representative_phone_extension' => $row->representative_phone_extension,
                            'representative_rfc' => $row->representative_rfc,
                            'representative_email' => $row->representative_email,
                            'representative_curp' => $row->representative_curp,
                            'representative_additional_address' => $row->representative_additional_address

                        ];

                        if($business_name && $business_name->count()) {

                            $rick = new LocationUpdateRequest();
        
                            $rick->replace($params);

                            if($business_name->update($rick->all())) $response['updated']++;
                        }
                        else {

                            $rick = new LocationCreateRequest();
        
                            $rick->replace($params);
                            
                            Location::create($rick->all());
                            $response['created']++;
                        }
                    }
                }

                $sys_log->update(['status' => 2]);
            }
            else {
                unset($response['token']);
            }

            return response()->json($response); 
            */
        }
        catch (Exception $e) {
            return response()->json([
                'error' => $e
            ]);
        }
    }

    public function syncDepartment(Request $request) {
        
        try {

            $response = [
                'sync_status' => null,
                'total' => 0,
                'created' => 0,
                'updated' => 0,
                'unchanged' => 0,
                'success' => false,
                'sync_log_id' => null,
            ];

            $json = $this->syncData($request);

            if($json) {

                $tmp = json_decode($json->content());

                $response['success'] = $tmp->success;
                
                if($tmp->success) {

                    $response['sync_log_id'] = $tmp->sync_log_id;

                    if(sizeof($tmp->data)) {

                        $response['total'] = sizeof($tmp->data);
                            
                        foreach($tmp->data as $row) {

                            $department = Department::where('department_id', $row->department_id)->first();

                            $params = [
                                'company_id' => $row->company_id,
                                'company_name' => $row->company_name,
                                'department_id' => $row->department_id,
                                'department_name' => $row->department_name,
                                'alternative_department_name' => $row->alternative_department_name,
                                'business_unit_id' => $row->business_unit_id,
                                'business_unit_name' => $row->business_unit_name,
                                'area_id' => $row->area_id,
                                'area_name' => $row->area_name,
                                'reference_1' => $row->reference_1,
                                'reference_2' => $row->reference_2,
                                'reference_3' => $row->reference_3,
                                'reference_4' => $row->reference_4,
                                'alphanumeric_id_1' => $row->alphanumeric_id_1,
                                'alphanumeric_id_2' => $row->alphanumeric_id_2,
                                'alphanumeric_id_3' => $row->alphanumeric_id_3,
                                'applies_all_location' => $row->applies_all_location,
                                'locations' => json_encode($row->locations)
                            ];

                            if($department && $department->count()) {

                                $rick = new DepartmentUpdateRequest();
            
                                $rick->replace($params);

                                if($department->update($rick->all())) $response['updated']++;
                            }
                            else {

                                $rick = new DepartmentCreateRequest();
            
                                $rick->replace($params);
                                
                                Department::create($rick->all());
                                $response['created']++;
                            }
                        }
        
                        SyncLog::where('id', $tmp->sync_log_id)->update(['status' => 2]);
                    }
                }
                else $response = $tmp;
            }

            return response()->json($response); 

            /* 
            // VERIFICAMOS SI EXISTE ACTUALIZACIÓN EN CURSO
            $module = 'department';

            $tmp = json_decode(($this->activeSyncLog($request))->content(), true);

            if($tmp && $tmp['id']) {
                
                return response()->json([
                    'success' => false,
                    'message' => 'Existe una actualización en curso','data' => $tmp
                ]);
            }

            // SE CREA EL LOG DE LA ACTUALIZACIÓN DE MÓDULO
            $sys_log = SyncLog::create([
                'service_provider_id' => $request['service_provider_id'],
                'module' => $module,
                'user_id' => auth()->user()->id,
            ]);

            // BUSCAMOS EL ENDPOINT DEL MÓDULO
            $service_provider_module = ServiceProviderModule::where('module', $module)
                                                    ->select('url')
                                                    ->where('service_provider_id', $request['service_provider_id'])
                                                    ->first();

            if(!$service_provider_module) {
                return response()->json([
                    'success' => false,
                    'message' => 'No existe el endpoint para el módulo `'.$module.'`'
                ]);
            }

            $tmp = $this->getToken($request);

            $get_token = json_decode($tmp->content(), true);

            if($get_token['status'] == 200) {

                $response = [
                    'sync_status' => null,
                    'total' => 0,
                    'created' => 0,
                    'updated' => 0,
                    'unchanged' => 0,
                ];

                $authorization = "Authorization: Bearer ".$request['token'];
                $ch = curl_init($request['url']);

                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $authorization));
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
                curl_setopt($ch, CURLOPT_TIMEOUT, 120);

                $curl_response = curl_exec($ch);
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                $data = json_decode($curl_response);

                if($data && sizeof($data)) {

                    $response['total'] = sizeof($data);
                        
                    foreach($data as $row) {

                        $department = Department::where('department_id', $row->department_id)->first();

                        $params = [
                            'company_id' => $row->company_id,
                            'company_name' => $row->company_name,
                            'department_id' => $row->department_id,
                            'department_name' => $row->department_name,
                            'alternative_department_name' => $row->alternative_department_name,
                            'business_unit_id' => $row->business_unit_id,
                            'business_unit_name' => $row->business_unit_name,
                            'area_id' => $row->area_id,
                            'area_name' => $row->area_name,
                            'reference_1' => $row->reference_1,
                            'reference_2' => $row->reference_2,
                            'reference_3' => $row->reference_3,
                            'reference_4' => $row->reference_4,
                            'alphanumeric_id_1' => $row->alphanumeric_id_1,
                            'alphanumeric_id_2' => $row->alphanumeric_id_2,
                            'alphanumeric_id_3' => $row->alphanumeric_id_3,
                            'applies_all_location' => $row->applies_all_location,
                            'locations' => json_encode($row->locations)
                        ];

                        if($department && $department->count()) {

                            $rick = new DepartmentUpdateRequest();
        
                            $rick->replace($params);

                            if($department->update($rick->all())) $response['updated']++;
                        }
                        else {

                            $rick = new DepartmentCreateRequest();
        
                            $rick->replace($params);
                            
                            Department::create($rick->all());
                            $response['created']++;
                        }
                    }
                }

                $sys_log->update(['status' => 2]);
            }

            return response()->json($response); 
            */
        }
        catch (Exception $e) {
            return response()->json([
                'error' => $e
            ]);
        }
    }

    public function syncEmployee(Request $request) {
        
        try {

            $response = [
                'sync_status' => null,
                'total' => 0,
                'created' => 0,
                'updated' => 0,
                'unchanged' => 0,
                'success' => false,
                'sync_log_id' => null,
            ];

            $json = $this->syncData($request);

            if($json) {

                $tmp = json_decode($json->content());

                $response['success'] = $tmp->success;
                
                if($tmp->success) {
                    
                    $response['sync_log_id'] = $tmp->sync_log_id;

                    if(sizeof($tmp->data)) {
    
                        $response['total'] = sizeof($tmp->data);
                            
                        foreach($tmp->data as $row) {
    
                            $employee = Employee::where('employee_id', $row->employee_id)->first();
    
                            $params = [
                                'employee_id' => $row->employee_id,
                                'company_id' => $row->company_id,
                                'employee_name' => $row->employee_name,
                                'employee_last_name' => $row->employee_last_name,
                                'employee_second_last_name' => $row->employee_second_last_name,
                                'employee_status' => $row->employee_status,
                                'company_name' => $row->company_name,
                                'cost_center_id' => $row->cost_center_id,
                                'cost_center_name' => $row->cost_center_name,
                                'base_location_id' => $row->base_location_id,
                                'base_location_name' => $row->base_location_name,
                                'pay_location_id' => $row->pay_location_id,
                                'pay_location_name' => $row->pay_location_name,
                                'period_id' => $row->period_id,
                                'period_name' => $row->period_name,
                                'department_id' => $row->department_id,
                                'department_name' => $row->department_name,
                                'work_station_id' => $row->work_station_id,
                                'work_station_name' => $row->work_station_name,
                                'work_shift_id' => $row->work_shift_id,
                                'work_shift_name' => $row->work_shift_name,
                                'imss_regime_id' => $row->imss_regime_id,
                                'imss_regime_name' => $row->imss_regime_name,
                                'benefit_tabulator_id' => $row->benefit_tabulator_id,
                                'benefit_tabulator_name' => $row->benefit_tabulator_name,
                                'clasification_id' => $row->clasification_id,
                                'clasification_name' => $row->clasification_name,
                                'pay_method_id' => $row->pay_method_id,
                                'pay_method_name' => $row->pay_method_name,
                                'rfc' => $row->rfc,
                                'imss_number' => $row->imss_number,
                                'blood_type' => $row->blood_type,
                                'entry_date' => $row->entry_date,
                                'group_entry_date' => $row->group_entry_date,
                                'contract_end_date' => $row->contract_end_date,
                                'unionized' => $row->unionized,
                                'employee_email' => $row->employee_email,
                                'employee_personal_email' => $row->employee_personal_email,
                                'birth_place' => $row->birth_place,
                                'birth_date' => $row->birth_date,
                                'nationality' => $row->nationality,
                                'gender' => $row->gender,
                                'street' => $row->street,
                                'inner_number' => $row->inner_number,
                                'external_number' => $row->external_number,
                                'between_street' => $row->between_street,
                                'and_street' => $row->and_street,
                                'district' => $row->district,
                                'postal_code' => $row->postal_code,
                                'city' => $row->city,
                                'state' => $row->state,
                                'telephone_number' => $row->telephone_number,
                                'curp' => $row->curp,
                                'marital_status' => $row->marital_status,
                                'bank_id' => $row->bank_id,
                                'bank_name' => $row->bank_name,
                                'bank_account' => $row->bank_account,
                                'contract_type' => $row->contract_type,
                                'roll_id' => $row->roll_id,
                                'roll_name' => $row->roll_name,
                                'base_seniority' => $row->base_seniority,
                                'salary_type' => $row->salary_type,
                                'salary_tabulator_id' => $row->salary_tabulator_id,
                                'salary_tabulator_name' => $row->salary_tabulator_name,
                                'salary_tabulator_level' => $row->salary_tabulator_level,
                                'salary_tabulator_level_name' => $row->salary_tabulator_level_name,
                                'business_name_id' => $row->business_name_id,
                                'business_name' => $row->business_name,
                                'business_unit_id' => $row->business_unit_id,
                                'business_unit_name' => $row->business_unit_name,
                                'alphanumeric_id_1' => $row->alphanumeric_id_1,
                                'alphanumeric_id_2' => $row->alphanumeric_id_2,
                                'alphanumeric_id_3' => $row->alphanumeric_id_3,
                                'termination_date' => $row->termination_date,
                                'business_rfc' => $row->business_rfc,
                                'payment_frequency' => $row->payment_frequency,
                                'collaborator_id' => $row->collaborator_id,
                                'daily_wage' => $row->daily_wage,
                                'monthly_salary' => $row->monthly_salary,
                                'integrated_salary' => $row->integrated_salary,
                                'integrated_salary_without_limit' => $row->integrated_salary_without_limit,
                                'fixed_factor' => $row->fixed_factor,
                                'monthly_salary_avg' => $row->monthly_salary_avg,
                                'employee_picture' => $row->employee_picture,
                            ];
    
                            if($employee && $employee->count()) {
    
                                $rick = new EmployeeUpdateRequest();
            
                                $rick->replace($params);
    
                                if($employee->update($rick->all())) $response['updated']++;
                            }
                            else {
    
                                $rick = new EmployeeCreateRequest();
            
                                $rick->replace($params);
                                
                                Employee::create($rick->all());
                                $response['created']++;
                            }
                        }
        
                        SyncLog::where('id', $tmp->sync_log_id)->update(['status' => 2]);
                    }
                }
                else $response = $tmp;
            }

            return response()->json($response); 

            /* 
            $tmp = json_decode(($this->activeSyncLog($request))->content(), true);

            if($tmp && $tmp['id']) {
                
                return response()->json([
                    'success' => false,
                    'message' => 'Existe una actualización en curso','data' => $tmp
                ]);
            }

            $sys_log = SyncLog::create([
                'service_provider_id' => $request['service_provider_id'],
                'module' => $request['module'],
                'user_id' => auth()->user()->id,
            ]);

            $response = [
                'sync_status' => null,
                'total' => 0,
                'created' => 0,
                'updated' => 0,
                'unchanged' => 0,
            ];

            $authorization = "Authorization: Bearer ".$request['token'];

            $ch = curl_init($request['url']);

            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $authorization));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);

            $curl_response = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $data = json_decode($curl_response);

            if($data && sizeof($data)) {

                $response['total'] = sizeof($data);
                    
                foreach($data as $row) {

                    $employee = Employee::where('employee_id', $row->employee_id)->first();

                    $params = [
                        'employee_id' => $row->employee_id,
                        'company_id' => $row->company_id,
                        'employee_name' => $row->employee_name,
                        'employee_last_name' => $row->employee_last_name,
                        'employee_second_last_name' => $row->employee_second_last_name,
                        'employee_status' => $row->employee_status,
                        'company_name' => $row->company_name,
                        'cost_center_id' => $row->cost_center_id,
                        'cost_center_name' => $row->cost_center_name,
                        'base_location_id' => $row->base_location_id,
                        'base_location_name' => $row->base_location_name,
                        'pay_location_id' => $row->pay_location_id,
                        'pay_location_name' => $row->pay_location_name,
                        'period_id' => $row->period_id,
                        'period_name' => $row->period_name,
                        'department_id' => $row->department_id,
                        'department_name' => $row->department_name,
                        'work_station_id' => $row->work_station_id,
                        'work_station_name' => $row->work_station_name,
                        'work_shift_id' => $row->work_shift_id,
                        'work_shift_name' => $row->work_shift_name,
                        'imss_regime_id' => $row->imss_regime_id,
                        'imss_regime_name' => $row->imss_regime_name,
                        'benefit_tabulator_id' => $row->benefit_tabulator_id,
                        'benefit_tabulator_name' => $row->benefit_tabulator_name,
                        'clasification_id' => $row->clasification_id,
                        'clasification_name' => $row->clasification_name,
                        'pay_method_id' => $row->pay_method_id,
                        'pay_method_name' => $row->pay_method_name,
                        'rfc' => $row->rfc,
                        'imss_number' => $row->imss_number,
                        'blood_type' => $row->blood_type,
                        'entry_date' => $row->entry_date,
                        'group_entry_date' => $row->group_entry_date,
                        'contract_end_date' => $row->contract_end_date,
                        'unionized' => $row->unionized,
                        'employee_email' => $row->employee_email,
                        'employee_personal_email' => $row->employee_personal_email,
                        'birth_place' => $row->birth_place,
                        'birth_date' => $row->birth_date,
                        'nationality' => $row->nationality,
                        'gender' => $row->gender,
                        'street' => $row->street,
                        'inner_number' => $row->inner_number,
                        'external_number' => $row->external_number,
                        'between_street' => $row->between_street,
                        'and_street' => $row->and_street,
                        'district' => $row->district,
                        'postal_code' => $row->postal_code,
                        'city' => $row->city,
                        'state' => $row->state,
                        'telephone_number' => $row->telephone_number,
                        'curp' => $row->curp,
                        'marital_status' => $row->marital_status,
                        'bank_id' => $row->bank_id,
                        'bank_name' => $row->bank_name,
                        'bank_account' => $row->bank_account,
                        'contract_type' => $row->contract_type,
                        'roll_id' => $row->roll_id,
                        'roll_name' => $row->roll_name,
                        'base_seniority' => $row->base_seniority,
                        'salary_type' => $row->salary_type,
                        'salary_tabulator_id' => $row->salary_tabulator_id,
                        'salary_tabulator_name' => $row->salary_tabulator_name,
                        'salary_tabulator_level' => $row->salary_tabulator_level,
                        'salary_tabulator_level_name' => $row->salary_tabulator_level_name,
                        'business_name_id' => $row->business_name_id,
                        'business_name' => $row->business_name,
                        'business_unit_id' => $row->business_unit_id,
                        'business_unit_name' => $row->business_unit_name,
                        'alphanumeric_id_1' => $row->alphanumeric_id_1,
                        'alphanumeric_id_2' => $row->alphanumeric_id_2,
                        'alphanumeric_id_3' => $row->alphanumeric_id_3,
                        'termination_date' => $row->termination_date,
                        'business_rfc' => $row->business_rfc,
                        'payment_frequency' => $row->payment_frequency,
                        'collaborator_id' => $row->collaborator_id,
                        'daily_wage' => $row->daily_wage,
                        'monthly_salary' => $row->monthly_salary,
                        'integrated_salary' => $row->integrated_salary,
                        'integrated_salary_without_limit' => $row->integrated_salary_without_limit,
                        'fixed_factor' => $row->fixed_factor,
                        'monthly_salary_avg' => $row->monthly_salary_avg,
                        'employee_picture' => $row->employee_picture,
                    ];

                    if($employee && $employee->count()) {

                        $rick = new EmployeeUpdateRequest();
    
                        $rick->replace($params);

                        if($employee->update($rick->all())) $response['updated']++;
                    }
                    else {

                        $rick = new EmployeeCreateRequest();
    
                        $rick->replace($params);
                        
                        Employee::create($rick->all());
                        $response['created']++;
                    }
                }
            }

            $sys_log->update(['status' => 2]);

            return response()->json($response); 
            */
        }
        catch (Exception $e) {
            return response()->json([
                'error' => $e
            ]);
        }
    }

    public function syncAll(Request $request) {

        $response = [
            'company' => null,
            'business_name' => null,
            'department' => null,
            'location' => null,
            $module => null,
            'success' => true
        ];

        $module = 'company';
        $request['module'] = $module;
        $tmp = $this->syncCompany($request);
        $response[$module] = json_decode($tmp->content());
        
        $module = 'business_name';
        $request['module'] = $module;
        $tmp = $this->syncBusinessName($request);
        $response[$module] = json_decode($tmp->content());

        $module = 'department';
        $request['module'] = $module;
        $tmp = $this->syncDepartment($request);
        $response[$module] = json_decode($tmp->content());

        $module = 'location';
        $request['module'] = $module;
        $tmp = $this->syncLocation($request);
        $response[$module] = json_decode($tmp->content());

        $module = 'employee';
        $request['module'] = $module;
        $tmp = $this->syncEmployee($request);
        $response['department'] = json_decode($tmp->content());

        return response()->json($response, 200);
    }

    public function syncData(Request $request) {
        
        try {

            set_time_limit(0);

            // VERIFICAMOS SI EXISTE ACTUALIZACIÓN EN CURSO
            $tmp = json_decode(($this->activeSyncLog($request))->content(), true);

            if($tmp && $tmp['id']) {
                
                return response()->json([
                    'success' => false,
                    'message' => 'Existe una actualización en curso','data' => $tmp
                ]);
            }

            // SE CREA EL LOG DE LA ACTUALIZACIÓN DE MÓDULO
            $sync_log = SyncLog::create([
                'service_provider_id' => $request['service_provider_id'],
                'module' => $request['module'],
                'user_id' => auth()->user()->id,
            ]);

            // BUSCAMOS EL ENDPOINT DEL MÓDULO
            $service_provider_module = ServiceProviderModule::where('module', $request['module'])
                                                    ->select('url')
                                                    ->where('service_provider_id', $request['service_provider_id'])
                                                    ->first();

            if(!$service_provider_module) {
                return response()->json([
                    'success' => false,
                    'message' => 'No existe el endpoint para el módulo `'.$request['module'].'`'
                ]);
            }

            $tmp = $this->getToken($request);
            $tmp_token = json_decode($tmp->content(), true);
            $tmp_token['sync_log_id'] = null;

            if($tmp_token['status'] == 200) {

                /* 
                $response = [
                    'sync_status' => null,
                    'total' => 0,
                    'created' => 0,
                    'updated' => 0,
                    'unchanged' => 0,
                ]; 
                */

                $authorization = "Authorization: Bearer ".$tmp_token['token'];
                $ch = curl_init($service_provider_module->url);

                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", $authorization));
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 240);
                curl_setopt($ch, CURLOPT_TIMEOUT, 240);

                $curl_response = curl_exec($ch);
                $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                $response = [
                    'status' => $status_code,
                    'data' => null,
                    'sync_log_id' => $sync_log->id,
                    'success' => null,
                ];

                if ($status_code == 200) {
                    // echo "Return code is {$statusCode} \n".curl_error($ch);
                    $response['success'] = true;
                    $response['data'] = json_decode($curl_response);
                }
                else {
                    // echo print_r($statusCode);
                    // $response['data'] = ;
                    $response['success'] = false;
                }

                

                /* if($data && sizeof($data)) {

                    $response['total'] = sizeof($data);
                        
                    foreach($data as $row) {

                        $company = Compania::where('company_id', $row->company_id)->first();

                        if($company && $company->count()) {
                            $rick = new CompaniaUpdateRequest();
        
                            $rick->replace([
                                    'company_id' => $row->company_id,
                                    'company_name' => $row->company_name,
                            ]);

                            if($company->update($rick->all())) $response['updated']++;
                        }
                        else {

                            $rick = new CompaniaCreateRequest();
        
                            $rick->replace([
                                    'company_id' => $row->company_id,
                                    'company_name' => $row->company_name,
                            ]);
                            
                            Compania::create($rick->all());
                            $response['created']++;
                        }
                    }
                }

                $sync_log->update(['status' => 2]); */
            }
            else {
                // unset($tmp_token['token']);
                // $tmp_token['success'] = false; 
                $response = $tmp_token;
            }

            return response()->json($response);
        }
        catch (Exception $e) {
            return response()->json([
                'error' => $e
            ]);
        }
    }

    public function activeSyncLog(Request $request) {

        $response = DB::table('sync_logs')
                            ->select('sync_logs.id', 'sync_logs.module', 'sync_logs.created_at', 'sync_logs.user_id', 'users.name', 'users.email')
                            ->join('users', 'users.id', 'sync_logs.user_id')
                            ->where('service_provider_id', $request['service_provider_id'])
                            ->whereNull('finished_at')
                            ->orderBy('id', 'desc')
                            ->limit(1)
                            ->first();
        
        return response()->json($response, 200);
    }
}
