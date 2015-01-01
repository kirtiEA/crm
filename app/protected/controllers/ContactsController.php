<?php

class ContactsController extends Controller
{
    public function actionIndex()
    {
        $contacts = json_encode(CompanyContacts::fetchCompanyContacts(Yii::app()->user->cid));
        $this->render('index', array('contacts' => $contacts));
    }
    
    public function actionfetchCompanyContacts() {
        echo json_encode(CompanyContacts::fetchCompanyContacts(Yii::app()->user->cid));
    }
    
    public function actionMassUploadContact() {
        $data = json_decode(Yii::app()->request->getParam('data'));
        foreach ($data as $contact) {
            $companyContact;
            if (empty($contact->id)) {
                $companyContact = new CompanyContacts();
            } else {
                $companyContact = CompanyContacts::model()->findByPk($contact->id);
            }
            
            $companyContact->name = $contact->name;
            $companyContact->companyid = Yii::app()->user->cid;
            $companyContact->fname = $contact->fname;
            $companyContact->lname = $contact->lname;
            $companyContact->phone1 = $contact->phone1;
            $companyContact->phone2 = $contact->phone2;
            $companyContact->mobile = $contact->mobile;
            $companyContact->email1 = $contact->email1;
            $companyContact->email2 = $contact->email2;
            $companyContact->fax = $contact->fax;
            $companyContact->address = $contact->address;
            $companyContact->website = $contact->website;
            if (empty($contact->id)) {
                $companyContact->createddate = date("Y-m-d H:i:s");
                $companyContact->createdby = Yii::app()->user->id;
                $companyContact->status= 1;
            }
            $companyContact->save();
            if (!empty($contact->brand)) {
                
            }
        }
        echo 1;
    }
}