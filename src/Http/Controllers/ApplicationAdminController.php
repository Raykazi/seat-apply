<?PHP

namespace Raykazi\Seat\SeatApplication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use GuzzleHttp\Client;
use Seat\Web\Http\Controllers\Controller;
use Raykazi\Seat\SeatApplication\Models\ApplicationModel;
use Raykazi\Seat\SeatApplication\Models\QuestionModel;
use Raykazi\Seat\SeatApplication\Models\SettingsModel;
use Raykazi\Seat\SeatApplication\Validation\AddReason;
use GuzzleHttp\Client as Requests;
use Illuminate\Support\Facades\Log;


class ApplicationAdminController extends Controller
{

    public function getApplications()
    {
        $applications = ApplicationModel::query()->orderby('created_at', 'desc')->get();
        return view('application::list', compact('applications'));
    }
    public function getQuestions()
    {
        $questions = QuestionModel::query()->orderby('order', 'asc')->get();
        $instructions = SettingsModel::query()->get();
        return view('application::questions', compact('questions','instructions'));
    }
    public function getApplication($app_id)
    {
        $app = ApplicationModel::find($app_id);
        $alts = json_decode($app->responses, true);
        $questions = array_splice($alts, 1);
        $final = "";
        foreach ($questions as $q => $a)
        {
            $final .= trim($q)."\r\n ".trim($a)."\r\n\n";
        }
        Log::error($final);
        return json_encode(['character_name' => $app->character_name, 'alt_characters' => $alts["Alt Characters"], 'responses' => $final]);
    }
    public function getQuestion($qid)
    {
        $questions = QuestionModel::find($qid);
        return response()->json($questions);
    }
    public function deleteQuestion($qid)
    {
        QuestionModel::where('qid', '=', $qid)->delete();
        return redirect()->back()->with('success', trans('application::application.question_deleted'));
    }
    public function updateSettings(Request $request)
    {
        $rules = array(
            'corpName' => 'required|string',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect(route('application.questions'))
                ->withErrors($validator)
                ->withInput($request->all);
        }
        $instructions = $request->input('message', '');
        $corpName = $request->input('corpName','');
        $records = SettingsModel::all();
        if(count($records)== 0)
        {
            SettingsModel::create([
                'instructions' => $instructions,
                'corp_name'        => $corpName
            ]);

        } else
        {
            $records[0]->instructions = $instructions;
            $records[0]->corp_name = $corpName;
            $records[0]->save();
        }
        return redirect()->back()->with('success', trans('application::application.settings_updated'));
    }
    public function submitQuestion(Request $request)
    {
        $rules = array(
            'questionNumber' => 'required|string',
            'questionInput' => 'required|string',
            'questionHint' => 'nullable|string',
            'questionRequired' => 'required|string',
            'questionType' => 'required|string',
            'questionOptions' => 'nullable|string',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect(route('application.questions'))
                ->withErrors($validator)
                ->withInput($request->all);
        }
        QuestionModel::create([
            'order' => $request->input('questionNumber'),
            'question'        => $request->input('questionInput'),
            'type'     => $request->input('questionType'),
            'options'       => $request->input('questionOptions'),
            'required'           => $request->input('questionRequired'),
            'hint'        => $request->input('questionHint')
        ]);

        return redirect()->back()->with('success', trans('application::application.application_submitted'));
    }
    public function updateQuestion(Request $request)
    {
        $id = $request->input('questionID');
        $rules = array(
            'questionNumber' => 'required|string',
            'questionInput' => 'required|string',
            'questionHint' => 'nullable|string',
            'questionRequired' => 'required|string',
            'questionType' => 'required|string',
            'questionOptions' => 'nullable|string',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect(route('application.questions'))
                ->withErrors($validator)
                ->withInput($request->all);
        }
        $result = QuestionModel::find($id);
        $result->order = $request->input('questionNumber');
        $result->question = $request->input('questionInput');
        $result->type = $request->input('questionType');
        $result->options = $request->input('questionOptions');
        $result->required = $request->input('questionRequired');
        $result->hint = $request->input('questionHint');
        $result->save();
        return redirect()->back()->with('success', trans('application::application.question_updated'));
    }
    public function updateApplication($app_id, $action)
    {
        if($action == "Delete")
        {
            ApplicationModel::where('application_id', '=', $app_id)->first()->delete();
            return json_encode(['name' => $action, 'value' => $app_id, 'approver' => auth()->user()->name]);
        } else
        {
            $app = ApplicationModel::find($app_id);
            switch ($action) {
                case 'Accept':
                    $app->status = '3';
                    break;
                case 'Reject':
                    $app->status = '-1';
                    break;
                case 'Review':
                    $app->status = '1';
                    break;
                case 'Interview':
                    $app->status = '2';
                    break;
            }

            $app->approver = auth()->user()->name;
            $app->save();
            return json_encode(['name' => $action, 'value' => $app_id, 'approver' => auth()->user()->name]);
        }
    }
}
