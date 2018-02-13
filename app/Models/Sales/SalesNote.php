<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class SalesNote extends Model
{
	protected $table = 'sales_note';

    // 範圍，所屬倉庫
	public function scopeOfNoteWid($query, $note_wid)
    {
        return $query->where('note_wid',  $note_wid)
			->orWhere('note_wid', 'like', '%'.$note_wid.'|')
			->orWhere('note_wid', 'like', $note_wid.'|%')
			->orWhere('note_wid', 'like', '%|'.$note_wid.'|%')
			->orWhere('note_wid', '-1');
    }

    // 範圍，業務ID
	public function scopeOfNoteTarget($query, $note_target)
    {
        return $query->where('note_target',  $note_target)
			->orWhere('note_target', 'like', '%'.$note_target.'|')
			->orWhere('note_target', 'like', $note_target.'|%')
			->orWhere('note_target', 'like', '%|'.$note_target.'|%')
			->orWhere('note_target', '-1');
    }

    // 關聯，公告更新人
	public function hasOneAdminUser()
	{
		return $this->hasOne('App\Models\Sales\Admin_user', 'id', 'update_user');
	}
}
