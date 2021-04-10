<?PHP

namespace Raykazi\Seat\SeatApplication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Seat\Services\Models\Note;
use Seat\Web\Http\Controllers\Controller;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Raykazi\Seat\SeatApplication\Models\ApplicationModel;
use Raykazi\Seat\SeatApplication\Models\QuestionModel;
use Raykazi\Seat\SeatApplication\Models\SettingsModel;
use stdClass;


class ApplicationController extends Controller {

    public function getMainPage()
    {
        $application = ApplicationModel::where('user_id', auth()->user()->id)->get();
        $questions = QuestionModel::query()->orderby('order', 'asc')->get();
        $instruction = SettingsModel::query()->get();
        return view('application::apply', compact("application", "questions", "instruction"));
    }

    public function submitApp(Request $request)
    {
        $questions = QuestionModel::query()->orderby('order', 'asc')->get();
        $responses = array();
        $responses["Alt Characters"] =  $request->input('altCharacters');
        $rules = array(
            'altCharacters' => 'nullable|string');
        foreach ($questions as $q)
        {
            $keyName = "#".$q->order;
            if($q->required =="No")
                $value = 'nullable|string';
            else
                $value = 'required|string';
            $rules[$keyName] = $value;
            $responses[$q->question] = $request->input($keyName);
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect(route('application.request'))
                ->withErrors($validator)
                ->withInput($request->all);
        }

        ApplicationModel::create([
            'user_id'           => auth()->user()->id,
            'character_name'    => $request->input('app'),
            'responses'         => json_encode($responses),
            'status'            => 0,
            'approver'          => "None",
        ]);


        return redirect()->back()->with('success', trans('application::application.application_submitted'));
    }

    public function getAboutView()
    {
        return view("application::about");
    }
}
