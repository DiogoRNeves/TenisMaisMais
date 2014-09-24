<?php

/**
 * @property User $user the user
 */
class MailActivation extends CApplicationComponent {

    public $user;

    /**
     * 
     * @return boolean whether succedeed or not
     */
    public function allowActivation() {
        if ($this->user === NULL) {
            return false;
        }
        if ($this->user->activationMailSent == 0 && !empty($this->user->contact->email)) {
            $this->user->generateActivationHash();
            $this->sendActivationEmail($this->user);
            $this->user->activationMailSent = 1;
            $this->user->save();
        }
        return true;
    }

    /**
     * @return boolean whether the mail was sent or not
     */
    protected function sendActivationEmail() {
        $userEmailAddress = $this->user->contact->email;
        if ($userEmailAddress !== NULL) {
            $mail = new YiiMailer();
            $mail->setView('activation');
            $subject = 'Ativação do seu perfil na ' . Yii::app()->name;
            $mail->setData(array('message' => $this->generateActivationMailMessage(),
                'name' => $this->user->name, 'description' => $subject));
            $mail->setFrom(Yii::app()->params['adminEmail']);
            $mail->setTo($userEmailAddress);
            $mail->setSubject($subject);
            if ($mail->send()) {
                Yii::app()->user->setFlash('mailSent', array(true, "E-mail de confirmação enviado!"));
                return true;
            }
            Yii::app()->user->setFlash('mailSent', array(false, "Não foi possível enviar o mail de ativação."));
            return false;
        }
        return false;
    }

    /**
     * 
     * @return String
     */
    protected function generateActivationMailMessage() {
        $link = Yii::app()->createAbsoluteUrl("user/activate", array(
            'activationHash' => $this->user->activationHash,
            'userID' => $this->user->primaryKey,
        ));
        $htmlLink = CHtml::link($link, $link, array('target' => '_blank'));
        return "Para ativar o seu perfil, por favor visite " . CHtml::tag('p', array(), $htmlLink);
    }

}
