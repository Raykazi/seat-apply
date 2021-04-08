<?PHP

namespace Raykazi\Seat\SeatApplication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use GuzzleHttp\Client;
use Seat\Web\Http\Controllers\Controller;
use Raykazi\Seat\SeatApplication\Models\ApplicationModel;
use Raykazi\Seat\SeatApplication\Models\QuestionModel;
use Raykazi\Seat\SeatApplication\Models\InstructionModel;
use Raykazi\Seat\SeatApplication\Validation\AddReason;
use GuzzleHttp\Client as Requests;


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
        $instructions = InstructionModel::query()->get();
        return view('application::questions', compact('questions','instructions'));
    }
    public function getQuestion($qid)
    {
        $questions = QuestionModel::find($qid);
        return response()->json($questions);
    }
    public function deleteQuestion($qid)
    {
        $questions = QuestionModel::where('qid', '=', $qid)->delete();
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
        $records = InstructionModel::all();
        if(count($records)== 0)
        {
            InstructionModel::create([
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

    public function updateApplication($app_id, $action)
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
