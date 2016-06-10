<?php


namespace App\Services\Miscellaneous;


use Illuminate\Session\Store;

class FlashMessage {

    /**
     * @var \Session
     */
    private $session;

    private $flash_prefix = 'flash.';

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Flash an information message.
     *
     * @param string $message
     */
    public function info($message)
    {
        $this->message($message, 'info');

        return $this;
    }

    /**
     * Flash a general message.
     *
     * @param  string $message
     * @param  string $level
     * @return $this
     */
    public function message($message, $type = 'info')
    {
        $messages = $this->getCollection($type);
//        $messages = $this->clearDuplicates($message, $messages);

        if(!$messages->contains($message)){
            $messages->push($message);
        }
        $this->session->flash($this->flash_prefix . $type, $messages);

        return $this;
    }

    /**
     * Flash a success message.
     *
     * @param  string $message
     * @return $this
     */
    public function success($message)
    {
        $this->message($message, 'success');

        return $this;
    }

    /**
     * Flash an error message.
     *
     * @param  string $message
     * @return $this
     */
    public function error($message)
    {
        $this->message($message, 'error');

        return $this;
    }

    /**
     * Flash a warning message.
     *
     * @param  string $message
     * @return $this
     */
    public function warning($message)
    {
        $this->message($message, 'warning');

        return $this;
    }

    /**
     * Flash an overlay modal.
     *
     * @param  string $message
     * @param  string $title
     * @return $this
     */
    public function overlay($message, $title = 'Notice')
    {
        $this->message($message);
        $this->session->flash($this->flash_prefix . 'overlay', true);
        $this->session->flash($this->flash_prefix . 'title', $title);

        return $this;
    }

    /**
     * @param $message
     * @param $messages
     * @return mixed
     */
    private function clearDuplicates($message, $messages)
    {
        $messages = $messages->reject(function ($value) use ($message) {
            return $value == $message;
        });

        return $messages;
    }

    /**
     * @param $type
     * @return \Illuminate\Support\Collection
     */
    private function getCollection($type)
    {
        $messages = collect();
        if($this->session->has($this->flash_prefix . $type)) {
            $messages = collect(
                $this->session->get($this->flash_prefix . $type)
            );

            return $messages;
        }

        return $messages;
    }


}