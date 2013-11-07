<?php

/**
 * Description of JsonResponse
 *
 * @author Ivan Batić <ivan.batic@live.com>
 */

namespace Orangehill\Photon\Library\Support;

use Illuminate\Support\MessageBag;

class ApiResponse
{

    /** @var boolean */
    protected $success = true;

    /** @var int Http status code */
    protected $statusCode = 200;

    /** @var mixed Response content */
    protected $content;

    /** @var array Custom fields */
    protected $customFields = array();

    /** @var MessageBag */
    protected $messageBag;

    public function __construct(MessageBag $messageBag)
    {
        $this->messageBag = $messageBag;
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function setSuccess($success)
    {
        $this->success = (bool) $success;
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($status)
    {
        $this->statusCode = (int) $status;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($data)
    {
        $this->content = $data;
        return $this;
    }

    public function setMessageBag(MessageBag $messages = null)
    {
        $this->messageBag = $messages;
        return $this;
    }

    public function getMessages()
    {
        return $this->messageBag->getMessages();
    }

    public function addMessage($key, $message)
    {
        $this->messageBag->add($key, $message);
        return $this;
    }

    public function getCustomFields()
    {
        return $this->customFields;
    }

    public function setCustomFields(array $customFields)
    {
        $this->customFields = $customFields;
        return $this;
    }

    public function setField($key, $value)
    {
        $this->customFields[$key] = $value;
        return $this;
    }

    public function getField($key)
    {
        return $this->customFields[$key];
    }

    public function toArray()
    {
        return array(
            'success'     => $this->getSuccess(),
            'status_code' => $this->getStatusCode(),
            'content'     => $this->getContent(),
            'messages'    => $this->getMessages()
            ) + $this->getCustomFields();
    }

    public function toJsonResponse()
    {
        return \Response::json($this->toArray(), $this->getStatusCode());
    }

}
