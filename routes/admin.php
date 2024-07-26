<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
  return redirect('admin/dashboard');
})->name('admin.dashboard');

Route::group(['middleware' => 'notification_read'], function () {
  /******************* Admin notifications Start ************************/
  Route::get('admin-notifications', 'Admin\NotificationController@adminNotifications')->name('admin-notifications');
  Route::get('read-admin-notifications/{id}', 'Admin\NotificationController@readAdminNotifications')->name('read-admin-notifications');
  Route::post('send-admin-firebase-token', 'Admin\NotificationController@sendAdminFirebaseToken')->name('send-admin-firebase-token')->middleware(['XSS']);
  Route::post('send-admin-firebase-notification', 'Admin\NotificationController@sendAdminFirebaseNotification')->name('send-admin-firebase-notification')->middleware(['XSS']);
  /******************* Admin notifications End ************************/

  /*****MID Details Management Start ***********************************************/
  Route::resource('mid-feature-management', 'Admin\MIDDetailsController');
  Route::get('get-middetails-data', 'Admin\MIDDetailsController@getMIDData')->name('get-middetails-data');
  Route::get('get-middetails-data/edit/{id?}', 'Admin\MIDDetailsController@edit')->name('admin.middetails.edit');
  Route::get('get-middetails-data/show/{id?}', 'Admin\MIDDetailsController@show')->name('admin.middetails.show');
  Route::patch('get-middetails-data/update/{id?}', 'Admin\MIDDetailsController@update')->name('admin.middetails.update')->middleware(['XSS']);
  Route::post('getsubmid', 'Admin\MIDDetailsController@getSubMID')->name('getsubmid')->middleware(['XSS']);
  /*****MID Details Management End ************************************************/

  Route::get('samples/{file_name}', function ($file_name = null) {
    $path = storage_path() . '/' . 'samples' . '/csv/' . $file_name;
    if (file_exists($path)) {
      return Response::download($path);
    }
  });

  /******************* Admin Routes Resource Start ************************/
  Route::resource('admin-user', 'Admin\AdminsController');
  Route::post('delete-user', 'Admin\AdminsController@deleteUser')->name('delete-user');
  Route::get('admin-status/{id}', 'Admin\AdminsController@changeStatus')->name('admin-status');
  Route::get('admin-user-password-expired/{id}', 'Admin\AdminsController@passwordExpired')->name('admin-user-password-expired');
  Route::post('admin-user/upload_user', 'Admin\AdminsController@bulkUpload')->name('upload-user')->middleware(['XSS']);

  Route::get('agreement-generate', 'Admin\AdminsController@agreementGenerate')->name('agreement-generate');
  Route::post('agreement-generate-store', 'Admin\AdminsController@agreementGenerateStore')->name('agreement-generate-store')->middleware(['XSS']);

  Route::get('agreement-upload', 'Admin\AdminsController@agreementUpload')->name('agreement-upload');
  Route::post('agreement-upload-store', 'Admin\AdminsController@agreementUploadStore')->name('agreement-upload-store')->middleware(['XSS']);
  Route::post('agreement-upload-store-rp', 'Admin\AdminsController@agreementUploadStoreRP')->name('agreement-upload-store-rp')->middleware(['XSS']);
  /******************* Admin Routes Resource End ************************/

  /********************Bank Routes Resource START ************************/
  Route::resource('banks', 'Admin\BankController');
  Route::get('bank-status/{id}', 'Admin\BankController@changeStatus')->name('bank-status');
  Route::post('send-bank-multi-mail', 'Admin\BankController@sendMultiMail')->name('send-bank-multi-mail')->middleware(['XSS']);
  Route::post('delete-bank', 'Admin\BankController@deleteMultiBank')->name('delete.bank.user')->middleware(['XSS']);
  /********************Agent Routes Resource START ************************/
  Route::resource('agents', 'Admin\AgentController')->middleware(['XSS']);
  Route::get('agents/bank-details/{id}', 'Admin\AgentController@getAgentBankDetails')->name('admin.agent.bankDetails');
  Route::get('agent-status/{id}', 'Admin\AgentController@changeStatus')->name('agent-status');
  Route::post('delete-agent', 'Admin\AgentController@deleteMultiAgent')->name('delete.agent.user')->middleware(['XSS']);

  Route::post('send-rp-multi-mail', 'Admin\AgentController@sendMultiMail')->name('send-rp-multi-mail')->middleware(['XSS']);

  Route::post('rp-agreement-sent', 'Admin\AgreementDocumentsRPController@AgreementSent')->name('rp-agreement-sent')->middleware(['XSS']);
  Route::post('rp-agreement-received', 'Admin\AgreementDocumentsRPController@AgreementReceived')->name('rp-agreement-received')->middleware(['XSS']);
  Route::post('rp-cross-signed-agreement-sent', 'Admin\AgreementDocumentsRPController@CrossSignedAgreementSent')->name('rp-cross-signed-agreement-sent')->middleware(['XSS']);
  Route::post('rp-agreement/reassign/', ['uses' => 'Admin\AgreementDocumentsRPController@reassign_agreement', 'as' => 'agreement-reassign'])->middleware(['XSS']);
  Route::post('agents/export', 'Admin\AgentController@export')->name('agents.export');
  /********************Agent Routes Resource End ************************/

  /********************WL Agent Routes Resource START ************************/
  Route::resource('wl-agents', 'Admin\WLAgentController')->middleware(['XSS']);
  Route::post('wl-agents/export', 'Admin\WLAgentController@export')->name('wl-agents.export')->middleware(['XSS']);
  Route::get('wl-agent-status/{id}', 'Admin\WLAgentController@changeStatus')->name('wl-agent-status');
  Route::post('delete-wl-agents', 'Admin\WLAgentController@deleteMultiWlAgent')->name('delete.wl.agent')->middleware(['XSS']);

  Route::get('wl-agent-merchant', 'Admin\WLAgentMerchantManagementController@allMerchant')->name('wl-agent-merchant-all');
  Route::get('wl-agent-merchant/{id}', 'Admin\WLAgentMerchantManagementController@index')->name('wl-agent-merchant');
  Route::post('wl-agent-merchant-export-all', 'Admin\WLAgentMerchantManagementController@exportAll')->name('wl-agent-merchant-export-all')->middleware(['XSS']);
  Route::post('wl-agent-merchant-export/{id}', 'Admin\WLAgentMerchantManagementController@export')->name('wl-agent-merchant-export')->middleware(['XSS']);
  Route::get('wl-agent-merchant-create/{id}', 'Admin\WLAgentMerchantManagementController@create')->name('wl-agent-merchant-create');
  Route::post('wl-agent-merchant-store', 'Admin\WLAgentMerchantManagementController@store')->name('wl-agent-merchant-store')->middleware(['XSS']);
  Route::get('wl-agent-merchant-show/{id}', 'Admin\WLAgentMerchantManagementController@show')->name('wl-agent-merchant-show');
  Route::get('wl-agent-merchant-edit/{id}', 'Admin\WLAgentMerchantManagementController@edit')->name('wl-agent-merchant-edit');
  Route::put('wl-agent-merchant-update/{id}', 'Admin\WLAgentMerchantManagementController@update')->name('wl-agent-merchant-update')->middleware(['XSS']);
  Route::delete('wl-agent-merchant-destroy/{id}', 'Admin\WLAgentMerchantManagementController@destroy')->name('wl-agent-merchant-destroy');
  Route::get('wl-agent-csv-export', '\App\LazyCSVExport\WlAgentUserCSVExport@download')->name('wl-agent-csv-export');
  Route::post('delete-all-wl-agent-merchant', 'Admin\WLAgentMerchantManagementController@deleteMultiMerchantAgent')->name('delete.wl.agent.merchant')->middleware(['XSS']);
  Route::post("/wl-agent-merchant-toggle-bin", "Admin\WLAgentMerchantManagementController@toggleBinChecker")->name("wl.agent.merchant.togglebin")->middleware(['XSS']);

  /********************WL Agent Routes Resource End ************************/

  /************************Profile Details Route Start **********************/
  Route::get('profile', 'AdminController@profile')->name('admin-profile');
  Route::post('user-change-pass', 'AdminController@changePass')->name('user-change-pass')->middleware(['XSS']);
  Route::patch('update-profile/{id}', 'AdminController@updateProfile')->name('update-profile')->middleware(['XSS']);
  /************************Profile Details Route End **********************/

  /****************Admin  Roles module Start *****************************/
  Route::get('roles', ['as' => 'roles.index', 'uses' => 'Admin\RoleController@index']);
  Route::get('roles/create', ['as' => 'roles.create', 'uses' => 'Admin\RoleController@create']);
  Route::post('roles/create', ['as' => 'roles.store', 'uses' => 'Admin\RoleController@store'])->middleware(['XSS']);
  Route::get('roles/{id}', ['as' => 'roles.show', 'uses' => 'Admin\RoleController@show']);
  Route::get('roles/{id}/edit', ['as' => 'roles.edit', 'uses' => 'Admin\RoleController@edit']);
  Route::patch('roles/{id}', ['as' => 'roles.update', 'uses' => 'Admin\RoleController@update'])->middleware(['XSS']);
  Route::delete('roles/{id}', ['as' => 'roles.destroy', 'uses' => 'Admin\RoleController@destroy']);
  /****************Admin  Roles module End *****************************/

  /**************Admin Dashboard module Start ****************************/
  Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
  Route::post('dashboard/transaction-summary', 'AdminController@transactionSummaryFilter')->name('dashboard.transactionSummary')->middleware(['XSS']);
  Route::post('dashboard/merchant-transaction-percentage', 'AdminController@getMerchantTxnPercentage')->name('dashboard.merchantTxnPercentage')->middleware(['XSS']);
  Route::post('dashboard/rp-merchant-overview', 'AdminController@getRpMerchantOverview')->name('dashboard.rp.merchant.overview')->middleware(['XSS']);
  /**************Admin Dashboard module End   ****************************/
  Route::get('technical', 'Admin\AdminsController@technical')->name('admin.technical');

  Route::get('block-system', 'Admin\BlockSystemController@index')->name('block-system');
  Route::get('mass-transaction-action', 'Admin\MerchantTransactionController@massAction')->name('mass-transaction-action.index');
  Route::post('store/mass-transaction-action', 'Admin\MerchantTransactionController@massActionStore')->name('mass-transaction-action.store')->middleware(['XSS']);
  Route::get('add/block-system', 'Admin\BlockSystemController@add')->name('add.block-system');
  Route::post('store/block-system', 'Admin\BlockSystemController@store')->name('store.block-system')->middleware(['XSS']);
  Route::get('edit/block-system/{id}', 'Admin\BlockSystemController@edit')->name('edit.block-system');
  Route::post('update/block-system/{id}', 'Admin\BlockSystemController@update')->name('update.block-system')->middleware(['XSS']);
  Route::delete('delete/block-system/{id}', 'Admin\BlockSystemController@destroy')->name('delete.block-system');


  // Export Applications
  Route::get('applications-list/export', ['uses' => 'Admin\ApplicationController@exportAllApplications', 'as' => 'admin.applications.exportAllApplications']);
  Route::get('applications-list/complete/export', ['uses' => 'Admin\ApplicationController@exportAllCompleted', 'as' => 'admin.applications.exportAllCompleted']);
  Route::get('applications-list/approved/export', ['uses' => 'Admin\ApplicationController@exportAllApproved', 'as' => 'admin.applications.exportAllApproved']);
  Route::get('applications-list/rejected/export', ['uses' => 'Admin\ApplicationController@exportAllRejected', 'as' => 'admin.applications.exportAllRejected']);
  Route::get('applications-list/not-interested/export', ['uses' => 'Admin\ApplicationController@exportAllNotInterested', 'as' => 'admin.applications.exportAllNotInterested']);
  Route::get('applications-list/terminated/export', ['uses' => 'Admin\ApplicationController@exportAllTerminated', 'as' => 'admin.applications.exportAllTerminated']);
  Route::get('applications-list/deleted/export', ['uses' => 'Admin\ApplicationController@exportAllDeleted', 'as' => 'admin.applications.exportAllDeleted']);
  Route::get('applications-list/agreement-send/export', ['uses' => 'Admin\ApplicationController@exportAllAgreementSend', 'as' => 'admin.applications.exportAllAgreementSend']);
  Route::get('applications-list/agreement-signed/export', ['uses' => 'Admin\ApplicationController@exportAllAgreementSigned', 'as' => 'admin.applications.exportAllAgreementSigned']);
  Route::get('applications-list/agreement-received/export', ['uses' => 'Admin\ApplicationController@exportAllAgreementReceived', 'as' => 'admin.applications.exportAllAgreementReceived']);
  Route::get('applications-list/sent-to-bank/export', ['uses' => 'Admin\ApplicationController@exportAllSentToBank', 'as' => 'admin.applications.exportAllSentToBank']);

  Route::get('applications-list/view/{id?}', ['uses' => 'Admin\ApplicationController@view', 'as' => 'application.view']);
  Route::post('applications/partners/update', ['uses' => 'Admin\ApplicationController@update_partners', 'as' => 'admin.applications.partner.update'])->middleware(['XSS']);
  Route::get('applications-list/edit/{id?}', ['uses' => 'Admin\ApplicationController@edit_application', 'as' => 'application.edit']);
  Route::put('applications-list/update/{id?}', ['uses' => 'Admin\ApplicationController@update_application', 'as' => 'admin.applications.update'])->middleware(['XSS']);

  // * Resend the agreement email
  Route::post('applications-list/resendEmail', 'Admin\ApplicationController@resendAgreementEmail')->name('resend.agreement.mail')->middleware(['XSS']);
  Route::post('applications-list/apmContent/{id}', 'Admin\ApplicationController@apmRatesContent')->name('apm.modal.content')->middleware(['XSS']);
  Route::post('applications-list/apm-store', 'Admin\ApplicationController@storeApmRates')->name('apmrates.store')->middleware(['XSS']);

  Route::post('applications/approved/', ['uses' => 'Admin\ApplicationController@approved_application', 'as' => 'application-done'])->middleware(['XSS']);
  Route::post('applications/reject/', ['uses' => 'Admin\ApplicationController@reject_application', 'as' => 'application-reject'])->middleware(['XSS']);
  Route::post('applications/reassign/', ['uses' => 'Admin\ApplicationController@reassign_application', 'as' => 'application-reassign'])->middleware(['XSS']);
  Route::post('applications/changeAgent/', ['uses' => 'Admin\ApplicationController@changeAgent', 'as' => 'admin.applications.changeAgent'])->middleware(['XSS']);
  Route::delete('admin-applications-delete/{id}', 'Admin\ApplicationController@destroy')->name('admin.applications.delete');
  Route::post('admin-applications-restore/{id}', 'Admin\ApplicationController@restore')->name('admin.applications.restore')->middleware(['XSS']);
  Route::get('application-pdf/{id}', 'Admin\ApplicationController@downloadPDF')->name('application-pdf');
  Route::get('application-docs/{id}', 'Admin\ApplicationController@downloadDOCS')->name('application-docs');
  Route::get('application-back-inprogress/{id}', 'Admin\ApplicationController@backInprogress')->name('application-back-inprogress');
  Route::post('application-agreement-sent', 'Admin\ApplicationController@applicationAgreementSent')->name('application-agreement-sent')->middleware(['XSS']);
  Route::post('application-agreement-received', 'Admin\ApplicationController@applicationAgreementReceived')->name('application-agreement-received')->middleware(['XSS']);
  Route::post('agreement/reassign/', ['uses' => 'Admin\ApplicationController@reassign_agreement', 'as' => 'agreement-reassign'])->middleware(['XSS']);
  Route::post('application-terminate', 'Admin\ApplicationController@updateTerminate')->name('application-terminate')->middleware(['XSS']);
  Route::post('applications-move-in-not-interested', 'Admin\ApplicationController@applicationMoveInNotInterested')->name('applications-move-in-not-interested')->middleware(['XSS']);
  Route::get('application-restore/{id}', 'Admin\ApplicationController@applicationRestore')->name('application-restore');
  Route::get('change-notInterestAppStatus/{id?}', 'Admin\ApplicationController@changeNotInterestAppStatus')->name('change.notInterest.app');

  Route::post('application-delete-docs', 'Admin\ApplicationController@applicationdeletedocs')->name('application-delete-docs')->middleware(['XSS']);

  Route::get('downloadDocumentsUploadeAdmin', 'Admin\ApplicationController@downloadDocumentsUploade')->name('downloadDocumentsUploadeAdmin');
  Route::get('downloadfile', 'Admin\ApplicationController@downloadFile')->name('downloadfile');
  Route::get('viewAppImageAdmin', 'Admin\ApplicationController@viewAppImage')->name('viewAppImageAdmin');

  Route::get('get-application-bank', 'Admin\ApplicationController@getApplicationBanks')->name('get-application-bank-admin');
  Route::post('store-application-bank', 'Admin\ApplicationController@sendApplicationToBank')->name('application-send-to-bank-admin')->middleware(['XSS']);
  Route::get('applications-bank/all/', ['uses' => 'Admin\BankApplicationController@index', 'as' => 'application-bank.all']);
  Route::get('applications-bank/detail/{id}', ['uses' => 'Admin\BankApplicationController@show', 'as' => 'application-bank.detail']);
  Route::get('applications-bank/edit/{id}', ['uses' => 'Admin\BankApplicationController@edit', 'as' => 'application-bank.edit']);
  Route::post('applications-bank/update', ['uses' => 'Admin\BankApplicationController@update', 'as' => 'application-bank.update'])->middleware(['XSS']);
  Route::get('applications-bank/approved/', ['uses' => 'Admin\BankApplicationController@approved_application', 'as' => 'application-bank.approved']);
  Route::get('applications-bank/pending/', ['uses' => 'Admin\BankApplicationController@pending_application', 'as' => 'application-bank.pending']);
  Route::get('applications-bank/reassign/', ['uses' => 'Admin\BankApplicationController@reassign_application', 'as' => 'application-bank.reassign']);
  Route::post('applications-bank-approve/', ['uses' => 'Admin\BankApplicationController@applicationApprove', 'as' => 'application-bank-approve'])->middleware(['XSS']);
  Route::post('application-bank-reject/', ['uses' => 'Admin\BankApplicationController@applicationReject', 'as' => 'application-bank-reject'])->middleware(['XSS']);
  Route::post('application-bank-reassign/', ['uses' => 'Admin\BankApplicationController@applicationReAssign', 'as' => 'application-bank-reassign'])->middleware(['XSS']);
  Route::post('delete-all-application-bank', ['uses' => 'Admin\BankApplicationController@deleteAllApplication'])->name('delete.all.bank.application')->middleware(['XSS']);
  Route::get('downloadBankApplicationDocumentsUpload', 'Admin\BankApplicationController@downloadBankApplicationDocumentsUpload')->name('downloadBankApplicationDocumentsUpload');
  Route::get('applications-rp/all/', ['uses' => 'Admin\RpApplicationController@index', 'as' => 'application-rp.all']);
  Route::get('applications-rp/pending/', ['uses' => 'Admin\RpApplicationController@pending_application', 'as' => 'application-rp.pending']);
  Route::get('applications-rp/reassign/', ['uses' => 'Admin\RpApplicationController@reassign_application', 'as' => 'application-rp.reassign']);
  Route::get('applications-rp/approved/', ['uses' => 'Admin\RpApplicationController@approved_application', 'as' => 'application-rp.approved']);
  Route::get('applications-rp/detail/{id}', ['uses' => 'Admin\RpApplicationController@show', 'as' => 'application-rp.detail']);
  Route::get('applications-rp/edit/{id}', ['uses' => 'Admin\RpApplicationController@edit', 'as' => 'application-rp.edit']);
  Route::post('applications-rp/update', ['uses' => 'Admin\RpApplicationController@update', 'as' => 'application-rp.update'])->middleware(['XSS']);
  Route::post('applications-rp-approve/', ['uses' => 'Admin\RpApplicationController@applicationApprove', 'as' => 'application-rp-approve'])->middleware(['XSS']);
  Route::post('application-rp-reject/', ['uses' => 'Admin\RpApplicationController@applicationReject', 'as' => 'application-rp-reject'])->middleware(['XSS']);
  Route::post('application-rp-reassign/', ['uses' => 'Admin\RpApplicationController@applicationReAssign', 'as' => 'application-rp-reassign'])->middleware(['XSS']);
  Route::post('delete-all-application-rp', ['uses' => 'Admin\RpApplicationController@deleteAllApplication'])->name('delete.all.rp.application')->middleware(['XSS']);
  Route::get('downloadRpApplicationDocumentsUpload', 'Admin\RpApplicationController@downloadRpApplicationDocumentsUpload')->name('downloadRpApplicationDocumentsUpload');
  /**************Applications Routes End From Here**************************/

  /************* categories resources start ******************************/
  Route::resource('categories', 'Admin\CategoryController')->middleware(['XSS']);
  /************* categories resources end ******************************/

  /************* technology partner start ******************************/
  Route::resource('integration-preference', 'Admin\TechnologyPartnersController')->middleware(['XSS']);
  /************* technology partner end ******************************/

  /************* Admin log start ******************************/
  Route::resource('admin-logs', 'Admin\AdminLogsController')->middleware(['XSS']);
  Route::get('log/download', 'Admin\AdminLogsController@downloadLog');
  Route::get("/system-logs", "Admin\AdminLogsController@viewLogs")->name("system.logs");
  Route::post("/clear-system-logs", "Admin\AdminLogsController@clearLogs")->name("clear.system.logs")->middleware(['XSS']);
  /************* Admin log end ******************************/

  /**************** User Management Resources start ******************/
  Route::get('users-management', 'Admin\UserManagementController@index')->name('users-management');
  Route::post('users-management/export', 'Admin\UserManagementController@export')->name('users-management.export')->middleware(['XSS']);
  Route::post('show-user-details', 'Admin\UserManagementController@showUserDetails')->name('show-user-details')->middleware(['XSS']);
  Route::post('change-password', 'Admin\UserManagementController@changePassword')->name('change-password')->middleware(['XSS']);
  Route::get('merchant-user-masstransactions', 'Admin\UserManagementController@massremove')->name('merchant-user-masstransactions');
  Route::get('merchant-user-create', 'Admin\UserManagementController@merchantUserCreate')->name('merchant-user-create');
  Route::post('merchant-user-store', 'Admin\UserManagementController@merchantUserStore')->name('merchant-user-store')->middleware(['XSS']);
  Route::get('merchant-user-edit/{id}', 'Admin\UserManagementController@merchantUserEdit')->name('merchant-user-edit');
  Route::put('merchant-user-update/{id}', 'Admin\UserManagementController@merchantUserUpdate')->name('merchant-user-update')->middleware(['XSS']);
  Route::get('merchant-user/bank-details/{id}', 'Admin\UserManagementController@getUserBankDetails')->name('admin.merchant.bankDetails');

  Route::get('assign-mid/{id}', 'Admin\UserManagementController@assignMID')->name('assign-mid');
  Route::post('assign-mid', 'Admin\UserManagementController@assignMIDStore')->name('assign-mid-store')->middleware(['XSS']);
  Route::post('assign-mid-merchant', 'Admin\UserManagementController@assignMIDStoremerchant')->name("assign-mid-store-merchant")->middleware(['XSS']);
  Route::get('card-email-limit/{id}', 'Admin\UserManagementController@cardEmailLimit')->name('card-email-limit');
  Route::get('merchant-rate-fee/{id}', 'Admin\UserManagementController@merchantRateFee')->name('merchant-rate-fee');
  Route::get('additional-mail/{id}', 'Admin\UserManagementController@additionalMail')->name('additional-mail');
  Route::get('merchant-rules/{id}', 'Admin\UserManagementController@merchantRules')->name('merchant-rules');
  Route::get('personal-info/{id}', 'Admin\UserManagementController@merchantPersonalInfo')->name('personal-info');

  Route::get('sub-user/{id}', 'Admin\UserManagementController@subUser')->name('sub-user');
  Route::get('sub-users-management', 'Admin\UserManagementController@subUsersMngt')->name('sub-users-management');
  Route::get('sub-users-edit/{id}', 'Admin\UserManagementController@subUserEdit')->name('sub-users-edit');
  Route::get('sub-users-list-edit/{id}', 'Admin\UserManagementController@subUserListEdit')->name('sub-users-list-edit');
  Route::patch('sub-users-update/{id}', 'Admin\UserManagementController@subUserUpdate')->name('sub-users-update')->middleware(['XSS']);
  Route::patch('sub-users-list-update/{id}', 'Admin\UserManagementController@subUserListUpdate')->name('sub-users-list-update')->middleware(['XSS']);
  Route::delete('sub-users-delete/{id}', 'Admin\UserManagementController@subUserDelete')->name('sub-users-delete');
  Route::delete('users-management/{id}', 'Admin\UserManagementController@destroy')->name('users-management-delete');
  Route::get('merchant-sub-user-masstransactions', 'Admin\UserManagementController@massremoveSubUser')->name('merchant-sub-user-masstransactions');

  Route::post('send-user-multi-mail', 'Admin\UserManagementController@sendMultiMail')->name('send-user-multi-mail')->middleware(['XSS']);
  Route::post('get-user-total-amount', 'Admin\UserManagementController@getUserTotalAmount')->name('get-user-total-amount')->middleware(['XSS']);
  Route::post('user-set-agent', 'Admin\UserManagementController@setAgent')->name('user-set-agent')->middleware(['XSS']);
  Route::post('user-deactive', 'Admin\UserManagementController@userActiveDeactive')->name('user-deactive')->middleware(['XSS']);
  Route::post('user-otp-required', 'Admin\UserManagementController@userOTPRequired')->name('user-otp-required')->middleware(['XSS']);
  Route::post('user-ip-remove', 'Admin\UserManagementController@userIPRemove')->name('user-ip-remove')->middleware(['XSS']);
  Route::post('user-disable-rules', 'Admin\UserManagementController@userDisableRule')->name('user-disable-rules')->middleware(['XSS']);
  Route::post('user-bin-remove', 'Admin\UserManagementController@userBinRemove')->name('user-bin-remove')->middleware(['XSS']);
  Route::get('send-password/{id}', 'Admin\UserManagementController@sendPassword')->name('send-password');
  Route::get('user-otp-reset/{id}', 'Admin\UserManagementController@userOtpReset')->name('user-otp-reset');
  Route::post('make-refund-status', 'Admin\UserManagementController@makeRefundStatus')->name('make-refund-status')->middleware(['XSS']);
  Route::post('make-active-status', 'Admin\UserManagementController@makeActiveStatus')->name('make-active-status')->middleware(['XSS']);
  Route::get('api-key-generate/{id}', 'Admin\UserManagementController@apiKeyGenerate')->name('api-key-generate');
  Route::get('get-template-data', 'Admin\UserManagementController@getTemplateData')->name('get-template-data');
  Route::get('merchant-stores', 'Admin\UserStoresController@index')->name('merchant-stores');
  Route::get('merchant-stores-products/{id}', 'Admin\UserStoresController@products')->name('admin.merchant.stores.products');
  Route::get('user-stores/export', 'Admin\UserStoresController@export')->name('user-stores-csv-export');
  Route::get('merchant-stores-product-csv-export/{id}', 'Admin\UserStoresController@productExport')->name('user-stores-product-csv-export');
  /**************** User Management Resources End  ******************/

  /*******************Admin Transaction MOdule Start*****************************/

  Route::get('transactions/{id}', 'Admin\MerchantTransactionController@show')->name('admin.getsingletransaction');
  Route::post('merchant-transactions-details', 'Admin\MerchantTransactionController@transactionDetails')->name('admin.merchant-transactions-details')->middleware(['XSS']);


  Route::group(['middleware' => 'trim'], function () {

    /**************Applications Routes Start From Here**************************/
    Route::get('applications-list', ['uses' => 'Admin\ApplicationController@index', 'as' => 'admin.applications.list']);
    Route::get('applications-list/complete', ['uses' => 'Admin\ApplicationController@is_completed', 'as' => 'admin.applications.is_completed']);
    Route::get('applications-list/approved', ['uses' => 'Admin\ApplicationController@is_approved', 'as' => 'admin.applications.is_approved']);
    Route::get('applications-list/rate-accepted', ['uses' => 'Admin\ApplicationController@rateAccepted', 'as' => 'admin.applications.rate_accepted']);
    Route::get('applications-list/rate-accepted/export', ['uses' => 'Admin\ApplicationController@rateAcceptedExport', 'as' => 'admin.applications.rate_accepted.export']);
    Route::get('applications-list/rate-decline', ['uses' => 'Admin\ApplicationController@rateDecline', 'as' => 'admin.applications.rate_decline']);
    Route::get('applications-list/rate-decline/export', ['uses' => 'Admin\ApplicationController@rateDeclineExport', 'as' => 'admin.applications.rate_decline.export']);
    Route::get('applications-list/rejected', ['uses' => 'Admin\ApplicationController@is_rejected', 'as' => 'admin.applications.is_rejected']);
    Route::get('applications-list/not-interested', ['uses' => 'Admin\ApplicationController@not_interested', 'as' => 'admin.applications.not_interested']);
    Route::get('applications-list/terminated', ['uses' => 'Admin\ApplicationController@is_terminated', 'as' => 'admin.applications.is_terminated']);
    Route::get('applications-list/deleted', ['uses' => 'Admin\ApplicationController@is_deleted', 'as' => 'admin.applications.is_deleted']);
    Route::get('applications-list/agreement-send', ['uses' => 'Admin\ApplicationController@agreement_send', 'as' => 'admin.applications.agreement_send']);
    Route::get('applications-list/agreement-signed', ['uses' => 'Admin\ApplicationController@agreementSigned', 'as' => 'admin.applications.agreement_signed']);
    Route::get('applications-list/agreement-received', ['uses' => 'Admin\ApplicationController@agreement_received', 'as' => 'admin.applications.agreement_received']);
    Route::get('applications-list/sent-to-bank', ['uses' => 'Admin\ApplicationController@getSentToBank', 'as' => 'admin.applications.sent_to_bank']);
    Route::post('application-referred-reply', 'Admin\ApplicationController@applicationReferredReply')->name('admin.application-referred-reply');
    Route::post('send-application-multi-mail', 'Admin\ApplicationController@sendMultiMail')->name('send-application-multi-mail')->middleware(['XSS']);
    Route::post('get-application-note', 'Admin\ApplicationController@getApplicationNote')->name('get-application-note')->middleware(['XSS']);
    Route::post('store-application-note', 'Admin\ApplicationController@storeApplicationNote')->name('store-application-note')->middleware(['XSS']);

    Route::post('send-to-bank-list', 'Admin\ApplicationController@sendToBankList')->name('admin.send-to-bank-list')->middleware(['XSS']);

    Route::post('get-application-note-bank', 'Admin\ApplicationController@getApplicationNoteBank')->name('get-application-note-bank')->middleware(['XSS']);
    Route::post('store-application-note-bank', 'Admin\ApplicationController@storeApplicationNoteBank')->name('store-application-note-bank')->middleware(['XSS']);
    Route::post('search-application-note-bank', 'Admin\ApplicationController@searchApplicationNoteBank')->name('search-application-note-bank')->middleware(['XSS']);

    Route::post('delete-all-application', 'Admin\ApplicationController@deleteAllApplication')->name('delete-all-application')->middleware(['XSS']);
    /**************Transactions Routes Start From Here**************************/

    Route::post('transaction/status/changes/{id}', 'Admin\MerchantTransactionController@updateStatus')->name("transaction.status.changes")->middleware(['XSS']);
    Route::get('transactions', 'Admin\MerchantTransactionController@index')->name('admin.transactions');
    Route::get('crypto', 'Admin\MerchantTransactionController@crypto')->name('crypto');
    Route::get('refund', 'Admin\MerchantTransactionController@refunds')->name('admin.refund');
    Route::get('pre-arbitration', 'Admin\MerchantTransactionController@preArbitration')->name('admin.pre.arbitration');
    Route::get('chargebacks', 'Admin\MerchantTransactionController@chargebacks')->name('admin.chargebacks');
    Route::get('retrieval', 'Admin\MerchantTransactionController@retrieval')->name('admin.retrieval');
    Route::get('suspicious', 'Admin\MerchantTransactionController@flagged')->name('admin.suspicious');
    Route::get('test-transactions', 'Admin\MerchantTransactionController@testTransactions')->name('admin.test-transactions');
    Route::get('merchant-remove-flagged', 'Admin\MerchantTransactionController@getRemoveFlaggedTransactions')->name('merchant-remove-flagged');
    Route::get('declined-transactions', 'Admin\MerchantTransactionController@declinedTransaction')->name('admin.declined.transactions');
    Route::get('send-transaction-webhook/{id}', 'Admin\MerchantTransactionController@sendTransactionWebhook')->name('send-transaction-webhook');

    Route::post('pre-arbitration-notice-sent', 'Admin\MerchantTransactionController@sentPreArbitrationNotice')->name('pre-arbitration-notice-sent')->middleware(['XSS']);
    Route::post('send-multi-transaction-webhook', 'Admin\MerchantTransactionController@sendMultiTransactionWebhook')->name('send-multi-transaction-webhook')->middleware(['XSS']);

    Route::post('/remove/suspicious', 'Admin\MerchantTransactionController@removeSuspicious')->name('admin.remove.suspicious')->middleware(['XSS']);
    Route::post('/remove/retrieval', 'Admin\MerchantTransactionController@removeRetrieval')->name('admin.remove.retrieval')->middleware(['XSS']);


    Route::post('resend-retrieval-email', 'Admin\MerchantTransactionController@resendRetrievalEmail')->name('resend-retrieval-email')->middleware(['XSS']);
    Route::post('resend-refund-email', 'Admin\MerchantTransactionController@resendRefundEmail')->name('resend-refund-email')->middleware(['XSS']);
    Route::post('resend-chargebacks-email', 'Admin\MerchantTransactionController@resendChargebacksEmail')->name('resend-chargebacks-email')->middleware(['XSS']);
    Route::post('resend-suspicious-email', 'Admin\MerchantTransactionController@resendSuspiciousEmail')->name('resend-suspicious-email')->middleware(['XSS']);
  });

  Route::get('all-admin-transactions-csv-export', '\App\LazyCSVExport\AdminAllTransactionsCSVExport@download')->name('all-admin-transactions-csv-export');
  Route::get('all-admin-crypto-transactions-csv-export', '\App\LazyCSVExport\AdminAllCryptoTransactionsCSVExport@download')->name('all-admin-crypto-transactions-csv-export');
  Route::get('all-admin-refund-transactions-csv-export', '\App\LazyCSVExport\AdminAllRefundTransactionsCSVExport@download')->name('all-admin-refund-transactions-csv-export');
  Route::get('all-admin-chargeback-transactions-csv-export', '\App\LazyCSVExport\AdminAllChargebackTransactionsCSVExport@download')->name('all-admin-chargeback-transactions-csv-export');
  Route::get('all-admin-retrival-transactions-csv-export', '\App\LazyCSVExport\AdminAllRetrivalTransactionsCSVExport@download')->name('all-admin-retrival-transactions-csv-export');
  Route::get('all-admin-suspicious-transactions-csv-export', '\App\LazyCSVExport\AdminAllSuspiciousTransactionsCSVExport@download')->name('all-admin-suspicious-transactions-csv-export');
  Route::get('all-admin-declined-transactions-csv-export', '\App\LazyCSVExport\AdminAllDeclinedTransactionsCSVExport@download')->name('all-admin-declined-transactions-csv-export');
  Route::get('all-admin-prearbitration-transactions-csv-export', '\App\LazyCSVExport\AdminAllPreArbitrationTransactionsCSVExport@download')->name('all-admin-prearbitration-transactions-csv-export');
  Route::get('all-admin-test-transactions-csv-export', '\App\LazyCSVExport\AdminAllTestTransactionsCSVExport@download')->name('all-admin-test-transactions-csv-export');
  Route::get('user-management-csv-export', '\App\LazyCSVExport\UserManagementCSVExport@download')->name('user-management-csv-export');
  Route::get('admin-user-csv-export', '\App\LazyCSVExport\AdminUserCSVExport@download')->name('admin-user-csv-export');
  Route::get('bank-user-csv-export', '\App\LazyCSVExport\BankUserCSVExport@download')->name('bank-user-csv-export');
  Route::get('agent-user-csv-export', '\App\LazyCSVExport\AgentUserCSVExport@download')->name('agent-user-csv-export');
  Route::get('wl-agent-user-csv-export', '\App\LazyCSVExport\WLAgentUserCSVExport@download')->name('wl-agent-user-csv-export');
  Route::get('wl-agent-merchant-all-csv-export', '\App\LazyCSVExport\WLAgentMerchantAllCSVExport@download')->name('wl-agent-merchant-all-csv-export');
  // Transaction export
  Route::post('transactions/export', 'Admin\MerchantTransactionController@exportAllTransactions')->name('admin.transactions.exportAllTransactions')->middleware(['XSS']);
  Route::post('crypto/export', 'Admin\MerchantTransactionController@exportCrypto')->name('crypto.export')->middleware(['XSS']);
  Route::post('refund/export', 'Admin\MerchantTransactionController@exportRefunds')->name('admin.refund.export')->middleware(['XSS']);
  Route::post('chargebacks/export', 'Admin\MerchantTransactionController@exportChargebacks')->name('admin.chargebacks.export')->middleware(['XSS']);
  Route::post('retrieval/export', 'Admin\MerchantTransactionController@exportRetrieval')->name('admin.retrieval.export')->middleware(['XSS']);
  Route::post('flagged/export', 'Admin\MerchantTransactionController@exportFlagged')->name('admin.flagged.export')->middleware(['XSS']);
  Route::post('test-transactions/export', 'Admin\MerchantTransactionController@exportTestTransactions')->name('admin.test-transactions.export')->middleware(['XSS']);
  Route::post('merchant-remove-flagged/export', 'Admin\MerchantTransactionController@exportRemoveFlaggedTransactions')->name('merchant-remove-flagged.export')->middleware(['XSS']);
  Route::post('declined/export', 'Admin\MerchantTransactionController@exportDeclinedTransactions')->name('admin.declined.export')->middleware(['XSS']);

  Route::post('change-refund-status', 'Admin\MerchantTransactionController@changeRefundStatus')->name('change-refund-status')->middleware(['XSS']);
  Route::post('change-transaction-unRefund', 'Admin\MerchantTransactionController@changeTransactionUnRefund')->name('change-transaction-unRefund')->middleware(['XSS']);
  Route::post('change-chargebacks-status', 'Admin\MerchantTransactionController@changeChargebacksStatus')->name('change-chargebacks-status')->middleware(['XSS']);
  Route::post('change-transaction-unChargeback', 'Admin\MerchantTransactionController@changeChargebacksUnChargeback')->name('change-transaction-unChargeback')->middleware(['XSS']);
  Route::post('change-transaction-flag', 'Admin\MerchantTransactionController@changeTransactionFlag')->name('change-transaction-flag')->middleware(['XSS']);
  Route::post('change-transaction-unflagged', 'Admin\MerchantTransactionController@changeTransactionUnFlag')->name('change-transaction-unflagged')->middleware(['XSS']);
  Route::post('change-transaction-status', 'Admin\MerchantTransactionController@changeTransactionStatus')->name('change-transaction-status')->middleware(['XSS']);
  Route::post('merchant-chargeback-upload-document', 'Admin\MerchantTransactionController@uploadDocument')->name('merchant-chargeback-upload-document')->middleware(['XSS']);
  Route::post('merchant-chargebacks-document', 'Admin\MerchantTransactionController@showDocumentChargebacks')->name('merchant-chargebacks-show-documents')->middleware(['XSS']);
  Route::get('downloadDocumentsUploade', 'Admin\MerchantTransactionController@downloadDocumentsUploade')->name('downloadDocumentsUploade');
  Route::post('merchant-flagged-upload-document', 'Admin\MerchantTransactionController@uploadDocument')->name('merchant-flagged-upload-document')->middleware(['XSS']);
  Route::post('merchant-flagged-document', 'Admin\MerchantTransactionController@showDocumentFlagged')->name('merchant-flagged-show-documents')->middleware(['XSS']);
  Route::post('merchant-retrieval-upload-document', 'Admin\MerchantTransactionController@uploadDocument')->name('merchant-retrieval-upload-document')->middleware(['XSS']);
  Route::post('merchant-retrieval-document', 'Admin\MerchantTransactionController@showDocumentRetrieval')->name('merchant-retrieval-show-documents')->middleware(['XSS']);
  Route::post('change-retrieval-status', 'Admin\MerchantTransactionController@changeRetrievalStatus')->name('change-retrieval-status')->middleware(['XSS']);
  Route::post('change-transaction-unRetrieval', 'Admin\MerchantTransactionController@changeRetrievalUnRetrieval')->name('change-transaction-unRetrieval')->middleware(['XSS']);
  Route::post('delete-transaction', 'Admin\MerchantTransactionController@deleteTransaction')->name('delete-transaction')->middleware(['XSS']);

  /*******************Admin Transaction MOdule End*****************************/

  /****************** Gateway Module Start *******************************/
  Route::resource('gateway', 'Admin\GatewayController', ['as' => 'admin'])->middleware(['XSS']);
  /****************** Gateway Module End *******************************/

  /*********************sub-gateway module Start*************************************/
  Route::resource('gateway/{gateway_id}/subgateway', 'Admin\SubgatewayController', ['as' => 'admin'])->middleware(['XSS']);
  Route::get('gateway/{gateway_id}/subgateway/edit-data/{id}', 'Admin\SubgatewayController@subGatewayEdit', ['as' => 'admin'])->name('subGatway-edit-data');
  Route::delete('gateway/{gateway_id}/subgateway/delete-data/{id}', 'Admin\SubgatewayController@subGatewayDelete', ['as' => 'admin'])->name('subGatway-delete-data');
  Route::get('gateway/{gateway_id}/subgateway/get-data', 'Admin\SubgatewayController@getWonderlandMIDData')->name('admin.subgateway.get-subgateway-data')->middleware(['XSS']);
  Route::post('getsubgateway', 'Admin\SubgatewayController@getSubgateway')->name('admin.getsubgateway');
  /*********************sub-gateway module End*************************************/

  /****************Admin Trade module Start ***********************************/
  Route::get('trade', 'Admin\TradeController@index')->name('admin.trade.index');
  /****************Admin Trade module End ***********************************/

  /****************Admin Ticket module Start ***********************************/
  Route::get('ticket', 'Admin\TicketController@index')->name('admin.ticket');
  Route::get('ticket/{id}', 'Admin\TicketController@show')->name('admin.ticket.show');
  Route::get('ticket/close/{id}', 'Admin\TicketController@close')->name('admin.ticket.close');
  Route::get('ticket/reopen/{id}', 'Admin\TicketController@reopen')->name('admin.ticket.reopen');
  Route::delete('ticket/{id}', 'Admin\TicketController@destroy')->name('admin.ticket.destroy');


  Route::resource('ticket/reply', 'Admin\TicketReplyController', ['as' => 'admin.ticket'])->middleware(['XSS']);
  //Route::post('ticket/reply','Admin\TicketReplyController@store')->name('admin.ticket-reply.store');
  /****************Admin Ticket module End ***********************************/


  /*********************Create Rules Start******************************************/
  Route::resource('create_rules', 'Admin\CreateRulesController', ['as' => 'admin'])->middleware(['XSS']);
  Route::get("merchant_rules/list", 'Admin\CreateRulesController@merchantRules')->name("admin.merchant_rules.index");
  Route::get('merchant_rules/list/{type}', 'Admin\CreateRulesController@merchantList')->name('admin.merchant_rules.list');
  Route::get('create_rules/{type}', 'Admin\CreateRulesController@create')->name('create_rules.create');
  Route::get('create_rules/list/{type}', 'Admin\CreateRulesController@list')->name('admin.create_rules.list');
  Route::post('change-assign-mid', 'Admin\CreateRulesController@changeAssignMID')->name('change-assign-mid')->middleware(['XSS']);
  Route::post('delete-rules', 'Admin\CreateRulesController@deleteRules')->name('delete-rules')->middleware(['XSS']);
  Route::post('change-rules-status/{status}', 'Admin\CreateRulesController@changeRulesStatus')->name('change-rules-status')->middleware(['XSS']);
  Route::get('rules-status/{id}', 'Admin\CreateRulesController@changeStatus')->name('rules-status');
  Route::post('rules/datatable', 'Admin\CreateRulesController@listDataTable')->name('rules.datatable')->middleware(['XSS']);
  Route::post('rules/merchant/datatable', 'Admin\CreateRulesController@listMerchantDataTable')->name("rules.merchant.datatable")->middleware(['XSS']);
  Route::post('sort-rules', 'Admin\CreateRulesController@sortRules')->name('sort.rules')->middleware(['XSS']);
  Route::get('rules-merchant-status/{id}', 'Admin\CreateRulesController@changeMerchantStatus')->name('rules-merchant-status');
  /*********************Create Rules End********************************************/

  /****************Merchant Rules Start*******************************************/
  Route::get('merchant/create_rules/{id}/{type}', 'Admin\MerchantRulesController@create')->name('merchant.create_rules.create');
  Route::post('merchant/create_rules/store', 'Admin\MerchantRulesController@store')->name('merchant.create_rules.store')->middleware(['XSS']);
  Route::get('merchant/create_rules', 'Admin\MerchantRulesController@index')->name('merchant.create_rules.index');
  Route::get('merchant/create_rules/list/{id}/{type}', 'Admin\MerchantRulesController@list')->name('merchant.create_rules.list');
  Route::post('merchant/rules/datatable', 'Admin\MerchantRulesController@listDataTable')->name('merchant.rules.datatable')->middleware(['XSS']);
  Route::get("merchant/edit_rules/{userId}/{type}/{id}", "Admin\MerchantRulesController@edit")->name("merchant.edit_rules");
  Route::post("merchant/update_rules/{userId}/{id}", "Admin\MerchantRulesController@update")->name("merchant.update_rules")->middleware(['XSS']);

  //******************Report module Start*********************************//
  //Transaction summary Report
  Route::get('report', 'Admin\ReportController@index')->name('report.index');
  Route::get('transaction-summary-report', 'Admin\ReportController@transactionsSummaryReport')->name('transaction-summary-report');
  Route::get('transaction-summary-report2', 'Admin\ReportController@transactionsSummaryReport2')->name('transaction-summary-report2');
  Route::get('transaction-summary-report-excle', 'Admin\ReportController@transactionsSummaryReportExcle')->name('transaction-summary-report-excle');
  //Merchant Transaction Report
  Route::get('merchant-transaction-report', 'Admin\ReportController@merchantTransactionsReport')->name('merchant-transaction-report');
  Route::get('merchant-transaction-report-excle', 'Admin\ReportController@merchantTransactionsReportExcle')->name('merchant-transaction-report-excle');
  Route::get('auto-suspicious-report', 'Admin\ReportController@suspiciousReport')->name('auto-suspicious-report');
  Route::post('auto-suspicious-report/export', 'Admin\ReportController@exportAllSuspicious')->name('admin.auto_suspicious.export')->middleware(['XSS']);
  Route::get('auto-suspicious/start', 'Admin\ReportController@startFlag')->name('auto-suspicious.startFlag');
  //Summery Report
  Route::get('summary-report', 'Admin\ReportController@summaryReport')->name('summary-report');
  Route::get('card-summary-report', 'Admin\ReportController@cardSummaryReport')->name('card-summary-report');
  Route::post('card-summary-report-excle', 'Admin\ReportController@cardSummaryReportExcel')->name('card-summary-report-excle')->middleware(['XSS']);
  Route::get('payment-status-summary-report', 'Admin\ReportController@paymentStatusSummaryReport')->name('payment-status-summary-report');
  Route::get('payment-status-summary-report-excle', 'Admin\ReportController@paymentStatusSummaryReportExcel')->name('payment-status-summary-report-excle');
  Route::get('mid-summary-report', 'Admin\ReportController@midSummaryReport')->name('mid-summary-report');
  Route::post('mid-summary-report-excle', 'Admin\ReportController@midSummaryReportExcel')->name('mid-summary-report-excle')->middleware(['XSS']);
  Route::get('summary-report-on-country', 'Admin\ReportController@midSummaryReportOnCountry')->name('summary-report-on-country');
  Route::post('summary-report-on-country-excle', 'Admin\ReportController@midSummaryReportOnCountryExcel')->name('summary-report-on-country-excle')->middleware(['XSS']);

  // Reason Report
  Route::get('transactions-reason-report', 'Admin\ReportController@reasonReport')->name('transactions-reason-report');
  Route::get('merchant-transactions-reason-report', 'Admin\ReportController@merchantReasonReport')->name('merchant-transactions-reason-report');
  Route::get('merchant-transactions-reason-report-excle', 'Admin\ReportController@merchantTransactionReasonReportExcle')->name('merchant-transactions-reason-report-excle');
  Route::get('merchant-transactions-approval-report', 'Admin\ReportController@merchantApprovalReport')->name('merchant-transactions-approval-report');
  Route::get('countrywise-transactions-report', 'Admin\ReportController@countrywiseTransactionReport')->name('countrywise-transactions-report');
  Route::get('merchant-daily-transactions-report', 'Admin\ReportController@merchantDailyTransactionReport')->name('merchant-daily-transactions-report');
  Route::get('merchant-countrywise-transactions-report', 'Admin\ReportController@merchantCountrywiseTransactionReport')->name('merchant-countrywise-transactions-report');

  //******************Report module End************************************//

  //******************payout module Start*********************************//
  Route::get('generate-payout-report', 'Admin\PayoutReportController@index')->name('generate-payout-report');
  Route::post('payout-report-store', 'Admin\PayoutReportController@store')->name('payout-report-store')->middleware(['XSS']);
  Route::post('make-report-paid', 'Admin\PayoutReportController@makeReportPaid')->name('make-report-paid')->middleware(['XSS']);
  Route::post('show-report-client', 'Admin\PayoutReportController@showReportClient')->name('show-report-client')->middleware(['XSS']);
  Route::get('report-delete', 'Admin\PayoutReportController@massremove')->name('report-delete');
  Route::get('upload-files/{id}', 'Admin\PayoutReportController@uploadReportFiles')->name('upload-files');
  Route::post('generate-report-document', 'Admin\PayoutReportController@reportFilesUpload')->name('generate-report-document')->middleware(['XSS']);

  Route::get('generate/daily/settlement/report', 'Admin\PaymentSettlementController@fethDailyUserReport')->name('setellement_report');
  Route::get('generate/settlement/report', 'Admin\PaymentSettlementController@fethUserReport')->name('setellement_report');
  Route::get("daily/settlement_payout/report", "Admin\PaymentSettlementController@index")->name("merchant.daily_settlement_report");
  Route::get("settlement/payout/report", "Admin\PaymentSettlementController@settlementPAyoutReport")->name("merchant.settlement_report");
  Route::post("auto/payout-report-store", "Admin\PaymentSettlementController@autoPayoutReportStore")->name("merchant.auto_payoutreport");
  Route::get("generate/settlement_payout/report_date", "Admin\PaymentSettlementController@reGenerateCalculation")->name("merchant.generate_thisdate_calculation");
  Route::get("view/settlement_payout/date", "Admin\PaymentSettlementController@viewTillDateReport")->name("merchant.view_report_tilldate");

  Route::get('admin-generate-report-export', '\App\LazyCSVExport\AdminGenerateReportExport@download')->name('admin.generate_report.export');

  //Route::post('generate_report/export', 'Admin\PayoutReportController@generateReportExport')->name('admin.generate_report.export');
  Route::get('generate_report/pdf/{id}', 'Admin\PayoutReportController@generatePDF')->name('generate_report.pdf');
  Route::get('generate_report/{id}', 'Admin\PayoutReportController@show')->name('generate_report.show');

  Route::get('auto-generate-payout-report', 'Admin\PayoutReportController@autoPayout')->name('auto-generate-payout-report');
  Route::post('auto-payout-report-store', 'Admin\PayoutReportController@autoPayoutStore')->name('auto-payout-report-store-new')->middleware(['XSS']);

  Route::get('generate-payout-report-new', 'Admin\PayoutReportController@indexNew')->name('generate-payout-report-new');
  Route::post('payout-report-store-new', 'Admin\PayoutReportController@storeNew')->name('payout-report-store-new')->middleware(['XSS']);

  //******************WL RP payout module Start*********************************//
  Route::get('generate-payout-report-rp', 'Admin\WLRPPayoutReportController@index')->name('generate-payout-report-rp');
  Route::post('payout-report-store-rp', 'Admin\WLRPPayoutReportController@store')->name('payout-report-store-rp')->middleware(['XSS']);
  Route::post('make-report-paid-rp', 'Admin\WLRPPayoutReportController@makeReportPaid')->name('make-report-paid-rp')->middleware(['XSS']);
  Route::post('show-report-client-rp', 'Admin\WLRPPayoutReportController@showReportClient')->name('show-report-client-rp');
  Route::get('report-delete-rp', 'Admin\WLRPPayoutReportController@massremove')->name('report-delete-rp');
  Route::get('generate_report_rp/{id}', 'Admin\WLRPPayoutReportController@show')->name('generate_report.show.rp');
  Route::get('generate_report_rp/pdf/{id}', 'Admin\WLRPPayoutReportController@generatePDF')->name('generate_report.pdf.rp');
  Route::post('generate-report-document-rp', 'Admin\WLRPPayoutReportController@reportFilesUpload')->name('generate-report-document-rp')->middleware(['XSS']);
  Route::get('generate-report-rp-export', '\App\LazyCSVExport\AdminGenerateRPReportExport@download')->name('admin.generate_report.rp.export');

  // * Agent Payout Report Section
  Route::get('generate-agent-report', 'Admin\RpPayoutReportController@generateAgentReport')->name('generate-agent-report');
  Route::post('generate-agent-report/store', 'Admin\RpPayoutReportController@storeAgentReport')->name('generate.agent.report.store')->middleware(['XSS']);
  Route::get('generate-agent-report/export', 'Admin\RpPayoutReportController@agentReportExcel')->name('rp.generated.report.excel');
  Route::post('generate-agent-report/delete', 'Admin\RpPayoutReportController@deleteAgentReport')->name('generate.rp.report.delete')->middleware(['XSS']);
  Route::post('generate-agent-report/isPaid', 'Admin\RpPayoutReportController@changeIsPaidStatus')->name('generate.rp.report.isPaid')->middleware(['XSS']);
  Route::post('generate-agent-report/document', 'Admin\RpPayoutReportController@uploadRPDocument')->name('generate.rp.report.document')->middleware(['XSS']);
  Route::post('generate-agent-report/ClientSide', 'Admin\RpPayoutReportController@changeClientSideStatus')->name('generate.rp.report.clientSide')->middleware(['XSS']);
  Route::get('generate-agent-report/show/{id}', 'Admin\RpPayoutReportController@showAgentreport')->name('admin.generate.agent.report.show');
  Route::get('generate-agent-report/pdf/{id}', 'Admin\RpPayoutReportController@getAgentreportPdf')->name('generate.agent.report.pdf');


  // * Agent Report Section
  Route::get('agent-report', 'Admin\RpPayoutReportController@agentReport')->name('agent-report');
  Route::post('agent-company', 'Admin\RpPayoutReportController@getCompanyByAgent')->name('agent.company')->middleware(['XSS']);
  //******************payout module End************************************//

  // IP Whitelist
  Route::get('ip-whitelist', 'Admin\IPWhitelistController@index')->name('ip-whitelist');
  Route::get('approveWebsiteUrl/{id}', 'Admin\IPWhitelistController@approveWebsiteUrl')->name('approveWebsiteUrl');
  Route::get('refuseWebsiteUrl/{id}', 'Admin\IPWhitelistController@refuseWebsiteUrl')->name('refuseWebsiteUrl');
  Route::get('add/ip', 'Admin\IPWhitelistController@addIP')->name('add.ip');
  Route::post('add/ip', 'Admin\IPWhitelistController@storeIP')->name('store.ip')->middleware(['XSS']);
  Route::post('ip-whitelist-excel', 'Admin\IPWhitelistController@ipWhitelistExcel')->name('ip-whitelist-excel')->middleware(['XSS']);
  Route::post("/approve-multi-ip", 'Admin\IPWhitelistController@approveMultiIP')->name("approve.multiip")->middleware(['XSS']);

  // ASP Iframe
  Route::get('asp-iframe', 'Admin\ASPIframeController@index')->name('asp-iframe');
  Route::post('asp-iframe', 'Admin\ASPIframeController@store')->name('asp-iframe')->middleware(['XSS']);
  Route::get('get-iframe-logo', 'Admin\ASPIframeController@getIframeLogo')->name('get-iframe-logo');

  //******************Payout Schedule module Start *********************************//
  Route::resource('payout-schedule', 'Admin\PayoutScheduleController')->middleware(['XSS']);
  //******************Payout Schedule module End   *********************************//

  // Article Categories
  Route::resource('article-categories', 'Admin\ArticleCategoryController')->middleware(['XSS']);

  // Article Tags
  Route::resource('article-tags', 'Admin\ArticleTagController')->middleware(['XSS']);

  // Article
  Route::resource('article', 'Admin\ArticleController')->middleware(['XSS']);
  Route::get('article/view/{slug}', 'Admin\ArticleController@view');

  Route::get('resend/email', 'AdminController@resendMail')->name('resend.admin.profile');

  //Required Fields
  Route::resource('required_fields', 'Admin\RequiredFieldsController')->middleware(['XSS']);

  //Merchant & RP Agreement
  Route::resource('agreement_content', 'Admin\AgreementContentController')->middleware(['XSS']);

  //TreansactionSession start
  Route::get('transaction-session', 'Admin\TransactionSessionController@index')->name('transaction-session');
  Route::get('transaction-session/restore/{id}', 'Admin\TransactionSessionController@restoreTransactionSessionForm')->name('admin.restoreTransactionSessionForm');
  Route::post('transaction-session/restore/{id}', 'Admin\TransactionSessionController@restoreTransactionSession')->name('admin.restoreTransactionSession');
  Route::get('transaction-session/{id}', 'Admin\TransactionSessionController@transactionSessionShow')->name('admin.transaction_session_show')->middleware(['XSS']);
  //TransactionSession end

  Route::get('payment-api-data', 'Admin\PaymentAPIController@index')->name('admin.paymentApi');
  Route::get('payment-api-data/{id}', 'Admin\PaymentAPIController@show')->name('admin.paymentApi.show');

  //Mail Templates
  Route::resource('mail-templates', 'Admin\MailTemplatesController');
  Route::get('mail-templates/edit/{id}', 'Admin\MailTemplatesController@edit')->name("mail-templates.edit");

  // Chakra
  Route::get('chakra-transaction', 'Admin\ChakraController@transactionlist')->name('chakra-transactionlist');

  // mass mid change
  Route::post('mass-mid/create/confirm', 'Admin\MassMIDController@createConfirm')->name('mass-mid.createConfirm')->middleware(['XSS']);
  Route::post('mass-mid/merchats/get-data', 'Admin\MassMIDController@getMerchants')->name('mass-mid.getMerchants')->middleware(['XSS']);
  Route::post('mass-mid/update/confirm', 'Admin\MassMIDController@updateConfirm')->name('mass-mid.updateConfirm')->middleware(['XSS']);
  Route::post('mass-mid/revert/confirm', 'Admin\MassMIDController@revertConfirm')->name('mass-mid.revertConfirm')->middleware(['XSS']);
  Route::post('mass-mid/revert/revert', 'Admin\MassMIDController@revert')->name('mass-mid.revert')->middleware(['XSS']);
  Route::get('mass-mid/setup/refresh/{id}', 'Admin\MassMIDController@refresh')->name('mass-mid.refresh');
  Route::get('mass-mid/merchats/view-merchants/{id}', 'Admin\MassMIDController@viewMerchants')->name('mass-mid.viewMerchants');
  Route::resource('mass-mid', 'Admin\MassMIDController')->middleware(['XSS']);

  /************* Cron Management ******************************/
  Route::get('cron-management', 'Admin\CronManagementController@index')->name('cron-management');
  Route::post('show-all-cron', 'Admin\CronManagementController@showAllCron')->name('show.all.cron')->middleware(['XSS']);
  Route::post('get-cron-edit-form', 'Admin\CronManagementController@getEditCronForm')->name('get.cron.edit.form')->middleware(['XSS']);
  Route::get('cron-management-startstop', 'Admin\CronManagementController@startStopCron')->name('cron.management.startstop');
  Route::post('store-add-cron', 'Admin\CronManagementController@storeAddCron')->name('store.add.cron')->middleware(['XSS']);

  //BlockedSystem start
  Route::get('blocked-system', 'Admin\BlockCardController@index')->name('blocked-system');
  Route::delete('blocked-system-destroy/{id}', 'Admin\BlockCardController@destroy')->name('blocked-system-destroy');
  Route::post('delete-card', 'Admin\BlockCardController@deleteCard')->name('delete-card')->middleware(['XSS']);
  // Blocked System end

  Route::get('counter/show/all', 'Admin\CounterController@index')->name('counter.index');

  Route::get('soi-refund', 'Admin\SoiController@refund')->name('soi-refund');
  Route::post("soi/refund/store", "Admin\SoiController@store")->name("soi.refund.store")->middleware(['XSS']);


  Route::get('aiglobal-refund', 'Admin\AIGlobalController@refund')->name('aiglobal-refund');
  Route::post("aiglobal/refund/store", "Admin\AIGlobalController@store")->name("aiglobal.refund.store")->middleware(['XSS']);


  Route::get('oculus-refund', 'Admin\OculusController@refund')->name('oculus-refund');
  Route::post("oculus/refund/store", "Admin\OculusController@store")->name("oculus.refund.store")->middleware(['XSS']);


  // * For Admin Invoices
  Route::post('get-application-invoice', 'Admin\InvoicesController@getApplicationInvoice')->name('get-application-invoice')->middleware(['XSS']);
  Route::get('invoice/download', 'Admin\InvoicesController@downloadInvoice')->name('invoice.download');
  Route::post('make-invoice-paid', 'Admin\InvoicesController@makeInvoicePaid')->name('make-invoice-paid')->middleware(['XSS']);
  Route::post('invoice/update-transactions-hash', 'Admin\InvoicesController@updatedTransactionHash')->name('invoice.updatedTransactionHash')->middleware(['XSS']);
  Route::post('invoice/massdelete', 'Admin\InvoicesController@massDelete')->name('invoice.massdelete')->middleware(['XSS']);
  Route::get('invoice-csv-export', '\App\LazyCSVExport\InvoiceCSVExport@download')->name('invoice-csv-export');
  Route::resource('invoices', 'Admin\InvoicesController')->middleware(['XSS']);

  // * AWS test routes
  Route::resource("aws-s3-test", "Admin\AwsTestController")->middleware(['XSS']);
  // * APM section
  Route::resource("apm", "Admin\ApmController")->middleware(['XSS']);

  // * Admin Test Routes
  Route::get('test/rp-agreement', 'Admin\AdminTestController@testRpAgreement');
  Route::get('test/agreement', 'Admin\AdminTestController@testAgreement');
  Route::get('/centpay-transaction-update', 'Admin\AdminTestController@updateCentpayTransaction');

  // * Test email routes
  Route::get('test-email/{email}', 'Admin\AdminTestController@testEmail');
  Route::get('test-job-email/{email}', 'Admin\AdminTestController@testJobEmail');

  // * WinoPay refund routes
  Route::get("/winopay/refund", "Repo\PaymentGateway\WinoPay@refundForm")->name('winopay.refund.form');
  Route::post("/winopay/refund", "Repo\PaymentGateway\WinoPay@refund")->name('winopay.refund')->middleware(['XSS']);
});