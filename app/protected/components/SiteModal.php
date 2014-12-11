<?php

class SiteModal extends CWidget {
    public $users = array();
    public function init() {
        $users = User::fetchCompanyUsers(Yii::app()->user->cid);
        foreach ($users as $user) {
            array_push($this->users, $user['name']);
        }
    }
    public function run() {
        $cid = Yii::app()->request->getParam('cid');
        $listings = Listing::getListingsForCampaign(Yii::app()->user->cid, $cid);
//        print_r(Yii::app()->request->getParam('cid'));
        $listingsFinal = array();
        foreach ($listings as $list) {
            $usersperlisting = Task::fetcUsersAssignedToSite($list['id'],  $cid, Yii::app()->user->cid);
           // print_r($usersperlisting);die();
            if (!empty($usersperlisting['username'])) {
                $list['monitor'] = $usersperlisting['username'];
            }
            array_push($listingsFinal, $list);
        }
        
        $this->render('siteModal',array('listings' => $listingsFinal));        
    }
}