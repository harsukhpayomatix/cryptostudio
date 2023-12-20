<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\AdminLog;
use App\AdminAction;
use App\Admin;
use File;
use Log;

class AdminLogsController extends AdminController
{

    protected $AdminLog, $AdminAction, $Admin, $moduleTitleS, $moduleTitleP;
    public function __construct()
    {
        parent::__construct();
        $this->AdminLog = new AdminLog;
        $this->AdminAction = new AdminAction;
        $this->Admin = new Admin;

        $this->moduleTitleS = 'Admin Logs';
        $this->moduleTitleP = 'admin.logs';

        view()->share('moduleTitleP', $this->moduleTitleP);
        view()->share('moduleTitleS', $this->moduleTitleS);
    }

    public function index(Request $request)
    {
        $input = \Arr::except($request->all(), array('_token', '_method'));
        if (isset($input['noList'])) {
            $noList = $input['noList'];
        } else {
            $noList = 15;
        }
        $data = $this->AdminLog->getData($input, $noList);
        $actionList = $this->AdminAction->getData();
        $adminList = $this->Admin->getData();
        return view($this->moduleTitleP . '.index', compact('data', 'noList', 'actionList', 'adminList'));
    }

    public function show($id)
    {
        $data = $this->AdminLog::where('id', $id)->first();
        $json = json_decode($data->request);
        return view($this->moduleTitleP . '.show', compact('data', 'json'));
    }

    public function downloadLog()
    {
        $file = storage_path() . "/logs/laravel.log";

        if (file_exists($file)) {
            $headers = [
                'Content-Type' => 'application/text',
            ];

            return response()->download($file, 'cryptostudio.log', $headers);
        } else {
            return response()->json(['error' => 'File not found.']);
        }
    }

    public function viewLogs(Request $request)
    {
        // // Read the Laravel log file
        $logFile = storage_path('logs/laravel.log');
        $logContent = file_get_contents($logFile);
        // Split the log content into separate log entries
        $logEntries = preg_split('/\n(?=\[[\d: -]+\] local\.\w+:)/', $logContent, -1, PREG_SPLIT_NO_EMPTY);
        // * Reverse the logs file
        $logEntries = array_reverse($logEntries);

        // Get the size of the log file
        $logFileSize = filesize($logFile);

        // Format the file size for human-readable display
        $logFileSizeFormatted = formatBytes($logFileSize);

        return view('admin.logs.systemLogs', ['logs' => $logEntries, "fileSize" => $logFileSizeFormatted]);

    }

    // * CLearlogs
    public function clearLogs(Request $request)
    {
        // Clear the log file
        $logPath = storage_path('logs/laravel.log');
        File::put($logPath, '');

        // Optionally, you can write a message to the log indicating when the logs were cleared
        Log::info('Logs cleared by ' . auth()->guard("admin")->user()->name);

        // Redirect back to your view with a success message
        return redirect()->back()->with('success', 'Logs cleared successfully.');
    }
}
