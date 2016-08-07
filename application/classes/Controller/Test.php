<?php 

class Controller_Test extends Controller
{
    public function __construct(Request $request, Response $response)
    {
        $user = Model::factory('User');

        if (Kohana::$environment != Kohana::DEVELOPMENT) {
            throw new HTTP_Exception_403();
        }

        parent::__construct($request, $response);
    }


    public function action_index()
    {
       $um = new Model_UserManager();
       var_dump($um->allowUserConnect( new Model_Users(10)));
    }


    public function action_create_invoice()
    {
        $bitpay = ORM::factory('Bitpay');
        $bitpay->createInvoce(41, 1.00);
    }

    public function action_refresh()
    {
        $transaction = ORM::factory('Transaction', 2);
        $bitpay = ORM::factory('Bitpay');
        $bitpay->refreshStatus($transaction);
    }

    public function action_role()
    {
        $user = (new Model_Users())
            ->where('email', '=', 'tsykun314@gmail.com')
            ->find(); 
        var_dump($user->getId());
    }

    public function action_transaction()
    {

    }

    public function action_get_sum()
    {
        $transaction = ORM::factory('Transaction', 2);
        $bitpay = ORM::factory('Bitpay');
        var_dump($bitpay->getTransactionSum($transaction));
    }

    public function action_openssl()
    {
        // (new Model_UserManager())
        //     ->authorizate(new Model_Users(41));
        $userManager = new Model_UserManager();
        var_dump($userManager->secureContext()->getUser());
    }

}