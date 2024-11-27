<?php

use App\Models\ApplicationLog;
use App\Models\Permission;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Mail\GenericMailer;
use App\Models\User;
use App\Models\Configuration;
    function helperEmail(){
        return 'alieallshore@gmail.com';
    }
    function validateAll($validate){
        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput();
        }
    }

    function validateRequest(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            //errorLogs($model = "\App\Models\Role", $log = $exception->getMessage(), $module = 'role', $functionType = 'destroy', $errorType, $getTraceAsString = $exception->getTraceAsString());             
            redirect()->back()->withErrors($validator)->withInput()->throwResponse();
        }
    }

    function hasSingleImageFile(Request $request){
        if(!$request->hasFile('file_name')) {
            return response()->json(['upload_file_not_found'], 400);
        }        
        $allowedfileExtension=['pdf','jpg','png'];
        $files = $request->file('file_name'); 
        $errors = [];        
        $extension = $files->getClientOriginalExtension();
        $check = in_array($extension,$allowedfileExtension);
        $path = $request->file_name->store('public/images');
        $name = $request->file_name->getClientOriginalName();
        $request['banner'] = $path;
        return $request;      
    }

    function success($view = null, $data = [], $message = 'Operation successful', $deviceType = 'web'){
        return view($view, [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ]);
    }

    function error($view = null, $data = [], $message = 'Operation failed', $deviceType = 'web'){
        return view($view, [
            'status' => 'error',
            'message' => $message,
            'data' => $data,
        ]);
    }

    function emailNotifcationForUser($email, $content, $subject){
        // dd($email, $mail, $data);
        try{
            // $sentMessage   = Mail::to($email)->send(new $mail([
            //     'data' => $data,
            //     'subject' => 'Password Reset Email',            
            // ]));
            $sentMessage   = Mail::to($email)->send(new GenericMailer($subject, $content, 'Nadeem.i@allshoretalent.com'));
            $messageId = $sentMessage->getMessageId();
            if($ret->messageId){
                
                return true;
            } else {
                errorLogs(null, 'Unable to send email', 'emailNotifcationForUser', null, 'email error', serialize($content));
            }
        }catch(Exception $e){
            $message = $e->getMessage();
            errorLogs(null, 'Unable to send email', $message, null, 'email error', serialize($content));
            //return $e;
        }
    }
    /**
     * $model = controller
     * $log = log
     * $method= module
     * $errorType = error type
     */
    function errorLogs($model, $log, $message, $method, $errorType, $getTraceAsString){       
        // $errorMessages = array(
        //     'index'     => 'Trying to view list of through index function in Module =' . $model . '  Error Message: ' . $log,
        //     'show'      => 'Trying to show data through show function in Module =' . $model . '  Error Message: ' . $log,
        //     'create'    => 'Trying to run create function in Module =' . $model . '  Error Message: ' . $log,
        //     'store'     => 'Trying to save data through store function in Module =' . $model . '  Error Message: ' . $log,
        //     'edit'      => 'Trying to fetch information through edit function in Module =' . $model . '  Error Message: ' . $log,
        //     'update'    => 'Trying to update data through update function in Module =' . $model . '  Error Message: ' . $log,
        //     'destroy'   => 'Trying to delete through destroy function in Module =' . $model . '  Error Message: ' . $log,
        //     'search'    => 'Trying to search from the data through search function in Module =' . $model . '  Error Message: ' . $log,
        //     'other'     => $model . '  Error Message: ' . $log,
        // );
        
        $checkAuth = 0;
        if(isset(Auth::user()->id)){
            $checkAuth = Auth::user()->id;
        }
        if(Config::get('app.logging')){
            //Log::channel('userLog')->error($errorMessages[$functionType], ['user' => $checkAuth]);    
            Log::channel('userLog')->error($message, ['user' => $checkAuth]);    
        }
        // dd('here', $model, $errorType, $module, $errorMessages[$functionType], $log, $getTraceAsString);
        $data = [
            'model'         => $model,
            //'model'         => 'none',
            //'log'           => $errorMessages[$functionType],
            'log'           => $log,
            'message'       => $message,
            'module'        => $method,
            'error_type'    => $errorType,
            // 'module'        => $module,
            'stack_trace'   => $getTraceAsString,
            'level'         => 'error',
        ];
        $applicationLog = ApplicationLog::create($data);
        return $applicationLog;
    }

    function twilioSMSToken($user, $remember){
        $token = Token::create([
            'user_id' => $user->id
        ]);
        $token->sendCode();
        if ($token->sendCode()) {
            dd("here    ");
            session()->set("token_id", $token->id);
            session()->set("user_id", $user->id);
            session()->set("remember", $remember);
            return view('users.code');
        }
    }

        // filter donation fields by given gids
        function filterFieldsByGids($rows) {
            $gids = [
                '215976511602589' => 'job_id', 
                '1206200087474163' => 'received_at',
                '218627553660083' => 'first_name',
                '218627553660086' => 'last_name', 
                '218627553660092' => 'street_address', 
                '218627553660096' => 'city',
                '218627553660094' => 'state',
                '218627553660098' => 'zip',
                '220259761730981' => 'organization_name',
                '1201932524599548' => 'impact_report_link',
                '327036247827065' => 'donation_receipt_link',
                '256473962359898' => 'apllets_received',
                '1164605808659656' => 'buld_ewaste_weight',
                '1206123470176889' => 'donation_stage',
                '1199173512699290' => 'record_id',
            ];
            $fields = [];
            foreach($rows as $k => $field) {
                $arg_key = array_keys($gids);
                if(in_array($field->gid, $arg_key)) {
                    $fields[$gids[$field->gid]] = $field->value;
                }
            }
            return (object) $fields;
            //  return array_filter($rows, function($field) use ($gids) {
            //     return in_array($field->gid, $gids);
            // });
        }
    
        function convertToDateFormat($dateString) {
            try {
                // Attempt to parse the date
                $date = Carbon::parse($dateString);
    
                // Convert the date to the desired format
                return $date->format('F j, Y');
            } catch (Exception $e) {
                // Handle invalid date
                return "-";
            }
        }
        
        function getAvatar($user = null)
        {
            $user = $user ?? auth()->user();
            
            if($user and !empty($user->picture)) {
                if ($user->picture === 'picture.png') {
                    return asset($user->picture);
                }
                $hasDoSpaces = (strpos($user->picture, '.digitaloceanspaces.com') !== false);
                if($hasDoSpaces) {
                    return $user->picture;
                }
            }
    
            return 'https://itad-portal-files.sfo3.digitaloceanspaces.com/avatars/1727899799_picture.png';
        }

        function getLogo()
        {
            $logo = getConfigObject('logo');
            
            if($logo and !empty($logo->value)) {
                $hasDoSpaces = (strpos($logo->value, '.digitaloceanspaces.com') !== false);
                if($hasDoSpaces) {
                    return $logo->value;
                }
            }
    
            return 'https://itad-portal-files.sfo3.digitaloceanspaces.com/images/logo.png';
        }
        function getFavicon()
        {
            $logo = getConfigObject('favicon');
            
            if(!$logo and !empty($logo->value)) {
                $hasDoSpaces = (strpos($logo->value, '.digitaloceanspaces.com') !== false);
                if($hasDoSpaces) {
                    return $logo->value;
                }
            }
    
            return 'https://itad-portal-files.sfo3.digitaloceanspaces.com/images/favicon.ico';
        }
        function maskPhoneNumber($phone) {
            // Keep last 3 digits, mask the rest
            $masked = '( *** ) *** - ' . substr($phone, -4);
            return $masked;
        }
        function maskEmail($email) {
            // Split the email into local and domain parts
            list($localPart, $domainPart) = explode('@', $email);
    
            // Mask the local part (show first two characters)
            $maskedLocalPart = substr($localPart, 0, 2) . str_repeat('*', strlen($localPart) - 2);
    
            // Split the domain into name and top-level domain (TLD)
            list($domainName, $tld) = explode('.', $domainPart);
    
            // Mask the domain part (show first two characters of domain name)
            $maskedDomainPart = substr($domainName, 0, 2) . str_repeat('*', strlen($domainName) - 2) . '.' . $tld;
    
            // Return the masked email
            return $maskedLocalPart . '@' . $maskedDomainPart;
        }
        function getConfigObject($name) {
            $obj = Configuration::where('name', $name)->first();
            return $obj ?? null;
        }
        function getSetting($name) {
            $obj = Configuration::where('name', $name)->first();
            return $obj ? $obj->value : '';
        }
        function donationsPerPage() {
            $obj = Configuration::where('name', 'donations_per_page')->first();
            return $obj && $obj->value != null && intval($obj->value) > 0 ? intval($obj->value) : 10;
        }

        function prepareEmailTemplate($email, $template, $patterns=[], $subject = 'System Email') {
            //$email_base_template = view('emails.template')->render(); // replace {body_content} from base content
            $email_base_template = '';
            $email_template =  getSetting($template); // Configuration::where('name', 'password_reset_success')->first();
            if($email_template && $patterns) {
                foreach($patterns as $key => $value) {
                    $email_template = str_replace('{'.$key.'}', $value, $email_template);
                }
            }
            //$email_content = str_replace('{body_content}', $email_template, $email_base_template);
            $email_content = $email_template;
            //return $email_base_template;
            emailNotifcationForUser($email, $email_content, $subject);
        }

         function getGroupedPermissions()
        {
            $permissions = Permission::where('type', 'user-define')->get();
            $groupedPermissions = [];

            foreach ($permissions as $permission) {
                // Assuming the naming convention is {entity}-{action}
                $parts = explode('-', $permission->name);
                if (count($parts) > 1) {
                    $groupedPermissions[$parts[0]][] = $permission;
                } else {
                    $groupedPermissions['others'][] = $permission;
                }
            }
            return $groupedPermissions;
        }
        function getUserRole() {
            return Auth::user()->roles()->first()->name;
        }
        function paginateCustomArray(array $items, $page)
        {
            $perPage = getSetting('sync_requests_per_page') ?? 8;
            // Calculate the offset (which item to start from)
            $offset = ($page - 1) * $perPage;
            $offset = ($offset > 0 ) ? $offset : 0;
            // Return the sliced portion of the array based on the offset and $perPage
            return array_slice($items, $offset, $perPage);
        }
        function numPagesDonations(array $items)
        {
            $perPage = getSetting('sync_requests_per_page') ?? 8;
            // Calculate the offset (which item to start from)
            $totalItems = count($items);
            $totalPages = ceil($totalItems / $perPage);
            return $totalPages;
        }
