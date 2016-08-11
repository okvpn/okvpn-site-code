<?php 

class Model_Transaction extends ORM implements Model_TransactionInerface
{
    protected $_table_name = 'transaction';


    protected $_belongs_to = array(
        'billing' => array(
            'model'       => 'Billing',
            'foreign_key' => 'billing_id',
        ),
    );

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getDateCreate()
    {
        return $this->date_create;
    }

    /**
     * @inheritdoc
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @inheritdoc
     */
    public function getInvoiceId()
    {
        return $this->invoce_id;
    }

    /**
     * @inheritdoc
     */
    public function getNotificationUrl()
    {
        return self::NOTIFY_URL . $this->notification_url;
    }

    /**
     * @inheritdoc
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @inheritdoc
     */
    public function getPaidSum()
    {
        return $this->paid_sum;
    }

    /**
     * @inheritdoc
     */
    public function getRedirectUrl()
    {
        return self::REDERECT_URL . $this->redirect_url;
    }

    /**
     * @inheritdoc
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @inheritdoc
     */
    public function getBillingId()
    {
        return $this->billing_id;
    }

    public function setDateCreate($date)
    {
        $this->date_create = $date;
        return $this;
    }

    public function setUserId($uid)
    {
        $this->user_id = $uid;
        return $this;
    }

    public function setInvoiceId($id)
    {
        $this->invoce_id = $id;
        return $this;
    }

    public function setNotificationUrl($url)
    {
        $this->notification_url = $url;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setPaidSum($sum)
    {
        $this->paid_sum = $sum;
        return $this;
    }

    public function setRedirectUrl($url)
    {
        $this->redirect_url = $url;
        return $this;
    }

    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    public function setBillingId($id)
    {
        $this->billing_id = $id;
        return $this;
    }

    /**
     * @return Model_BillingInerface
     *
     */
    public function getBilling()
    {
        if (null === $this->getBillingId()) {
            return;
        }

        return $this->billing;
    }

    /**
     * @param Model_BillingInerface $billing
     * @return $this
     */
    public function setBilling(Model_BillingInerface $billing)
    {
        $this->billing = $billing;
        return $this;
    }

    public function refreshBilling()
    {
        $billing = $this->getBilling();

        if (null === $billing) {

            $billing = ORM::factory('Billing');

            $billing->uid  = $this->getUserId();
            $billing->date =  date('Y-m-d H:i:s');
            $billing->type = 'real';
            $billing->amount = $this->getPaidSum();
            $billing->save();

            $this->setBilling($billing);
        } else {

            $billing->amount = $this->getPaidSum();
            $billing->save();
        }

    }
}