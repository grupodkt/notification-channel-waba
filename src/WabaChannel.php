<?php
namespace NotificationChannels\Waba;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use NotificationChannels\Waba\Exceptions\CouldNotSendNotification;

class WabaChannel
{
    /**
     * @var Waba
     */
    protected $waba;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * WabaChannel constructor.
     *
     * @param Waba     $waba
     * @param Dispatcher $events
     */
    public function __construct(Waba $waba, Dispatcher $events)
    {
        $this->waba    = $waba;
        $this->events  = $events;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed                                  $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $to      = $this->getTo($notifiable);
            $message = $notification->toWaba($notifiable);
            if (is_string($message)) {
                $message = new WabaMessage($message);
            }
            if (!$message instanceof WabaMessage) {
                throw CouldNotSendNotification::invalidMessageObject($message);
            }

            return $this->waba->sendMessage($message, $to);
        } catch (Exception $exception) {
            $this->events->dispatch(
                new NotificationFailed($notifiable, $notification, 'waba', ['message' => $exception->getMessage()])
            );
        }
    }

    /**
     * Get the address to send a notification to.
     *
     * @param mixed $notifiable
     * @return mixed
     * @throws CouldNotSendNotification
     */
    protected function getTo($notifiable)
    {
        if ($notifiable->routeNotificationFor('waba')) {
            return $notifiable->routeNotificationFor('waba');
        }
        if (isset($notifiable->celular)) {
            return $notifiable->celular;
        }
        throw CouldNotSendNotification::invalidReceiver();
    }

    /**
     * Get the alphanumeric sender.
     *
     * @param $notifiable
     * @return mixed|null
     * @throws CouldNotSendNotification
     */
    protected function canReceiveAlphanumericSender($notifiable)
    {
        return false;
    }
}