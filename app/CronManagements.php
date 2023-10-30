<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CronManagements extends Model
{
    use SoftDeletes;

    // protected $fillable = [
    //     'admin_id',
    //     'action_id',
    //     'actionvalue',
    //     'request',
    //     'ip',
    //     'remark',
    // ];

    public function actions()
    {
        return $this->hasOne(AdminAction::class, 'id', 'action_id');
    }

    public function getData($input, $noList = 15)
    {
        $data = static::select('admin_logs.*', 'admin_actions.title as action_name', 'admins.name as admin_name')
            ->join('admins', 'admins.id', 'admin_logs.admin_id')
            ->join('admin_actions', 'admin_actions.id', 'admin_logs.action_id');

        if (!empty($input['start_date']) && !empty($input['end_date'])) {
            $start_date = date('Y-m-d 00:00:00',strtotime($input['start_date']));
            $end_date = date('Y-m-d 23:59:59',strtotime($input['end_date']));

            $data= $data->where('admin_logs.created_at', '>=', $start_date)
                ->where('admin_logs.created_at', '<=', $end_date);
        }
        if (!empty($input['action_id'])) {
            $data = $data->where('admin_logs.action_id', $input['action_id']);
        }
        if (!empty($input['admin_id'])) {
            $data = $data->where('admin_logs.admin_id', $input['admin_id']);
        }

        $data = static::get();

        return $data;
    }

    public function storeData($input)
    {
        return static::create($input);
    }

    public function findData($id)
    {
        return static::find($id);
    }

    public function updateData($id, $input)
    {
        return static::find($id)->update($input);
    }

    public function deleteData($id)
    {
        return static::find($id)->delete();
    }
}
