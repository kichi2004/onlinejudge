<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;


class ProblemController extends Controller
{
    public function list(){
        $problems = Problem::visibleFilter()->get();
        return view('problems/list', ['problems' => $problems]);
    }

    public function problem($id){
        $problem = Problem::find($id);
        abort_if($problem===NULL,404);
        //abort_if($problem->open!==NULL && $problem->creator!==auth()->id(),403);
        abort_unless($problem->is_visible(),403);

        return view('problems/problem', ['problem' => $problem]);
    }

    public function editorial($id){
        $problem = Problem::find($id);
        abort_if($problem===NULL,404);
        abort_unless($problem->is_visible(),403);
        abort_unless($problem->has_editorial(),404);
        return view('problems/editorial', ['problem' => $problem]);
    }

}