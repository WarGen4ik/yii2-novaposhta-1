<?php
namespace jones\novaposhta;

/**
 * Class InternetDocument
 * @package jones\novaposhta
 * @author sergii gamaiunov <hello@webkadabra.com>
 *
 * This model contain methods to create and configure return orders
 */
final class AdditionalService extends Api
{
    const SCENARIO_CHECK    = 'CheckPossibilityCreateReturn';
    const SCENARIO_CREATE   = 'save';
    const SCENARIO_DELETE   = 'delete';
    const SCENARIO_LIST     = 'getReturnOrdersList';

    // check
    public $Number;

    // create
    public $IntDocNumber;
    public $PaymentMethod;
    public $Reason;
    public $SubtypeReason;
    public $Note;
    public $OrderType;
    public $ReturnAddressRef;

    // delete
    public $Ref;

    // list (with "Number" and "Ref")
    public $BeginDate;
    public $EndDate;
    public $Page;
    public $Limit;


    public function rules()
    {
        return [
            [['IntDocNumber', 'PaymentMethod', 'Reason', 'SubtypeReason', 'OrderType', 'ReturnAddressRef'], 'required', 'on' => static::SCENARIO_CREATE],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_DELETE]   = ['Ref'];
        $scenarios[self::SCENARIO_CHECK]    = ['Number'];
        $scenarios[self::SCENARIO_CREATE]   = ['IntDocNumber', 'PaymentMethod', 'Reason', 'SubtypeReason', 'OrderType', 'ReturnAddressRef', 'Note'];
        $scenarios[self::SCENARIO_LIST]     = ['Ref', 'Number', 'BeginDate', 'EndDate', 'Page', 'Limit'];
        return $scenarios;
    }

    public function checkPossibilityCreateReturn(){
        $this->call('CheckPossibilityCreateReturn');
    }

    public function create(){
        $this->call('save');
    }

    public function delete(){
        $this->call('delete');
    }

    public function getList(){
        $this->call('getReturnOrdersList');
    }
}
