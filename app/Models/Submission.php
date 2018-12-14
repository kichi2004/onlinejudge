<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\Models\Lang;
use App\Models\Problem;


class Submission extends Model
{
    protected $fillable = ['problem', 'lang', 'sender', 'size', 'status', 'exec_time'];
    protected $dates = ['time'];
    protected $dateFormat='Y-m-d H:i:s';
    const CREATED_AT = null;
    const UPDATED_AT = null;

    /**
     * @inheritdoc
     */
    public static function create(array $data) {
        $source=$data['source'];
        unset($data['source']);
        $data['size']=strlen($source);
        $data['sender']=auth()->id();


        $model = static::query()->create($data);
        $id=$model->id;
        $lang=Lang::find($model->lang);
        Storage::disk('data')->makeDirectory('submissions/'.$id);
        Storage::disk('data')->put('submissions/'.$id.'/source.'.$lang->extension, $source);

        $model->update(['status'=>'WJ']);

        return $model;
    }

    /**
     * filters only visible problem
     */
    public function scopeOwnFilter($query){
        return $query->Where('sender', auth()->id());
    }

    /**
     * filters only visible problem
     */
    public function scopeFilterWithRequest($query){
        $request=request();
        if($request->filled('problem'))
            $query->Where('problem', $request->input('problem'));

        if($request->filled('lang'))
        $query->Where('lang', $request->input('lang'));

        if($request->filled('status'))
        $query->Where('status', $request->input('status'));

        if($request->filled('sender'))
        $query->Where('sender', $request->input('sender'));

        return $query;
    }

    /**
     * returns whether the problem visible for the user.
     * @return bool
     */
    public function is_visible(){
        if(auth()->user()->permission & 8)return true;
        return $this->creator==auth()->id();
    }
    
    /**
     * returns the creator of problem 
     */
    public function get_problem_creator(){
        return Problem::find($this->problem)->creator;
    }

    /**
     * returns lang name of the submission
     * @returns string
     */
    public function get_lang_name(){
        return Lang::find($this->lang)->name;
    }

    /**
     * returns problem title of the submission
     * @returns string
     */
    public function get_problem_title(){
        return Problem::find($this->problem)->title;
    }

    /**
     * returns the submission source
     * @return string
     */
    public function get_source(){
        return Storage::disk('data')->get('submissions/'.$this->id.'/source.'.Lang::find($this->lang)->extension);
    }

    /**
     * returns whether the submission has compile result
     * @return bool
     */
    public function has_compile_result(){
        return Storage::disk('data')->exists('submissions/'.$this->id.'/judge_log.txt');
    }

    /**
     * returns compile result
     * @return string
     */
    public function get_compile_result(){

        return Storage::disk('data')->get('submissions/'.$this->id.'/judge_log.txt');
    }

    /**
     * returns whether the submission has judge result
     * @return bool
     */
    public function has_judge_result(){
        return Storage::disk('data')->exists('submissions/'.$this->id.'/judge_log.json');
    }

    /**
     * returns judge result as object
     * @return object
     */
    public function get_judge_result(){
        return json_decode(Storage::disk('data')->get('submissions/'.$this->id.'/judge_log.json'));
    }
    
    /**
     * rejudge the submission
     */
    public function rejudge(){
        $this->update(['status' => 'WR']);
    }
}
