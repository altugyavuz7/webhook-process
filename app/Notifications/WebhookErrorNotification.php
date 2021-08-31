<?php

namespace App\Notifications;

use App\WebhookProcess;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WebhookErrorNotification extends Notification
{
    use Queueable;

    /**
     * @var \Exception
     */
    private $exception;
    /**
     * @var WebhookProcess
     */
    private $process;

    /**
     * Create a new notification instance.
     *
     * @param \Exception $exception
     * @param WebhookProcess $process
     */
    public function __construct(\Exception $exception, WebhookProcess $process)
    {
        $this->exception = $exception;
        $this->process   = $process;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    public function toSlack($notifiable)
    {
        $url = route('webhook-process-errors.index');
        
        $content = config('app.name') . " Webhook Process Error";

        if (config('webhook.slack_mention_activate') && config('webhook.slack_mention_user') != "") {

            $mentions = config('webhook.slack_mention_user');

            if (strpos($mentions, ',')) {
                $user = explode(',', $mentions);
                $mentions = "";
                foreach ($user as $item) {
                    $mentions .= "<@" . $item . ">";
                }
            } else {
                $mentions = "<@" . $mentions . ">";
            }

            $content .= "\r\n" . $mentions;
        }

        return (new SlackMessage)
            ->error()
            ->content($content)
            ->linkNames()
            ->attachment(function ($attachment) use ($url) {
                $attachment->title("Process ID:" . $this->process->id, $url)
                    ->fields([
                        'Scope'          => $this->process->scope,
                        'Bigcommerce ID' => $this->process->bigcommerce_id,
                        'Type'           => $this->process->type,
                        'Error Message'  => $this->exception->getMessage(),
                        'File'           => $this->exception->getFile(),
                        'Error Code'     => $this->exception->getCode(),
                        'Error Line'     => $this->exception->getLine()
                    ]);
            });
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
