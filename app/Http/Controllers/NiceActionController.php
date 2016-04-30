<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

use App\NiceAction;
use App\NiceActionLog;
use DB;

class NiceActionController extends Controller
{
    public function getHome()
    {
        //*** Getting actions to display ***
        $actions = NiceAction::orderBy('niceness', 'desc')->get();
        
        //*** Getting logged action to display ***
        $logged_actions = NiceActionLog::paginate(5);
        
        // Select some records in NiceActionLog model (nice_action_logs table)
        /*$logged_actions = NiceActionLog::whereHas('nice_action', function($query) {
            $query->where('name', '=', 'Kiss');
        })->get();*/
        
        return view('home', ['actions' => $actions, 'logged_actions' => $logged_actions]);
    }

    public function getNiceAction($action, $name = null)
    {
        if ($name === null) {
            $name = 'you';
        }
        
        // Inserts NiceActionLog
        $nice_actions = NiceAction::where('name', $action)->first();
        $nice_actions_log = new NiceActionLog();
        $nice_actions->logged_actions()->save($nice_actions_log);
        
        // Returns newlly created $action to 'actions/nice.blade.php' view
        return view('actions.nice', ['action' => $action, 'name' => $name]);
    }
    
    public function postInsertNiceAction(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|alpha|unique:nice_actions',
            'niceness' => 'required|numeric',
        ]);
        
        // Inserts NiceAction
        $action = new NiceAction();
        $action->name = ucfirst(strtolower($request['name']));
        $action->niceness = $request['niceness'];
        $action->save();
        
        $actions = NiceAction::all();
        
        // Returns $actions to view
        if ($request->ajax()) {
            return response()->json();
        }
        //return redirect()->route('home', ['actions' => $actions]);
        return redirect()->route('home');
    }
    
    private function transformName($name)
    {
        $prefix = 'KING ';
        return $prefix . strtoupper($name);
    }
    
    // This function does not work in php class methods
    private function phpAlert($msg) {
        //echo '<script type="text/javascript">alert("' . $msg . '")</script>';
        print '<script type="text/javascript">';
        print 'alert($msg)';
        print '</script>';  
    }
    
    public function getHomeTesty()
    {
        //*** Insert new log each time home page is reloded ****
        // DB query version
        // "insert" returns boolen and "insertGetId" returns added log Id
        /*$query = DB::table('nice_action_logs')
                    ->insertGetId([
                        'nice_action_id' => DB::table('nice_actions')->select('id')
                        ->where('name', 'Kiss')->first()->id
                    ]);*/
        
        // Eloquent version
        /*$nice_actions = NiceAction::where('name', 'Kiss')->first();
        $nice_actions_log = new NiceActionLog();
        $nice_actions->logged_actions()->save($nice_actions_log);*/

        //*** Updating db data ***
        $hug = NiceAction::where('name', 'Hug')->first();
        $hug = null;
        if ($hug) {
            $hug->name = "ChangedHug";
            $hug->update();
        }
        //*** Deleting db data ***
        // This code can cause db inconsistency when logs exists for 'Wave' action
        $wave = NiceAction::where('name', 'Wave')->first();
        $wave = null;
        if ($wave) {
            $wave->delete();
        }
        // This deletion does not cause any inconsistency in db
        //$logs = NiceActionLog::where('nice_action_id', '3')->delete(); // for 'Kiss'

        //*** Getting actions to display ***
        //$actions = NiceAction::all();
        // Alternative db access to above line
        //$actions = DB::table('nice_actions')->get();
        
        $actions = NiceAction::orderBy('niceness', 'desc')->get();
        
        // Select some records in NiceAction model (nice_actions table)
        //$actions = NiceAction::where('name', 'Kiss')->get();
        
        //*** Getting logged action to display ***
        $logged_actions = NiceActionLog::all();
        
        // Select some records in NiceActionLog model (nice_action_logs table)
        /*$logged_actions = NiceActionLog::whereHas('nice_action', function($query) {
            $query->where('name', '=', 'Kiss');
        })->get();*/
        
        //return view('home', ['actions' => $actions, 'logged_actions' => $logged_actions]);

        //*** Getting 3rd parameter to display using dd() function in home view ***
        // Access to table join by db query
        $query = DB::table('nice_action_logs')
                    ->join('nice_actions', 'nice_action_logs.nice_action_id', '=', 'nice_actions.id')
                    //->where('nice_actions.name', '=', 'Kiss')
                    ->get();
        
        // Getting count, max etc.
        //$query = DB::table('nice_action_logs')->where('id', '>', '3')->count();
        //$query = DB::table('nice_action_logs')->max('id');
        return view('home', ['actions' => $actions, 'logged_actions' => $logged_actions, 'db' => $query]);
    }
}
