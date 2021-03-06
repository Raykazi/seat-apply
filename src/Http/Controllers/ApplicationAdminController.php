<?PHP

namespace Raykazi\Seat\SeatApplication\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use GuzzleHttp\Client;
use Seat\Web\Http\Controllers\Controller;
use Raykazi\Seat\SeatApplication\Models\ApplicationModel;
use Raykazi\Seat\SeatApplication\Models\QuestionModel;
use Raykazi\Seat\SeatApplication\Validation\AddReason;


class ApplicationAdminController extends Controller
{

    public function getApplications()
    {
        $applications = ApplicationModel::where('status', '<', '10')->orderby('created_at', 'desc')->get();
        return view('application::list', compact('applications'));
    }
    public function getQuestions()
    {
        $questions = QuestionModel::query()->orderby('order', 'asc')->get();
        return view('application::questions', compact('questions'));
    }

    public function updateApplication($app_id, $action)
    {
        $app = ApplicationModel::find($app_id);

        switch ($action) {
            case 'Approve':
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
    public function srpSaveKillMail(AddKillMail $request)
    {

        KillMail::create([
            'user_id'        => auth()->user()->id,
            'character_name' => $request->input('srpCharacterName'),
            'kill_id'        => $request->input('srpKillId'),
            'kill_token'     => $request->input('srpKillToken'),
            'approved'       => 0,
            'cost'           => $request->input('srpCost'),
            'type_id'        => $request->input('srpTypeId'),
            'ship_type'      => $request->input('srpShipType')
        ]);

        return redirect()->back()->with('success', trans('application::application.submitted'));
    }

    public function srpAddReason(AddReason $request)
    {

        $kill_id = $request->input('srpKillId');

        $killmail  = ApplicationModel::find($kill_id);

        

        if (is_null($killmail))
        return redirect()->back()
            ->with('error', trans('srp::srp.not_found'));

        $reason = $killmail->reason();
        if (!is_null($reason)) 
            $reason->delete();

        ApplicationModel::addNote($request->input('srpKillId'), 'reason', $request->input('srpReasonContent'));
        
        return redirect()->back()
                         ->with('success', trans('srp::srp.note_updated'));
    }
}
