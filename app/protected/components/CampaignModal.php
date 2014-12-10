<?php

class CampaignModal extends CWidget {
    public $users = [];
    public function init() {
        $users = User::fetchCompanyUsers(Yii::app()->user->cid);
        foreach ($users as $user) {
            array_push($this->users, $user['name']);
        }
    }
    public function run() {
        $this->render('campaignModal');        
    }
}