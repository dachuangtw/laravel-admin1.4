<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesNotes extends Model
{
    //業務公告
    protected $table = 'sales_note';
    //主鍵
    protected $primaryKey = 'id';

    //公告對象(用|分隔)
    public function getNoteTargetAttribute($note_target)
    {
        if (is_string($note_target)) {
            return explode('|',$note_target);
        }

        return $note_target;
    }

    public function setNoteTargetAttribute($note_target)
    {
        if (is_array($note_target)) {
            $this->attributes['note_target'] = implode('|',$note_target);
        }
    }

    //倉庫(用|分隔)
    public function getNoteWidAttribute($note_wid)
    {
        if (is_string($note_wid)) {
            return explode('|',$note_wid);
        }

        return $note_wid;
    }

    public function setNoteWidAttribute($note_wid)
    {
        if (is_array($note_wid)) {
            $this->attributes['note_wid'] = implode('|',$note_wid);
        }
    }
}
