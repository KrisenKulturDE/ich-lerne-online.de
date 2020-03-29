<?php
namespace ProcessWire;

class HomePage extends TwackComponent {
    public function __construct($args) {
        parent::__construct($args);

        $this->targetAudiences = $this->wire('pages')->find('template.name=target_audience');
        $this->textteaserSectionPage = $this->wire('pages')->get(1175);
        $this->containerPage = $this->wire('pages')->find('template.name=items_container')->first();
        $this->searchAction = $this->containerPage->url;

        $this->targetAudiences = $this->wire('pages')->find('template.name=target_audience');
        $this->categoryOptions = $this->wire('pages')->find('template.name=category, sort=title');

        if ($this->page->block_form_submission !== true) {
            $this->setTemplate(wire('templates')->get(44));

            if ($this->wire('input')->post->action === 'submit-item') {
                $this->evaluationResponse = $this->evaluateRequest();
            }

            if (empty($this->getAntispamCode())) {
                $this->regenerateAntispamCode();
            }
        }
    }

    protected function regenerateAntispamCode() {
        wire('session')->set('antispam_code_' . $this->formOrigin, mt_rand(1000, 9999));
    }

    protected function clearAntispamCode() {
        wire('session')->remove('antispam_code_' . $this->formOrigin);
    }

    public function getAntispamCode() {
        return wire('session')->get('antispam_code_' . $this->formOrigin);
    }

    public function getCurrentValue($fieldname){
        if (!empty($this->evaluationResponse['fields'][$fieldname]) && is_array($this->evaluationResponse['fields'][$fieldname])) {
            if (isset($this->evaluationResponse['fields'][$fieldname]['currentValue'])) {
                return $this->evaluationResponse['fields'][$fieldname]['currentValue'];
            }
        }
        return false;
    }

    public function getErrorMsg($fieldname){
        if (!empty($this->evaluationResponse['fields'][$fieldname]) && is_array($this->evaluationResponse['fields'][$fieldname])) {
            if (!empty($this->evaluationResponse['fields'][$fieldname]['error']) && is_array($this->evaluationResponse['fields'][$fieldname]['error'])) {
                return implode(', ', $this->evaluationResponse['fields'][$fieldname]['error']);
            }
        }
        return false;
    }

    public function getSuccessMsg($fieldname){
        if (!empty($this->evaluationResponse['fields'][$fieldname]) && is_array($this->evaluationResponse['fields'][$fieldname])) {
            if (!empty($this->evaluationResponse['fields'][$fieldname]['success']) && is_array($this->evaluationResponse['fields'][$fieldname]['error'])) {
                return implode(', ', $this->evaluationResponse['fields'][$fieldname]['success']);
            }
        }
        return false;
    }

    public function shouldClearFormFields(){
        return is_array($this->evaluationResponse) && $this->evaluationResponse['status'] === true;
    }

    /**
     * Responds to POST requests that match this form ID.
     */
    protected function evaluateRequest() {
        // A post-request of this modal is to be processed

        // Collection of all errors that occurred in the form:
        $output = array(
            // Messages per field:
            'fields' => array(),

            // General error messages:
            'error' => array(),

            // General success messages:
            'success' => array()
        );

        try {
            // Check Honeypot:
            if (!empty(wire('input')->post->information)) {
                throw new \Exception($this->_('Form could not be submitted.'));
            }

            // Throws Exception if csrf validation fails:
            wire('session')->CSRF->validate($this->formOrigin);

            // Evaluate antispam-code:
            $antispamCode = wire('input')->post->int('antispam_code');

            $output['fields']['antispam_code'] = array(
                'name'           => 'antispam_code',
                'label'          => 'Antispam-Code',
                'currentValue'   => '',
                'error'          => array(),
                'success'        => array()
            );

            $errorFlag             = false;
            if ($this->getAntispamCode() !== $antispamCode) {
                $errorFlag                                 = true;
                $output['fields']['antispam_code']['error'][] = $this->_('Please fill in the code above.');
            }

            $newRequest            = new Page();
            $newRequest->template  = $this->template; // "item"-template
            $newRequest->addStatus(Page::statusUnpublished); 
            $values                = [];

            foreach ($this->fields as $fieldParams) {
                $field = $this->template->fieldgroup->getField($fieldParams->name, true);

                $inputField = $field->getInputfield($newRequest);
                $inputField->processInput(wire('input')->post);

                $output['fields'][$field->name] = array(
                    'name'           => $field->name,
                    'label'          => $field->label,
                    'currentValue'   => $inputField->attr('value'),
                    'error'          => array(),
                    'success'        => array()
                );
                $values[$field->name] = $inputField->attr('value');

                if (in_array($field->name, ['sender_email', 'sender_reason', 'link'])) {
                    $field->required = true;
                }

                // The field has no content, but is required:
                if (!!$field->required && $inputField->isEmpty()) {
                    $errorFlag                                 = true;
                    $output['fields'][$field->name]['error'][] = $this->_('This field is mandatory.');
                }

                // Error messages from the input field are collected for each field name:
                if ($inputField->getErrors()) {
                    $errorFlag = true;
                    foreach ($inputField->getErrors(true) as $error) {
                        $output['fields'][$field->name]['error'][] = $error;
                    }
                }

                $newRequest->{$field->name} = $inputField->attr('value');
            }

            if ($errorFlag) {
                throw new \Exception($this->_('One or more fields have errors.'));
            }

            $messageIdent = md5(http_build_query($values));
            if (!empty(wire('session')->get($this->formOrigin)) && wire('session')->get($this->formOrigin) === $messageIdent) {
                throw new \Exception($this->_('Form was already submitted.'));
            }

            $newRequest->parent  = $this->containerPage;

            if (!$newRequest->save()) {
                $output['error']['processwire_error'] = array(
                    'text'   => $this->_('The request could not be saved.'),
                    'fields' => array()
                );
                throw new \Exception($this->_('The request could not be saved.'));
            }
        } catch (WireCSRFException $e) {
            $output['error']['csrf_error']   = $this->_('This request was apparently forged and therefore aborted.');
            $output['submission_blocked']    = true;
            $output['status']                = false;
            if ($this->twack->isTwackAjaxCall()) {
                Twack::sendResponse($output, 403);
            }
            return $output;
        } catch (\Exception $e) {
            $output['error']['form_error']      = $e->getMessage();
            $output['submission_blocked']       = false;
            $output['status']                   = false;

            if ($this->twack->isTwackAjaxCall()) {
                Twack::sendResponse($output, 400);
            }
            return $output;
        }

        try {
            $this->sendNotification($newRequest);
        } catch (\Exception $e) {
            wire('log')->save('forms', 'An error occurred while sending the notification: ' . $e->getMessage());
        }

        $this->clearAntispamCode();
        wire('session')->set($this->formOrigin, $messageIdent);
        wire('session')->CSRF->resetToken($this->formularName);
        unset($_POST);

        $output['status']                = true;
        $output['success']['finished']   = $this->_('Your request was processed successfully.');

        $recipients = [];
        if($this->page->template->hasField('user_reference')){
            foreach($this->page->user_reference as $u){
                if(empty((string)$u->email)){
                    continue;
                }
                $recipients[] = (string)$u->email;
            }
        }
        if (!empty($recipients)) {
            // Email-Response:
            $email = wireMail();
            $email->header('X-Mailer', wire('pages')->get(1)->httpUrl . '');

            $email->to($recipients);

            $email->subject('Neue Anfrage');
            $email->body('Hurra! \r\nAuf https://ich-lerne-online.org wurde ein neuer Beitrag eingereicht! \r\n\r\nJetzt muss er nur noch redaktionell überprüft und mit Zusatzinformationen anggereichert werden. \r\nKönntest du das vielleicht übernehmen? \r\nDu gelangst direkt zur Redaktionsansicht des Beitrags über diesen Link: \r\nhttps://ich-lerne-online.org/redaktion/page/edit/?id=' . $newRequest->id . ' \r\n\r\nVielen Dank für deine Unterstützung! \r\nDein ich-lerne-online-Roboter :-)');

            $email->bodyHTML('<p><strong>Hurra!</strong><br>Auf https://ich-lerne-online.org wurde ein neuer Beitrag eingereicht!</p><p>Jetzt muss er nur noch redaktionell überprüft und mit Zusatzinformationen anggereichert werden. <br>Könntest du das vielleicht übernehmen?</p><p>Du gelangst direkt zur Redaktionsansicht des Beitrags über diesen Link: <br><a href="https://ich-lerne-online.org/redaktion/page/edit/?id=' . $newRequest->id . '">https://ich-lerne-online.org/redaktion/page/edit/?id=' . $newRequest->id . ' </a></p><p><strong>Vielen Dank für deine Unterstützung!</strong><br>Dein ich-lerne-online-Roboter :-)</p>');

            $email->send();
        }

        if ($this->twack->isTwackAjaxCall()) {
            Twack::sendResponse($output, 200);
        }

        return $output;
    }

    protected function setTemplate(Template $template) {
        $this->fields   = new WireArray();
        $this->template = $template;

        if (empty($this->formName)) {
            $this->formName = $this->template->name;
        }

        foreach ($template->fields as $field) {
            try {
                // Do not output system fields:
                if ($field->hasFlag(Field::flagSystem) && $field->name !== 'title') {
                    continue;
                }

                if(!$field->type instanceof FieldtypeText && !$field->type instanceof FieldtypeInteger && !$field->type instanceof FieldtypePage){
                    continue;
                }

                $field = $template->fieldgroup->getField($field->name, true); // Include fieldgroup settings

                if (!($field instanceof Field)) {
                    continue;
                }

                $this->fields->add($field);
            } catch (\Exception $e) {
                Twack::devEcho($e->getMessage());
            }
        }
    }
}