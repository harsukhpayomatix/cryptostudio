<?php

namespace App\Http\Controllers\Admin;

use Str;
use Validator;
use DB;
use App\Bank;
use App\BankApplication;
use App\Categories;
use App\Mail\BankApplicationApproved;
use App\Mail\BankApplicationRejected;
use App\Mail\BankApplicationReassigned;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewBankEmail;
use App\MailTemplates;
use Mail;
use Storage;

class BankApplicationController extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->bankApplication = new BankApplication;
        $this->bank = new Bank;
        $this->template = new MailTemplates;

        $this->moduleTitleS = 'Bank';
        $this->moduleTitleP = 'admin.applications.bankapplication';

        view()->share('moduleTitleP', $this->moduleTitleP);
        view()->share('moduleTitleS', $this->moduleTitleS);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = \Arr::except($request->all(), array('_token', '_method'));
        if (isset($input['noList'])) {
            $noList = $input['noList'];
        } else {
            $noList = 15;
        }
        $applications = $this->bankApplication->getData($input, $noList);
        $companynames = $this->bankApplication->getCompanyName();
        // dd($data);
        return view($this->moduleTitleP . '.all-applications', compact('applications','companynames'));
    }

    public function pending_application(Request $request)
    {
        $input = \Arr::except($request->all(), array('_token', '_method'));
        if (isset($input['noList'])) {
            $noList = $input['noList'];
        } else {
            $noList = 15;
        }
        $applications = $this->bankApplication->getPendigApplications($input, $noList);
        $companynames = $this->bankApplication->getCompanyName();
        // dd($data);
        return view($this->moduleTitleP . '.all-applications', compact('applications','companynames'));
    }
    
    public function approved_application(Request $request)
    {
        $input = \Arr::except($request->all(), array('_token', '_method'));
        if (isset($input['noList'])) {
            $noList = $input['noList'];
        } else {
            $noList = 15;
        }
        $applications = $this->bankApplication->getApprovedApplications($input, $noList);
        $companynames = $this->bankApplication->getCompanyName();
        // dd($data);
        return view($this->moduleTitleP . '.all-applications', compact('applications','companynames'));
    }

    public function reassign_application(Request $request)
    {
        $input = \Arr::except($request->all(), array('_token', '_method'));
        if (isset($input['noList'])) {
            $noList = $input['noList'];
        } else {
            $noList = 15;
        }
        $applications = $this->bankApplication->getReassignApplications($input, $noList);
        $companynames = $this->bankApplication->getCompanyName();
        // dd($data);
        return view($this->moduleTitleP . '.all-applications', compact('applications','companynames'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $application = $this->bankApplication->findData($id);
        return view($this->moduleTitleP . '.detail', compact('application'));
    }

    public function downloadBankApplicationDocumentsUpload(Request $request)
    {
        return Storage::disk('s3')->download($request->file);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $application = $this->bankApplication->findData($id);
        return view($this->moduleTitleP . '.edit', compact('application'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank)
    {
        $this->validate(
            $request, 
            [
                "company_name" => "required",
                "website_url" => "required",
                "company_registered_number_year" => "required|numeric|digits_between:0,4",
                "company_address" => "required|max:300",
                "settlement_method_for_crypto" => "required",
                "settlement_method_for_fiat" => "required",
                "mcc_codes" => "required",
                "descriptors" => "required",            
                "authorized_individual_name.*" => "required",
                "authorized_individual_phone_number.*" => "required",
                "authorized_individual_email.*" => "required",
                'passport.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'latest_bank_account_statement.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'utility_bill.*' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'company_incorporation_certificate' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'article_of_accociasion' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840',
                'tax_certificate' => 'mimes:jpg,jpeg,png,pdf,txt,doc,docx,xls,xlsx,zip|max:35840'
            ],
            [
                'passport.*.max' => 'The passport size may not be greater than 35 MB.',
                'latest_bank_account_statement.*.max' => 'The latest bank account statement size may not be greater than 35 MB.',
                'utility_bill.*.max' => 'The utility bill size may not be greater than 35 MB.',
                'company_incorporation_certificate.max' => 'The company incorporation certificate size may not be greater than 35 MB.',
                'article_of_accociasion' => 'The Article of accociasion size may not be greater than 35 MB.',
                'tax_certificate' => 'The Tax certificate size may not be greater than 35 MB.'
            ]
        );

        $input = \Arr::except($request->all(),['_token','action','authorized_individual_name','authorized_individual_phone_number','authorized_individual_email','license_image','id']);
        $application = $this->bankApplication->findData($request->id);

        foreach($request->authorized_individual_email as $key => $email){
            $authorized_individual[$key]['name'] = $request->authorized_individual_name[$key];
            $authorized_individual[$key]['email'] = $request->authorized_individual_email[$key];
            $authorized_individual[$key]['phone_number'] = $request->authorized_individual_phone_number[$key];
        }

        if ($request->hasFile('license_image')) {
            $license_image = time() . rand(0, 10000000000000) . pathinfo(rand(111111111111, 999999999999), PATHINFO_FILENAME);
            $license_image = $license_image . '.' . $request->file('license_image')->getClientOriginalExtension();
            $filePath = 'uploads/bank-application-' . $application->bank_id . '/' . $license_image;
            Storage::disk('s3')->put($filePath, file_get_contents($request->file('license_image')->getRealPath()));
            $input['license_image'] = $filePath;
        }

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

        $input['authorised_individual'] = json_encode($authorized_individual);
        
        DB::beginTransaction();
        try {
            $this->bankApplication->updateApplication($request->id,$input);
            
            DB::commit();
            
            notificationMsg('success', "Application updated Successfully.");
            
            return redirect()->route('application-bank.detail',$request->id);
        } catch (Exception $e) {
            DB::rollBack();
            
            notificationMsg('error', 'Something went wrong. Try Again.');
            
            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->bank->destroyData($id);
        notificationMsg('success', 'Bank Delete Successfully!');
        return redirect()->route('banks.index');
    }

    public function changeStatus(Request $request, $id)
    {
        $status = $request->get('status');
        $this->bank->updateData($id, ['is_active' => $status]);
        notificationMsg('success', 'Status Change Successfully!');
        return redirect()->route('banks.index');
    }


    public function applicationApprove(Request $request)
    {
        $application = $this->bankApplication->findData($request->id);
        if($application->updateApplication($request->id, ['status' => '1'])){
            $bank = $this->bank->findData($application->bank_id);
            $data['user_name'] = $application->company_name;
            Mail::to($bank->email)->send(new BankApplicationApproved($data));
            return response()->json([
                'success' => '1'
            ]);
        }else{
            return response()->json([
                'success' => '0'
            ]);
        }
    }

    public function applicationReject(Request $request)
    {
        $application = $this->bankApplication->findData($request->id);
        if($application->updateApplication($request->id, ['status' => '2', 'reject_reason' => $request->reject_reason])){
            $bank = $this->bank->findData($application->bank_id);
            $data['user_name'] = $application->company_name;
            $data['reason'] = $request->reject_reason;
            Mail::to($bank->email)->send(new BankApplicationRejected($data));
            notificationMsg('success', 'Application Rejected Successfully!');
        }else{
            notificationMsg('error', 'Something went wrong. Please try again.');
        }
        return redirect()->back();
    }

    public function applicationReAssign(Request $request)
    {
        $application = $this->bankApplication->findData($request->id);
        if($application->updateApplication($request->id, ['status' => '3', 'reassign_reason' => $request->reassign_reason])){
            $bank = $this->bank->findData($application->bank_id);
            
            $data['user_name'] = $application->company_name;
            $data['reason'] = $request->reassign_reason;
            Mail::to($bank->email)->send(new BankApplicationReassigned($data));
            notificationMsg('success', 'Application Reassigned Successfully!');
        }else{
            notificationMsg('error', 'Something went wrong. Please try again.');
        }
        return redirect()->back();
    }
    
    public function deleteAllApplication(Request $request)
    {
        if ($request->get('type') == 'forall') {
            $allID = $request->get('id');
            foreach ($allID as $key => $value) {
                $this->bankApplication->softDelete($value);
            }
            return response()->json([
                'success' => true,
            ]);
        }
    }
}
