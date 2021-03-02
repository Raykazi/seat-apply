<?PHP

namespace Raykazi\Seat\SeatApplication\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Seat\Services\Models\Note;
use Seat\Web\Http\Controllers\Controller;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Raykazi\Seat\SeatApplication\Models\ApplicationModel;
use Raykazi\Seat\SeatApplication\Models\QuestionModel;
use Raykazi\Seat\SeatApplication\Validation\AddApplication;
use stdClass;


class ApplicationController extends Controller {

    public function getMainPage()
    {
        $application = ApplicationModel::where('user_id', auth()->user()->id)->get();
        $questions = QuestionModel::query()->orderby('order', 'asc')->get();
        return view('application::apply', compact("application", "questions"));
    }

    public function srpGetKillMail(Request $request)
    {
        $totalKill = [];

        $response = (new Client())->request('GET', $request->km);

        $killMail = json_decode($response->getBody());
        $totalKill = array_merge($totalKill, $this->srpPopulateSlots($killMail));
        preg_match('/([a-z0-9]{35,42})/', $request->km, $tokens);
        $totalKill['killToken'] = $tokens[0];

        return response()->json($totalKill);
    }

    public function submitApp(AddApplication $request)
    {
        $questions = QuestionModel::query()->orderby('order', 'asc')->get();
        $responses = array();
        $responses["Alt Characters"] =  $request->input('alts');
        foreach ($questions as $q)
        {
            $responses[$q->question] = $request->input('question#'.$q->qid);
        }

        ApplicationModel::create([
            'user_id'        => auth()->user()->id,
            'character_name' => $request->input('app'),
            'responses'     => json_encode($responses),
            'status'       => 0,
            'approver'           => "None",
        ]);


        return redirect()->back()
            ->with('success', trans('application::application.submitted'));
    }

    public function submitQuestion(AddApplication $request)
    {
        $questions = QuestionModel::query()->orderby('order', 'asc')->get();
        $responses = array();
        foreach ($questions as $q)
        {
            $responses[$q->qid] = $request->input('question#'.$q->qid);
        }

        ApplicationModel::create([
            'user_id'        => auth()->user()->id,
            'character_name' => $request->input('srpCharacterName'),
            'kill_id'        => $request->input('srpKillId'),
            'kill_token'     => $request->input('srpKillToken'),
            'approved'       => 0,
            'cost'           => $request->input('srpCost'),
            'type_id'        => $request->input('srpTypeId'),
            'ship_type'      => $request->input('srpShipType')
        ]);


        return redirect()->back()
                         ->with('success', trans('application::application.submitted'));
    }

	public function getInsurances($kill_id)
	{
		$killmail = ApplicationModel::where('kill_id', $kill_id)->first();

		if (is_null($killmail))
			return response()->json(['msg' => sprintf('Unable to retried killmail %s', $kill_id)], 404);

		$data = [];

		foreach ($killmail->type->insurances as $insurance) {

			array_push($data, [
				'name' => $insurance->name,
				'cost' => $insurance->cost,
				'payout' => $insurance->payout,
				'refunded' => $insurance->refunded(),
				'remaining' => $insurance->remaining($killmail),
			]);

		}

		return response()->json($data);
	}

	public function getPing($kill_id)
	{
		$killmail = ApplicationModel::find($kill_id);

		if (is_null($killmail))
			return response()->json(['msg' => sprintf('Unable to retrieve kill %s', $kill_id)], 404);

		if (!is_null($killmail->ping()))
			return response()->json($killmail->ping());

		return response()->json(['msg' => sprintf('There are no ping information related to kill %s', $kill_id)], 204);
    }
    
    public function getReason($kill_id)
	{
		$killmail = ApplicationModel::find($kill_id);

		if (is_null($killmail))
			return response()->json(['msg' => sprintf('Unable to retrieve kill %s', $kill_id)], 404);

		if (!is_null($killmail->reason()))
			return response()->json($killmail->reason());

		return response()->json(['msg' => sprintf('There are no reason information related to kill %s', $kill_id)], 204);
	}

    private function srpPopulateSlots(stdClass $killMail) : array
    {
        $priceList = [];
        $slots = [
            'killId' => 0,
            'price' => 0.0,
            'shipType' => null,
            'characterName' => null,
            'cargo' => [],
            'dronebay' => [],
        ];

        foreach ($killMail->victim->items as $item) {
            $searchedItem = InvType::find($item->item_type_id);
            $slotName = InvFlag::find($item->flag);
			if (!is_object($searchedItem)) {
			} else {
	            array_push($priceList, $searchedItem->typeName);

            	switch ($slotName->flagName)
            	{
        	        case 'Cargo':
    	                $slots['cargo'][$searchedItem->typeID]['name'] = $searchedItem->typeName;
	                    if (!isset($slots['cargo'][$searchedItem->typeID]['qty']))
                	        $slots['cargo'][$searchedItem->typeID]['qty'] = 0;
            	        if (property_exists($item, 'quantity_destroyed'))
        	                $slots['cargo'][$searchedItem->typeID]['qty'] = $item->quantity_destroyed;
    	                if (property_exists($item, 'quantity_dropped'))
	                        $slots['cargo'][$searchedItem->typeID]['qty'] += $item->quantity_dropped;
                	    break;
            	    case 'DroneBay':
        	            $slots['dronebay'][$searchedItem->typeID]['name'] = $searchedItem->typeName;
    	                if (!isset($slots['dronebay'][$searchedItem->typeID]['qty']))
	                        $slots['dronebay'][$searchedItem->typeID]['qty'] = 0;
                    	if (property_exists($item, 'quantity_destroyed'))
                	        $slots['dronebay'][$searchedItem->typeID]['qty'] = $item->quantity_destroyed;
            	        if (property_exists($item, 'quantity_dropped'))
        	                $slots['dronebay'][$searchedItem->typeID]['qty'] += $item->quantity_dropped;
    	                break;
	                default:
                	    if (!(preg_match('/(Charge|Script|[SML])$/', $searchedItem->typeName))) {
            	            $slots[$slotName->flagName]['id'] = $searchedItem->typeID;
        	                $slots[$slotName->flagName]['name'] = $searchedItem->typeName;
    	                    if (!isset($slots[$slotName->flagName]['qty']))
	                            $slots[$slotName->flagName]['qty'] = 0;
                        	if (property_exists($item, 'quantity_destroyed'))
                    	        $slots[$slotName->flagName]['qty'] = $item->quantity_destroyed;
                	        if (property_exists($item, 'quantity_dropped'))
            	                $slots[$slotName->flagName]['qty'] += $item->quantity_dropped;
        	            }
   	                break;
	            }
            }
        }

        $searchedItem = InvType::find($killMail->victim->ship_type_id);
        $slots['typeId'] = $killMail->victim->ship_type_id;
        $slots['shipType'] = $searchedItem->typeName;
        array_push($priceList, $searchedItem->typeName);
        $prices = $this->srpGetPrice($priceList);

        $pilot = CharacterInfo::find($killMail->victim->character_id);

        $slots['characterName'] = $killMail->victim->character_id;
        if (!is_null($pilot))
            $slots['characterName'] = $pilot->name;

        $slots['killId'] = $killMail->killmail_id;
        $slots['price'] = $prices->appraisal->totals->sell;

        return $slots;
    }

    private function srpGetPrice(array $priceList) : stdClass
    {

        $partsList = implode("\n", $priceList);
        
        $response = (new Client())
            ->request('POST', 'http://evepraisal.com/appraisal.json?market=jita', [
                'multipart' => [
                    [
                        'name' => 'uploadappraisal',
                        'contents' => $partsList,
                        'filename' => 'notme',
                        'headers' => [
                            'Content-Type' => 'text/plain'
                        ]
                    ],
                ]
            ]);

        return json_decode($response->getBody()->getContents());
    }

    public function getAboutView()
    {
        return view("application::about");
    }

    public function getInstructionsView()
    {
        return view("srp::instructions");
    }
}
