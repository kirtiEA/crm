<?php

/**
 * EatadsMailer class - wrapper for YiiMailer
 *
 * @package EatadsMailer
 * @author Gaurav Porwal
 * @copyright  Copyright (c) 2014 EatadsMailer
 * @version 1.0, 2013-01-07
 */
/**
 * Include the the YiiMailer class.
 */
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'YiiMailer.php');

class EatadsMailer extends YiiMailer {

    public $CharSet = 'UTF-8';
    private $emView = null;
    private $emTo;
    private $emFrom = null;
    private $emFromName = 'EatAds Admin';
    private $emData;
    private $emSubject;
    private $emBody;
    private $yiiMailer;
    private $emBcc;

    /**
     * Set and configure initial parameters
     * @param string $view View name
     * @param array $data Data array
     * @param string $layout Layout name
     */
    public function __construct($emView = '', $emTo, $emData = array(), $emBcc = array(), $fromName = null, $fromEmail = null) {
        $this->emView = $emView;
        $this->emTo = $emTo;
        $this->emFrom = Yii::app()->params['adminEmail'];
        $this->emData = $emData;
        $this->emBcc = 'gaurav@eatads.com';
        if ($fromName != NULL) {
            $this->emFromName = $fromName;
        }
        if ($fromEmail != NULL) {
            $this->emFrom = $fromEmail;
        }
    }

    public function setEMFrom($address, $name = '', $auto = true) {
        $this->emFrom = $address;
        $this->emFromName = $name;
        //return parent::SetFrom($this->emFrom, $this->emFromName, (int)$auto);
    }

    public function eatadsSend() {

        $mailContent = EmailTemplate::getMailContentBySlug($this->emView);
        $this->emSubject = $mailContent->subject;
        $this->emBody = $mailContent->content;

        foreach ($this->emData as $key => $value) {
            $this->emBody = str_replace('{{' . $key . '}}', $value, $this->emBody);
            $this->emSubject = str_replace('{{' . $key . '}}', $value, $this->emSubject);
        }

        parent::__construct('eatadsMail', array('message' => $this->emBody));
        parent::setFrom($this->emFrom, $this->emFromName);
        parent::setSubject($this->emSubject);
        parent::setTo($this->emTo);
        if (count($this->emBcc)) {
            parent::setBcc($this->emBcc);
        }
        return parent::send();
    }

}
