<?php
namespace App\Http\Controllers\Bank;
use App\Events\AdminNotification;
use DB;
use URL;
use Auth;
use File;
use View;
use Mail;
use Input;
use Session;
use Redirect;
use Exception;
use Validator;
use App\Bank;
use App\Admin;
use App\BankApplication;
use App\ImageUpload;
use App\Mail\BankApplicationSubmited;
use App\Mail\BankApplicationReSubmited;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use function GuzzleHttp\json_decode;

class MyApplicationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->moduleTitleS = 'Profile';
        $this->moduleTitleP = 'bank.myapplication';

        $this->application = new BankApplication;

        view()->share('moduleTitleP', $this->moduleTitleP);
        view()->share('moduleTitleS', $this->moduleTitleS);
    }

    public function create()
    {
        $application = $this->application->FindDataFromUser(auth()->guard('bankUser')->user()->id);
        if(!$application)
            return view($this->moduleTitleP . '.my-application');
        else
            return redirect()->route('bank.my-application.detail');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request, 
            [
                "company_name" => "required|regex:/^[a-z\d\-_\s\.]+$/i",
                "website_url" => "required",
                "company_registered_number_year" => "required|numeric|digits_between:0,4",
                "company_address" => "required|max:300|regex:/^[a-z\d\-_\s\.\,]+$/i",
                "settlement_method_for_crypto" => "required",
                "settlement_method_for_fiat" => "required",
                "mcc_codes" => "required",
                "descriptors" => "required",            
                "authorized_individual_name.*" => "required|regex:/^[a-z\d\-_\s\.]+$/i",
                "authorized_individual_phone_number.*" => "required|numeric",
                "authorized_individual_email.*" => "required",
                "license_image" => "required_with:is_license_applied",
                'passport.*' => 'required|mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'latest_bank_account_statement.*' => 'required|mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'utility_bill.*' => 'required|mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'company_incorporation_certificate' => 'required|mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'article_of_accociasion' => 'nullable|mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'tax_certificate' => 'nullable|mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840'
            ],
            [
                'company_name.regex' => 'Please Enter Only Alphanumeric Characters.',
                'company_address.regex' => 'Please Enter Only Alphanumeric Characters.',
                'authorized_individual_name.*.regex' => 'Please Enter Only Alphanumeric Characters.',
                'passport.*.max' => 'The passport size may not be greater than 35 MB.',
                'latest_bank_account_statement.*.max' => 'The latest bank account statement size may not be greater than 35 MB.',
                'utility_bill.*.max' => 'The utility bill size may not be greater than 35 MB.',
                'company_incorporation_certificate.max' => 'The company incorporation certificate size may not be greater than 35 MB.',
                'article_of_accociasion' => 'The Article of accociasion size may not be greater than 35 MB.',
                'tax_certificate' => 'The Tax certificate size may not be greater than 35 MB.'
            ]
        );

        $input = \Arr::except($request->all(),['_token','action','authorized_individual_name','authorized_individual_phone_number','authorized_individual_email','license_image']);

        foreach($request->authorized_individual_email as $key => $email){
            $authorized_individual[$key]['name'] = $request->authorized_individual_name[$key];
            $authorized_individual[$key]['email'] = $request->authorized_individual_email[$key];
            $authorized_individual[$key]['phone_number'] = $request->authorized_individual_phone_number[$key];
        }

        if ($request->hasFile('license_image')) {
            $license_image = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
            $license_image = $license_image . '.' . $request->file('license_image')->getClientOriginalExtension();
            $filePath = 'uploads/bank-application-' . auth()->guard('bankUser')->user()->id . '/' . $license_image;
            Storage::disk('s3')->put($filePath, file_get_contents($request->file('license_image')->getRealPath()));
            $input['license_image'] = $filePath;
        }
        
        $input['authorised_individual'] = json_encode($authorized_individual);
        $input['bank_id'] = auth()->guard('bankUser')->user()->id;

        if ($request->hasFile('passport')) {
            $files = $request->file('passport');
            $passportArr = [];
            foreach ($files as $key => $value) {
                $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                $filePath = 'uploads/bank-application-' . auth()->guard('bankUser')->user()->id . '/' . $imageDocument;
                Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                array_push($passportArr, $filePath);
            }
            $input['passport'] = json_encode($passportArr);
        }
        if ($request->hasFile('latest_bank_account_statement')) {
            $files = $request->file('latest_bank_account_statement');
            $statementArr = [];
            foreach ($files as $key => $value) {
                $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                $filePath = 'uploads/bank-application-' . auth()->guard('bankUser')->user()->id . '/' . $imageDocument;
                Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                array_push($statementArr, $filePath);
            }
            $input['latest_bank_account_statement'] = json_encode($statementArr);
        }
        if ($request->hasFile('utility_bill')) {
            $files = $request->file('utility_bill');
            $utilityArr = [];
            foreach ($files as $key => $value) {
                $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                $filePath = 'uploads/bank-application-' . auth()->guard('bankUser')->user()->id . '/' . $imageDocument;
                Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                array_push($utilityArr, $filePath);
            }
            $input['utility_bill'] = json_encode($utilityArr);
        }
        if ($request->hasFile('company_incorporation_certificate')) {
            $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
            $imageNameCertificate = $imageNameCertificate . '.' . $request->file('company_incorporation_certificate')->getClientOriginalExtension();
            $filePath = 'uploads/bank-application-' . auth()->guard('bankUser')->user()->id . '/' . $imageNameCertificate;
            Storage::disk('s3')->put($filePath, file_get_contents($request->file('company_incorporation_certificate')->getRealPath()));
            $input['company_incorporation_certificate'] = $filePath;
        }
        if ($request->hasFile('article_of_accociasion')) {
            $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
            $imageNameCertificate = $imageNameCertificate . '.' . $request->file('article_of_accociasion')->getClientOriginalExtension();
            $filePath = 'uploads/bank-application-' . auth()->guard('bankUser')->user()->id . '/' . $imageNameCertificate;
            Storage::disk('s3')->put($filePath, file_get_contents($request->file('article_of_accociasion')->getRealPath()));
            $input['article_of_accociasion'] = $filePath;
        }
        if ($request->hasFile('tax_certificate')) {
            $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
            $imageNameCertificate = $imageNameCertificate . '.' . $request->file('tax_certificate')->getClientOriginalExtension();
            $filePath = 'uploads/bank-application-' . auth()->guard('bankUser')->user()->id . '/' . $imageNameCertificate;
            Storage::disk('s3')->put($filePath, file_get_contents($request->file('tax_certificate')->getRealPath()));
            $input['tax_certificate'] = $filePath;
        }
        
        DB::beginTransaction();
        try {
            $this->application->storeData($input);
            
            DB::commit();

            $data['company_name'] = $input['company_name'];
            $data['company_address'] = $input['company_address'];
            $data['phone_number'] = $authorized_individual[0]['phone_number'];
            $data['email'] = $authorized_individual[0]['email'];

            Mail::to(config('notification.default_email'))->send(new BankApplicationSubmited($data));
            
            notificationMsg('success', "Application submitted Successfully.");
            
            return redirect()->route('bank.my-application.detail');
        } catch (Exception $e) {
            DB::rollBack();
            
            notificationMsg('error', 'Something went wrong. Try Again.');
            
            return redirect()->back()->withInput($request->all());
        }
    }

    public function detail(Request $request)
    {
        $data = $this->application->FindDataFromUser(auth()->guard('bankUser')->user()->id);
        return view($this->moduleTitleP . '.my-application-view',compact('data'));
    }

    public function edit(Request $request)
    {
        $application = $this->application->FindDataFromUser(auth()->guard('bankUser')->user()->id);

        //alow edit only if the application is pending or reassigned.
        if($application->status == '0' || $application->status == '3'){
            return view($this->moduleTitleP . '.edit',compact('application'));
        }else{
            return redirect()->route('bank.my-application.detail');
        }
    }

    public function update(Request $request)
    {
        $this->validate(
            $request, 
            [
                "company_name" => "required|regex:/^[a-z\d\-_\s\.]+$/i",
                "website_url" => "required",
                "company_registered_number_year" => "required|numeric|digits_between:0,4",
                "company_address" => "required|max:300|regex:/^[a-z\d\-_\s\.\,]+$/i",
                "settlement_method_for_crypto" => "required",
                "settlement_method_for_fiat" => "required",
                "mcc_codes" => "required",
                "descriptors" => "required",            
                "authorized_individual_name.*" => "required|regex:/^[a-z\d\-_\s\.]+$/i",
                "authorized_individual_phone_number.*" => "required|numeric",
                "authorized_individual_email.*" => "required",
                'passport.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'latest_bank_account_statement.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'utility_bill.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'company_incorporation_certificate' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'article_of_accociasion' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'tax_certificate' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840'
            ],
            [
                'company_name.regex' => 'Please Enter Only Alphanumeric Characters.',
                'company_address.regex' => 'Please Enter Only Alphanumeric Characters.',
                'authorized_individual_name.*.regex' => 'Please Enter Only Alphanumeric Characters.',
                'passport.*.max' => 'The passport size may not be greater than 35 MB.',
                'latest_bank_account_statement.*.max' => 'The latest bank account statement size may not be greater than 35 MB.',
                'utility_bill.*.max' => 'The utility bill size may not be greater than 35 MB.',
                'company_incorporation_certificate.max' => 'The company incorporation certificate size may not be greater than 35 MB.',
                'article_of_accociasion' => 'The Article of accociasion size may not be greater than 35 MB.',
                'tax_certificate' => 'The Tax certificate size may not be greater than 35 MB.'
            ]
        );

        $input = \Arr::except($request->all(),['_token','action','authorized_individual_name','authorized_individual_phone_number','authorized_individual_email','license_image']);
        $application = $this->application->findData($request->id);

        foreach($request->authorized_individual_email as $key => $email){
            $authorized_individual[$key]['name'] = $request->authorized_individual_name[$key];
            $authorized_individual[$key]['email'] = $request->authorized_individual_email[$key];
            $authorized_individual[$key]['phone_number'] = $request->authorized_individual_phone_number[$key];
        }

        if ($request->hasFile('license_image')) {
            $license_image = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
            $license_image = $license_image . '.' . $request->file('license_image')->getClientOriginalExtension();
            $filePath = 'uploads/bank-application-' . auth()->guard('bankUser')->user()->id . '/' . $license_image;
            Storage::disk('s3')->put($filePath, file_get_contents($request->file('license_image')->getRealPath()));
            $input['license_image'] = $filePath;
        }
        
        $input['authorised_individual'] = json_encode($authorized_individual);
        $input['status'] = '0';
        $application = $this->application->findData($request->id);

        if ($request->hasFile('passport')) {
            $old_passport_documents = [];
            if($application->passport != null) {
                $old_passport_documents = json_decode($application->passport);
            }
            $files = $request->file('passport');
            $passportArr = [];
            foreach ($files as $key => $value) {
                $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                $filePath = 'uploads/bank-application-' . $application->bank_id . '/' . $imageDocument;
                Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                array_push($passportArr, $filePath);
            }
            $updated_passport_documents = array_merge($old_passport_documents, $passportArr);
            $input['passport'] = json_encode($updated_passport_documents);
        }

        if ($request->hasFile('latest_bank_account_statement')) {
            $old_account_statement_documents = [];
            if($application->latest_bank_account_statement != null) {
                $old_account_statement_documents = json_decode($application->latest_bank_account_statement);
            }
            $files = $request->file('latest_bank_account_statement');
            $statementArr = [];
            foreach ($files as $key => $value) {
                $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                $filePath = 'uploads/bank-application-' . $application->bank_id . '/' . $imageDocument;
                Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                array_push($statementArr, $filePath);
            }
            $updated_account_statement_documents = array_merge($old_account_statement_documents, $statementArr);
            $input['latest_bank_account_statement'] = json_encode($updated_account_statement_documents);
        }

        if ($request->hasFile('utility_bill')) {
            $old_utilityBill = [];
            if($application->utility_bill != null) {
                $old_utilityBill = json_decode($application->utility_bill);
            }
            $files = $request->file('utility_bill');
            $utilityBillArr = [];
            foreach ($files as $key => $value) {
                $imageDocument = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
                $imageDocument = $imageDocument . '.' . $value->getClientOriginalExtension();
                $filePath = 'uploads/bank-application-' . $application->bank_id . '/' . $imageDocument;
                Storage::disk('s3')->put($filePath, file_get_contents($value->getRealPath()));
                array_push($utilityBillArr, $filePath);
            }
            $utilityBill = array_merge($old_utilityBill, $utilityBillArr);
            $input['utility_bill'] = json_encode($utilityBill);
        }

        if ($request->hasFile('company_incorporation_certificate')) {
            Storage::disk('s3')->delete($application->company_incorporation_certificate);
            $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
            $imageNameCertificate = $imageNameCertificate . '.' . $request->file('company_incorporation_certificate')->getClientOriginalExtension();
            $filePath = 'uploads/bank-application-' . $application->bank_id . '/' . $imageNameCertificate;
            Storage::disk('s3')->put($filePath, file_get_contents($request->file('company_incorporation_certificate')->getRealPath()));
            $input['company_incorporation_certificate'] = $filePath;
        }

        if ($request->hasFile('article_of_accociasion')) {
            Storage::disk('s3')->delete($application->article_of_accociasion);
            $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
            $imageNameCertificate = $imageNameCertificate . '.' . $request->file('article_of_accociasion')->getClientOriginalExtension();
            $filePath = 'uploads/bank-application-' . $application->bank_id . '/' . $imageNameCertificate;
            Storage::disk('s3')->put($filePath, file_get_contents($request->file('article_of_accociasion')->getRealPath()));
            $input['article_of_accociasion'] = $filePath;
        }

        if ($request->hasFile('tax_certificate')) {
            Storage::disk('s3')->delete($application->tax_certificate);
            $imageNameCertificate = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
            $imageNameCertificate = $imageNameCertificate . '.' . $request->file('tax_certificate')->getClientOriginalExtension();
            $filePath = 'uploads/bank-application-' . $application->bank_id . '/' . $imageNameCertificate;
            Storage::disk('s3')->put($filePath, file_get_contents($request->file('tax_certificate')->getRealPath()));
            $input['tax_certificate'] = $filePath;
        }

        DB::beginTransaction();
        try {
            
            if($application->status == '3'){

                $data['company_name'] = $input['company_name'];
                $data['company_address'] = $input['company_address'];
                $data['phone_number'] = $authorized_individual[0]['phone_number'];
                $data['email'] = $authorized_individual[0]['email'];

                Mail::to(config('notification.default_email'))->send(new BankApplicationReSubmited($data));
            }
            $application->updateApplication($request->id,$input);
            
            DB::commit();
            
            notificationMsg('success', "Application updated Successfully.");
            
            return redirect()->route('bank.my-application.detail');
        } catch (Exception $e) {
            
            DB::rollBack();
            
            notificationMsg('error', 'Something went wrong. Try Again.');
            
            return redirect()->back()->withInput($request->all());
        }
    }
}