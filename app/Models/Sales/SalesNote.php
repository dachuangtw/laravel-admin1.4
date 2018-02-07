<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class SalesNote extends Model
{
	protected $table = 'sales_note';

	public function scopeOfNoteWid($query, $note_wid)
    {
        return $query->where('note_wid',  $note_wid)
			->orWhere('note_wid', 'like', '%'.$note_wid.'|')
			->orWhere('note_wid', 'like', $note_wid.'|%')
			->orWhere('note_wid', 'like', '%|'.$note_wid.'|%')
			->orWhere('note_wid', '-1');
    }

	public function scopeOfNoteTarget($query, $note_target)
    {
        return $query->where('note_target',  $note_target)
			->orWhere('note_target', 'like', '%'.$note_target.'|')
			->orWhere('note_target', 'like', $note_target.'|%')
			->orWhere('note_target', 'like', '%|'.$note_target.'|%')
			->orWhere('note_target', '-1');
    }
}
