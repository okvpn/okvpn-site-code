<?php

interface Model_TransactionInerface 
{

    const NOTIFY_URL   = 'https://okvpn.org/user/notification_bitpay/';
    
    const REDERECT_URL = 'https://okvpn.org/user/redirect_url/';

    /**
     * @Column(name = 'date_create', type = 'integer Auto Increment')
     *
     */
    public function getId();

    /**
     * @Column(name = 'date_create', type = 'timestamp NULL')
     *
     */
    public function getDateCreate();

    /**
     * @Column(name = 'date_create', type = 'integer NULL')
     *
     */
    public function getUserId();

    /**
     * @Column(name = 'date_create', type = 'character varying(64) NULL')
     *
     */
    public function getInvoiceId();

    /**
     * @Column(name = 'date_create', type = 'character varying(64) NULL')
     *
     */
    public function getNotificationUrl();

    /**
     * @Column(name = 'date_create', type = 'character varying(18) NULL')
     *
     */
    public function getStatus();

    /**
     * @Column(name = 'date_create', type = 'real NULL')
     *
     */
    public function getPaidSum();

    /**
     * @Column(name = 'date_create', type = 'character varying(64) NULL')
     *
     */
    public function getRedirectUrl();

    /**
     * @Column(name = 'date_create', type = 'character varying(1024) NULL')
     *
     */
    public function getResponse();

    /**
     * @Column(name = 'date_create', type = 'integer NULL')
     *
     */
    public function getBillingId();
}