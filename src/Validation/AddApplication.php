<?PHP

namespace Raykazi\Seat\SeatApplication\Validation;

use Illuminate\Foundation\Http\FormRequest;
use Raykazi\Seat\SeatApplication\Models\QuestionModel;

class AddApplication extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $questions = QuestionModel::query()->orderby('order', 'asc')->get();
        $rules = array(
            'altCharacters' => 'nullable|string');
        foreach ($questions as $q)
        {
            $keyName = "question#".$q->qid;
            $value = 'required|string';
            $rules[$keyName] = $value;
        }
        return $rules;
    }
}

