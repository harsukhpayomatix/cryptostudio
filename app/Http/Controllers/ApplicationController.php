<?php

namespace App\Http\Controllers;


use File;


use Session;
use App\User;
use Redirect;
use App\Admin;
use Exception;
use Validator;
use App\Categories;
use App\Application;
use App\ImageUpload;
use App\TechnologyPartner;
use Illuminate\Http\Request;
use App\Events\AdminNotification;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewApplicationSubmitUser;

use Illuminate\Support\Facades\Storage;
use App\Notifications\ApplicationResubmit;
use App\Notifications\NewApplicationSubmit;

class ApplicationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->moduleTitleS = 'Profile';
        $this->moduleTitleP = 'front.application';

        $this->application = new Application;

        view()->share('moduleTitleP', $this->moduleTitleP);
        view()->share('moduleTitleS', $this->moduleTitleS);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Categories::orderBy("categories.id", "ASC")->pluck('name', 'id')->toArray();
        $technologypartners = TechnologyPartner::latest()->pluck('name', 'id')->toArray();
        $data = Application::where('user_id', auth()->user()->id)->first();
        $data = Application::where('user_id', auth()->user()->id)->first();
        if ($data == null) {
            $inputdata['user_id'] = auth()->user()->id;
            $inputdata['status'] = '12';
            $data = $this->application->storeData($inputdata);
        }
        return view($this->moduleTitleP . '.startapplication', compact('category', 'technologypartners', 'data'));
    }

    public function startApplicationStore(Request $request)
    {
        $input = \Arr::except($request->all(), array('_token', '_method'));
        $Appdata = Application::where('user_id', auth()->user()->id)->first();
        $id = $Appdata->id;
        if (isset($input['action']) && $input['action'] == 'saveDraft') {
            $this->validate(
                $request,
                [
                    'phone_no' => 'max:14',
                    'passport.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                    'company_incorporation_certificate' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                    'domain_ownership' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                    'latest_bank_account_statement.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                    'utility_bill.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                    'previous_processing_statement.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                    'extra_document.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                    'owner_personal_bank_statement' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                    'licence_document' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                    'moa_document' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                ],
                [
                    'passport.*.max' => 'The passport size may not be greater than 35 MB.',
                    'company_incorporation_certificate.max' => 'The company incorporation certificate size may not be greater than 35 MB.',
                    'domain_ownership.max' => 'The domain ownership size may not be greater than 35 MB.',
                    'latest_bank_account_statement.*.max' => 'The latest bank account statement size may not be greater than 35 MB.',
                    'utility_bill.*.max' => 'The utility bill size may not be greater than 35 MB.',
                    'previous_processing_statement.max' => 'The previous processing statement size may not be greater than 35 MB.',
                    'extra_document.*.max' => 'The additional document size may not be greater than 35 MB.',
                    'licence_document.max' => 'The Licence document size may not be greater than 35 MB.',
                    'moa_document.max' => 'The MOA document size may not be greater than 35 MB.',
                    'owner_personal_bank_statement.max' => 'The owner personal bank statement size may not be greater than 35 MB.',
                ],

            );
            // $input['user_id'] = auth()->user()->id;
            $user = auth()->user();
            if (isset($input['processing_country'])) {
                $input['processing_country'] = json_encode($input['processing_country']);
            }
            if (isset($input['processing_currency'])) {
                $input['processing_currency'] = json_encode($input['processing_currency']);
            }
            if (isset($input['technology_partner_id'])) {
                $input['technology_partner_id'] = json_encode($input['technology_partner_id']);
            }
            if (isset($input['accept_card'])) {
                $input['accept_card'] = json_encode($input['accept_card']);
            }

            if ($request->hasFile('passport')) {
                $files = $request->file('passport');
                $passportArr = [];
                foreach ($files as $key => $value) {
                    $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                    $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                    $filePath = 'uploads/application-' . $user->id . '/' . $imageDocument;
                    Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                    array_push($passportArr, $filePath);
                }
                $input['passport'] = json_encode($passportArr);
            }
            if ($request->hasFile('utility_bill')) {
                $files = $request->file('utility_bill');
                $utilityArr = [];
                foreach ($files as $key => $value) {
                    $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                    $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                    $filePath = 'uploads/application-' . $user->id . '/' . $imageDocument;
                    Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                    array_push($utilityArr, $filePath);
                }
                $input['utility_bill'] = json_encode($utilityArr);
            }
            if ($request->hasFile('latest_bank_account_statement')) {
                $files = $request->file('latest_bank_account_statement');
                $bankStatementArr = [];
                foreach ($files as $key => $value) {
                    $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                    $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                    $filePath = 'uploads/application-' . $user->id . '/' . $imageDocument;
                    Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                    array_push($bankStatementArr, $filePath);
                }
                $input['latest_bank_account_statement'] = json_encode($bankStatementArr);
            }

            if ($request->hasFile('company_incorporation_certificate')) {
                $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageNameCertificate = $imageNameCertificate . '.' . $request->file('company_incorporation_certificate')->getClientOriginalExtension();
                $filePath = 'uploads/application-' . $user->id . '/' . $imageNameCertificate;
                Storage::disk('s3')->put($filePath, file_get_contents($request->file('company_incorporation_certificate')->getRealPath()));
                $input['company_incorporation_certificate'] = $filePath;
            }

            if ($request->hasFile('domain_ownership')) {
                $imageNamedomainownership = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageNamedomainownership = $imageNamedomainownership . '.' . $request->file('domain_ownership')->getClientOriginalExtension();
                $filePath = 'uploads/application-' . $user->id . '/' . $imageNamedomainownership;
                Storage::disk('s3')->put($filePath, file_get_contents($request->file('domain_ownership')->getRealPath()));
                $input['domain_ownership'] = $filePath;
            }

            if ($request->hasFile('licence_document')) {
                $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageNameCertificate = $imageNameCertificate . '.' . $request->file('licence_document')->getClientOriginalExtension();
                $filePath = 'uploads/application-' . $user->id . '/' . $imageNameCertificate;
                Storage::disk('s3')->put($filePath, file_get_contents($request->file('licence_document')->getRealPath()));
                $input['licence_document'] = $filePath;
            }

            if ($request->hasFile('moa_document')) {
                $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageNameCertificate = $imageNameCertificate . '.' . $request->file('moa_document')->getClientOriginalExtension();
                $filePath = 'uploads/application-' . $user->id . '/' . $imageNameCertificate;
                Storage::disk('s3')->put($filePath, file_get_contents($request->file('moa_document')->getRealPath()));
                $input['moa_document'] = $filePath;
            }

            if ($request->hasFile('previous_processing_statement')) {
                $files = $request->file('previous_processing_statement');
                foreach ($request->file('previous_processing_statement') as $key => $value) {
                    $imageStatement = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                    $imageStatement = $imageStatement . '.' . $files[$key]->getClientOriginalExtension();
                    $filePath = 'uploads/application-' . $user->id . '/' . $imageStatement;
                    Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                    $input['previous_processing_statements'][] = $filePath;
                }
                $input['previous_processing_statement'] = json_encode($input['previous_processing_statements']);
                unset($input['previous_processing_statements']);
            }

            if ($request->hasFile('extra_document')) {
                $files = $request->file('extra_document');
                foreach ($request->file('extra_document') as $key => $value) {
                    $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                    $imageDocument = $imageDocument . '.' . $files[$key]->getClientOriginalExtension();
                    $filePath = 'uploads/application-' . $user->id . '/' . $imageDocument;
                    Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                    $input['extra_documents'][] = $filePath;
                }

                $input['extra_document'] = json_encode($input['extra_documents']);
                unset($input['extra_documents']);
            }

            if ($request->hasFile('owner_personal_bank_statement')) {

                $imageOwnerBankStatement = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageOwnerBankStatement = $imageOwnerBankStatement . '.' . $request->file('owner_personal_bank_statement')->getClientOriginalExtension();
                $filePath = 'uploads/application-' . $user->id . '/' . $imageOwnerBankStatement;
                Storage::disk('s3')->put($filePath, file_get_contents($request->file('owner_personal_bank_statement')->getRealPath()));
                $input['owner_personal_bank_statement'] = $filePath;
            }

            unset($input['action']);
            // try {
            //     $application = $this->application->updateApplication($id, $input);
            // } catch (Exception $e) {
            //     prd($e->getMessage());
            // }

            DB::beginTransaction();
            try {
                // $input['status'] = '12';
                $application = $this->application->updateApplication($id, $input);
                $notification = [
                    'user_id' => '1',
                    'sendor_id' => auth()->user()->id,
                    'type' => 'admin',
                    'title' => 'Application Created',
                    'body' => 'You have received a new application.',
                    'url' => '/admin/applications-list/view/' . $id,
                    'is_read' => '0'
                ];
                DB::commit();
                \Session::put("successcustom", "Thank you for saving your application.");
                return redirect('my-application')->with('success', 'Thank you for saving your application.');
            } catch (Exception $e) {
                DB::rollBack();
                \Session::put('error', 'Your application not submit.Try Again.');
                return redirect()->back()->withInput($request->all());
            }
        } else {
            $total_required_files = 1;
            if ($request->board_of_directors != null && $request->board_of_directors > 0) {
                $total_required_files = $request->board_of_directors;
            }
            $OldData = $Appdata->toArray();
            $ArrDataValidate = $this->GetValidateArr($OldData, $total_required_files);
            $this->validate(
                $request,
                $ArrDataValidate,
                [
                    'business_name.regex' => 'Please Enter Only Alphanumeric Characters.',
                    'phone_no.numeric' => 'Please Enter Only Digits.',
                    'business_contact_first_name.regex' => 'Please Enter Only Alphanumeric Characters.',
                    'business_contact_last_name.regex' => 'Please Enter Only Alphanumeric Characters.',
                    'business_address1.regex' => 'Please Enter Valid Company Address.',
                    'residential_address.regex' => 'Please Enter Valid Residential Address.',
                    'passport.*.max' => 'The passport size may not be greater than 35 MB.',
                    'company_incorporation_certificate.max' => 'The company incorporation certificate size may not be greater than 35 MB.',
                    'domain_ownership.max' => 'The domain ownership size may not be greater than 35 MB.',
                    'latest_bank_account_statement.*.max' => 'The latest bank account statement size may not be greater than 35 MB.',
                    'utility_bill.*.max' => 'The utility bill size may not be greater than 35 MB.',
                    'previous_processing_statement.max' => 'The previous processing statement size may not be greater than 35 MB.',
                    'extra_document.*.max' => 'The additional document size may not be greater than 35 MB.',
                    'licence_document.max' => 'The Licence document size may not be greater than 35 MB.',
                    'moa_document.max' => 'The MOA document size may not be greater than 35 MB.',
                    'owner_personal_bank_statement.max' => 'The owner personal bank statement size may not be greater than 35 MB.',
                ],
            );
            $input['user_id'] = auth()->user()->id;
            $user = auth()->user();
            $input['processing_country'] = json_encode($input['processing_country']);
            $input['processing_currency'] = json_encode($input['processing_currency']);
            $input['technology_partner_id'] = json_encode($input['technology_partner_id']);
            if (isset($input['accept_card'])) {
                $input['accept_card']           = json_encode($input['accept_card']);
            }

            if ($request->hasFile('passport')) {
                $files = $request->file('passport');
                $passportArr = [];
                foreach ($files as $key => $value) {
                    $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                    $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                    $filePath = 'uploads/application-' . $user->id . '/' . $imageDocument;
                    Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                    array_push($passportArr, $filePath);
                }
                $input['passport'] = json_encode($passportArr);
            }
            if ($request->hasFile('utility_bill')) {
                $files = $request->file('utility_bill');
                $utilityArr = [];
                foreach ($files as $key => $value) {
                    $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                    $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                    $filePath = 'uploads/application-' . $user->id . '/' . $imageDocument;
                    Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                    array_push($utilityArr, $filePath);
                }
                $input['utility_bill'] = json_encode($utilityArr);
            }
            if ($request->hasFile('latest_bank_account_statement')) {
                $files = $request->file('latest_bank_account_statement');
                $bankStatementArr = [];
                foreach ($files as $key => $value) {
                    $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                    $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                    $filePath = 'uploads/application-' . $user->id . '/' . $imageDocument;
                    Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                    array_push($bankStatementArr, $filePath);
                }
                $input['latest_bank_account_statement'] = json_encode($bankStatementArr);
            }

            if ($request->hasFile('company_incorporation_certificate')) {
                $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageNameCertificate = $imageNameCertificate . '.' . $request->file('company_incorporation_certificate')->getClientOriginalExtension();
                $filePath = 'uploads/application-' . $user->id . '/' . $imageNameCertificate;
                Storage::disk('s3')->put($filePath, file_get_contents($request->file('company_incorporation_certificate')->getRealPath()));
                $input['company_incorporation_certificate'] = $filePath;
            }

            if ($request->hasFile('domain_ownership')) {
                $imageNamedomainownership = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageNamedomainownership = $imageNamedomainownership . '.' . $request->file('domain_ownership')->getClientOriginalExtension();
                $filePath = 'uploads/application-' . $user->id . '/' . $imageNamedomainownership;
                Storage::disk('s3')->put($filePath, file_get_contents($request->file('domain_ownership')->getRealPath()));
                $input['domain_ownership'] = $filePath;
            }

            if ($request->hasFile('licence_document')) {
                $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageNameCertificate = $imageNameCertificate . '.' . $request->file('licence_document')->getClientOriginalExtension();
                $filePath = 'uploads/application-' . $user->id . '/' . $imageNameCertificate;
                Storage::disk('s3')->put($filePath, file_get_contents($request->file('licence_document')->getRealPath()));
                $input['licence_document'] = $filePath;
            }

            if ($request->hasFile('moa_document')) {
                $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageNameCertificate = $imageNameCertificate . '.' . $request->file('moa_document')->getClientOriginalExtension();
                $filePath = 'uploads/application-' . $user->id . '/' . $imageNameCertificate;
                Storage::disk('s3')->put($filePath, file_get_contents($request->file('moa_document')->getRealPath()));
                $input['moa_document'] = $filePath;
            }

            if ($request->hasFile('previous_processing_statement')) {
                $files = $request->file('previous_processing_statement');
                foreach ($request->file('previous_processing_statement') as $key => $value) {
                    $imageStatement = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                    $imageStatement = $imageStatement . '.' . $files[$key]->getClientOriginalExtension();
                    $filePath = 'uploads/application-' . $user->id . '/' . $imageStatement;
                    Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                    $input['previous_processing_statements'][] = $filePath;
                }
                $input['previous_processing_statement'] = json_encode($input['previous_processing_statements']);
                unset($input['previous_processing_statements']);
            }

            if ($request->hasFile('extra_document')) {
                $files = $request->file('extra_document');
                foreach ($request->file('extra_document') as $key => $value) {
                    $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                    $imageDocument = $imageDocument . '.' . $files[$key]->getClientOriginalExtension();
                    $filePath = 'uploads/application-' . $user->id . '/' . $imageDocument;
                    Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                    $input['extra_documents'][] = $filePath;
                }

                $input['extra_document'] = json_encode($input['extra_documents']);
                unset($input['extra_documents']);
            }

            if ($request->hasFile('owner_personal_bank_statement')) {

                $imageOwnerBankStatement = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageOwnerBankStatement = $imageOwnerBankStatement . '.' . $request->file('owner_personal_bank_statement')->getClientOriginalExtension();
                $filePath = 'uploads/application-' . $user->id . '/' . $imageOwnerBankStatement;
                Storage::disk('s3')->put($filePath, file_get_contents($request->file('owner_personal_bank_statement')->getRealPath()));
                $input['owner_personal_bank_statement'] = $filePath;
            }
            unset($input['action']);
            DB::beginTransaction();
            try {
                $input['status'] = '1';
                // $application = $this->application->storeData($input);
                $application = $this->application->updateApplication($id, $input);
                $application = Application::where('user_id', auth()->user()->id)->first();
                $notification = [
                    'user_id' => '1',
                    'sendor_id' => auth()->user()->id,
                    'type' => 'admin',
                    'title' => 'Application Created',
                    'body' => 'You have received a new application.',
                    'url' => '/admin/applications-list/view/' . $application->id,
                    'is_read' => '0'
                ];
                $realNotification = addNotification($notification);
                $realNotification->created_at_date = convertDateToLocal($realNotification->created_at, 'd/m/Y H:i:s');
                event(new AdminNotification($realNotification->toArray()));
                Admin::find('1')->notify(new NewApplicationSubmit($application));
                Mail::to($user->email)->queue(new NewApplicationSubmitUser($user->name));
                DB::commit();
                \Session::put("successcustom", "Thank you for submitting your application.");
                return redirect('my-application')->with('success', 'Thank you for submitting your application. Your application is under review.');
            } catch (Exception $e) {

                DB::rollBack();
                prd($e->getMessage());
                \Session::put('error', 'Your application not submit.Try Again.');
                return redirect()->back()->withInput($request->all());
            }
        }
    }

    public function status(Request $request)
    {
        if (\Auth::user()->main_user_id != '0')
            $userID = \Auth::user()->main_user_id;
        else
            $userID = \Auth::user()->id;

        $data = $this->application->FindDataFromUser($userID);
        $isResume = 0;
        if ($data) {
            $LastData = $data->toArray();
            $LastData = array_filter($LastData);

            unset($LastData['id']);
            unset($LastData['user_id']);
            unset($LastData['monthly_volume']);
            unset($LastData['monthly_volume_currency']);
            unset($LastData['country_code']);
            unset($LastData['status']);
            unset($LastData['created_at']);
            unset($LastData['updated_at']);
            unset($LastData['name']);
            unset($LastData['email']);
            unset($LastData['agent_commission']);
            unset($LastData['accept_card']);
            if (!empty($LastData)) {
                $isResume = 1;
            }
        }

        return view($this->moduleTitleP . '.status', compact('data', 'isResume'));
    }

    public function applicationsEdit(Request $request, $id)
    {
        $data = $this->application->findData($id);
        if ($data->user_id != \Auth::user()->id) {
            return redirect()->back();
        }
        if ($data) {
            $category = Categories::orderBy("categories.id", "ASC")->pluck('name', 'id')->toArray();
            $technologypartners = TechnologyPartner::latest()->pluck('name', 'id')->toArray();
            return view($this->moduleTitleP . '.applicationsEdit', compact('category', 'data', 'id', 'request', 'technologypartners'));
        }
        return redirect()->back();
    }

    public function applicationsUpdate(Request $request, $id)
    {
        $input = \Arr::except($request->all(), array('_token', '_method'));
        $user = User::where('id', $input['user_id'])->first();
        $application = Application::where('id', $id)->first();
        $this->validate(
            $request,
            [
                'business_type' => 'required',
                'accept_card' => 'required',
                'business_name' => 'required|regex:/^[a-z\d\-_\s\.]+$/i',
                'website_url' => 'required',
                'phone_no' => 'required|numeric',
                'skype_id' => 'required',
                'business_contact_first_name' => 'required|regex:/^[a-z\d\-_\s\.]+$/i',
                'business_contact_last_name' => 'required|regex:/^[a-z\d\-_\s\.]+$/i',
                'business_address1' => 'required|regex:/^[a-z\d\-_\s\.\,]+$/i',
                'residential_address' => 'required|regex:/^[a-z\d\-_\s\.\,]+$/i',
                'monthly_volume' => 'required',
                'country' => 'required',
                'processing_currency' => 'required',
                'technology_partner_id' => 'required',
                'processing_country' => 'required',
                'category_id' => 'required',
                'company_license' => 'required',
                'passport.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'company_incorporation_certificate' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'domain_ownership' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'latest_bank_account_statement.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'utility_bill.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'previous_processing_statement.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'extra_document.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'owner_personal_bank_statement' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'licence_document' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'moa_document' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
            ],
            [
                'business_name.regex' => 'Please Enter Only Alphanumeric Characters.',
                'phone_no.numeric' => 'Please Enter Only Digits.',
                'business_contact_first_name.regex' => 'Please Enter Only Alphanumeric Characters.',
                'business_contact_last_name.regex' => 'Please Enter Only Alphanumeric Characters.',
                'business_address1.regex' => 'Please Enter Valid Company Address.',
                'residential_address.regex' => 'Please Enter Valid Residential Address.',
                'passport.*.max' => 'The passport size may not be greater than 35 MB.',
                'company_incorporation_certificate.max' => 'The company incorporation certificate size may not be greater than 35 MB.',
                'domain_ownership.max' => 'The domain ownership size may not be greater than 35 MB.',
                'latest_bank_account_statement.*.max' => 'The latest bank account statement size may not be greater than 35 MB.',
                'utility_bill.*.max' => 'The utility bill size may not be greater than 35 MB.',
                'previous_processing_statement.max' => 'The previous processing statement size may not be greater than 35 MB.',
                'extra_document.*.max' => 'The additional document size may not be greater than 35 MB.',
                'owner_personal_bank_statement.max' => 'The owner personal bank statement size may not be greater than 35 MB.',
                'licence_document.max' => 'The Licence document size may not be greater than 35 MB.',
                'moa_document.max' => 'The MOA document size may not be greater than 35 MB.',
            ],
        );

        $input['processing_country'] = json_encode($input['processing_country']);
        $input['processing_currency'] = json_encode($input['processing_currency']);
        $input['technology_partner_id'] = json_encode($input['technology_partner_id']);

        // $input['customer_location'] = json_encode($input['customer_location']);
        // $input['settlement_currency'] = json_encode($input['settlement_currency']);
        $filePath = storage_path() . "/uploads/" . $user->name . '-' . $user->id . '/';
        if ($request->hasFile('passport')) {
            $old_passport_documents = json_decode($application->passport);
            $files = $request->file('passport');
            $passportArr = [];
            foreach ($files as $key => $value) {
                $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                $filePath = 'uploads/application-' . $user->id . '/' . $imageDocument;
                Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                array_push($passportArr, $filePath);
            }
            $updated_passport_documents = array_merge($old_passport_documents, $passportArr);
            $input['passport'] = json_encode($updated_passport_documents);
        }
        if ($request->hasFile('latest_bank_account_statement')) {
            $old_bank_statement = json_decode($application->latest_bank_account_statement);
            $files = $request->file('latest_bank_account_statement');
            $bankStatementArr = [];
            foreach ($files as $key => $value) {
                $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                $filePath = 'uploads/application-' . $user->id . '/' . $imageDocument;
                Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                array_push($bankStatementArr, $filePath);
            }
            $updated_bankStatement = array_merge($old_bank_statement, $bankStatementArr);
            $input['latest_bank_account_statement'] = json_encode($updated_bankStatement);
        }

        if ($request->hasFile('utility_bill')) {
            $old_utilityBill = json_decode($application->utility_bill);
            $files = $request->file('utility_bill');
            $utilityBillArr = [];
            foreach ($files as $key => $value) {
                $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                $filePath = 'uploads/application-' . $user->id . '/' . $imageDocument;
                Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                array_push($utilityBillArr, $filePath);
            }
            $utilityBill = array_merge($old_utilityBill, $utilityBillArr);
            $input['utility_bill'] = json_encode($utilityBill);
        }

        if ($request->hasFile('licence_document')) {
            $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
            $imageNameCertificate = $imageNameCertificate . '.' . $request->file('licence_document')->getClientOriginalExtension();
            $filePath = 'uploads/application-' . $user->id . '/' . $imageNameCertificate;
            Storage::disk('s3')->put($filePath, file_get_contents($request->file('licence_document')->getRealPath()));
            $input['licence_document'] = $filePath;
        } else if ($request->company_license == '1' || $request->company_license == '2') {
            Storage::disk('s3')->delete($application->licence_document);
            $input['licence_document'] = null;
        }


        if ($request->hasFile('moa_document')) {
            $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
            $imageNameCertificate = $imageNameCertificate . '.' . $request->file('moa_document')->getClientOriginalExtension();
            $filePath = 'uploads/application-' . $user->id . '/' . $imageNameCertificate;
            Storage::disk('s3')->put($filePath, file_get_contents($request->file('moa_document')->getRealPath()));
            $input['moa_document'] = $filePath;
        }

        if ($request->hasFile('company_incorporation_certificate')) {
            Storage::disk('s3')->delete($application->company_incorporation_certificate);
            $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
            $imageNameCertificate = $imageNameCertificate . '.' . $request->file('company_incorporation_certificate')->getClientOriginalExtension();
            $filePath = 'uploads/application-' . $user->id . '/' . $imageNameCertificate;
            Storage::disk('s3')->put($filePath, file_get_contents($request->file('company_incorporation_certificate')->getRealPath()));
            $input['company_incorporation_certificate'] = $filePath;
        }

        if ($request->hasFile('domain_ownership')) {
            Storage::disk('s3')->delete($application->domain_ownership);
            $imageNamedomainownership = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
            $imageNamedomainownership = $imageNamedomainownership . '.' . $request->file('domain_ownership')->getClientOriginalExtension();
            $filePath = 'uploads/application-' . $user->id . '/' . $imageNamedomainownership;
            Storage::disk('s3')->put($filePath, file_get_contents($request->file('domain_ownership')->getRealPath()));
            $input['domain_ownership'] = $filePath;
        }

        if ($request->hasFile('previous_processing_statement')) {
            // delete old records.
            if ($application->previous_processing_statement != null) {
                foreach (json_decode($application->previous_processing_statement) as $key => $value) {
                    Storage::disk('s3')->delete($value);
                }
            }
            $files = $request->file('previous_processing_statement');
            foreach ($request->file('previous_processing_statement') as $key => $value) {
                $imageStatement = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageStatement = $imageStatement . '.' . $files[$key]->getClientOriginalExtension();
                $filePath = 'uploads/application-' . $user->id . '/' . $imageStatement;
                Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                $input['previous_processing_statements'][] = $filePath;
            }

            $input['previous_processing_statement'] = json_encode($input['previous_processing_statements']);
            unset($input['previous_processing_statements']);
        }


        $old_extra_documents = [];
        if (Application::find($id)->extra_document) {
            $old_extra_documents = json_decode(Application::find($id)->extra_document);
        }
        if ($old_extra_documents) {
            if ($request->hasFile('extra_document')) {
                $files = $request->file('extra_document');
                foreach ($request->file('extra_document') as $key => $value) {
                    $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                    $imageDocument = $imageDocument . '.' . $files[$key]->getClientOriginalExtension();
                    $filePath = 'uploads/application-' . $user->id . '/' . $imageDocument;
                    Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                    $input['extra_documents'][] = $filePath;
                }
                $input['extra_document'] = json_encode($input['extra_documents']);
                $new_extra_documents = json_decode($input['extra_document']);
                $updated_extra_documents = array_merge($old_extra_documents, $new_extra_documents);
                $input['extra_document'] = json_encode($updated_extra_documents);
                unset($input['extra_documents']);
            }
        } else {
            if ($request->hasFile('extra_document')) {
                $files = $request->file('extra_document');
                foreach ($request->file('extra_document') as $key => $value) {
                    $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                    $imageDocument = $imageDocument . '.' . $files[$key]->getClientOriginalExtension();
                    $filePath = 'uploads/application-' . $user->id . '/' . $imageDocument;
                    Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                    $input['extra_documents'][] = $filePath;
                }
                $input['extra_document'] = json_encode($input['extra_documents']);
                unset($input['extra_documents']);
            }
        }

        if ($request->hasFile('owner_personal_bank_statement')) {
            File::delete(storage_path() . "/uploads/" . $user->name . '-' . $user->id . '/' . $application->owner_personal_bank_statement);
            $imageOwnerBankStatement = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
            $imageOwnerBankStatement = $imageOwnerBankStatement . '.' . $request->file('owner_personal_bank_statement')->getClientOriginalExtension();
            $filePath = 'uploads/application-' . $user->id . '/' . $imageOwnerBankStatement;
            Storage::disk('s3')->put($filePath, file_get_contents($request->file('owner_personal_bank_statement')->getRealPath()));
            $input['owner_personal_bank_statement'] = $filePath;
        }

        $this->application->updateApplication($id, $input);

        if ($application->status == '2') {
            $notification = [
                'user_id' => '1',
                'sendor_id' => auth()->user()->id,
                'type' => 'admin',
                'title' => 'Application Resubmitted',
                'body' => $application->business_name . ' application have been resubmitted.',
                'url' => '/admin/applications-list/view/' . $application->id,
                'is_read' => '0'
            ];
            $realNotification = addNotification($notification);

            $realNotification->created_at_date = convertDateToLocal($realNotification->created_at, 'd/m/Y H:i:s');
            event(new AdminNotification($realNotification->toArray()));

            $notification = [
                'user_id' => auth()->user()->id,
                'sendor_id' => '1',
                'type' => 'user',
                'title' => 'Application Resubmitted',
                'body' => 'Your application Resubmitted successfully.',
                'url' => '/my-application',
                'is_read' => '0'
            ];

            $realNotification = addNotification($notification);

            $this->application->updateApplication($id, ['status' => '1']);
        }

        DB::beginTransaction();
        try {
            if ($application->status == '2') {
                Admin::find('1')->notify(new ApplicationResubmit($application));
                $this->application->updateApplication($id, ['reason_reassign' => '']);

                notificationMsg('success', 'Your application has been resubmitted successfully.');
                return redirect()->route('my-application');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        notificationMsg('success', 'Your application has been updated successfully.');

        return redirect()->route('my-application');
    }

    public function downloadDocumentsUploade(Request $request)
    {
        addToLog('application document download', [$request->file], 'general');
        return Storage::disk('s3')->download($request->file);
    }

    public function viewAppImage(Request $request)
    {
        //{{ Config('app.aws_path').'uploads/application-'.$user->id.'/'.$value }}
        // $user = auth()->user();
        // $path = storage_path('uploads/application-'.$user->id.'/'.$request->file);
        // if (!File::exists($path)) {
        //     abort(404);
        // }
        // $file = File::get($path);
        // $type = File::mimeType($path);
        // $response = \Response::make($file, 200);
        // $response->header("Content-Type", $type);
        // return $response;

    }

    public function GetValidateArr($varData = array(), $total_required_files)
    {

        $ArrValidate = [
            'business_type' => 'required',
            'accept_card' => 'required',
            'business_name' => 'required|regex:/^[a-z\d\-_\s\.]+$/i',
            'phone_no' => 'required|numeric',
            'skype_id' => 'required',
            'website_url' => 'required',
            'business_contact_first_name' => 'required|regex:/^[a-z\d\-_\s\.]+$/i',
            'business_contact_last_name' => 'required|regex:/^[a-z\d\-_\s\.]+$/i',
            'business_address1' => 'required|regex:/^[a-z\d\-_\s\.\,]+$/i',
            'residential_address' => 'required|regex:/^[a-z\d\-_\s\.\,]+$/i',
            'monthly_volume' => 'required',
            'country' => 'required',
            'country_code' => 'required',
            'processing_currency' => 'required',
            'technology_partner_id' => 'required',
            'processing_country' => 'required',
            'category_id' => 'required',
            'company_license' => 'required',
            'passport' => 'required|array|min:' . $total_required_files,
            'passport.*' => 'required|mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
            'company_incorporation_certificate' => 'required|mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
            'domain_ownership' => 'required|mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
            'latest_bank_account_statement' => 'required|array|min:' . $total_required_files,
            'latest_bank_account_statement.*' => 'required|mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
            'utility_bill' => 'required|array|min:' . $total_required_files,
            'utility_bill.*' => 'required|mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
            'previous_processing_statement.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
            'extra_document.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
            'owner_personal_bank_statement' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
            'licence_document' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
            'moa_document' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
            'board_of_directors' => 'required'
        ];
        if (!empty($varData)) {
            if (!empty($varData['business_type'])) {
                unset($ArrValidate['business_type']);
            }
            if (!empty($varData['accept_card'])) {
                unset($ArrValidate['accept_card']);
            }
            if (!empty($varData['skype_id'])) {
                unset($ArrValidate['skype_id']);
            }
            if (!empty($varData['monthly_volume'])) {
                unset($ArrValidate['monthly_volume']);
            }
            if (!empty($varData['country'])) {
                unset($ArrValidate['country']);
            }
            if (!empty($varData['country_code'])) {
                unset($ArrValidate['country_code']);
            }
            if (!empty($varData['processing_currency'])) {
                unset($ArrValidate['processing_currency']);
            }
            if (!empty($varData['technology_partner_id'])) {
                unset($ArrValidate['technology_partner_id']);
            }
            if (!empty($varData['processing_country'])) {
                unset($ArrValidate['processing_country']);
            }
            if (!empty($varData['category_id'])) {
                unset($ArrValidate['category_id']);
            }
            if (!empty($varData['company_license'])) {
                unset($ArrValidate['company_license']);
            }
            if (!empty($varData['company_incorporation_certificate'])) {
                unset($ArrValidate['company_incorporation_certificate']);
            }
            if (!empty($varData['domain_ownership'])) {
                unset($ArrValidate['domain_ownership']);
            }
            if (!empty($varData['owner_personal_bank_statement'])) {
                unset($ArrValidate['owner_personal_bank_statement']);
            }
            if (!empty($varData['licence_document'])) {
                unset($ArrValidate['licence_document']);
            }
            if (!empty($varData['moa_document'])) {
                unset($ArrValidate['moa_document']);
            }
            if (!empty($varData['board_of_directors'])) {
                unset($ArrValidate['board_of_directors']);
            }
            if (!empty($varData['passport'])) {
                unset($ArrValidate['passport.*']);
            }
            if (!empty($varData['latest_bank_account_statement'])) {
                unset($ArrValidate['latest_bank_account_statement.*']);
            }
            if (!empty($varData['previous_processing_statement'])) {
                unset($ArrValidate['previous_processing_statement.*']);
            }
            if (!empty($varData['utility_bill'])) {
                unset($ArrValidate['utility_bill.*']);
            }
            if (!empty($varData['extra_document'])) {
                unset($ArrValidate['extra_document.*']);
            }
        }
        return $ArrValidate;
    }
}
