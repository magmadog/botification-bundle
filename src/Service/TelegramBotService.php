<?php
namespace magmadog\BotificationBundle\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\String\UnicodeString;
use Symfony\Component\Notifier\Transport\AbstractTransport;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Notifier\Message\SentMessage;

class TelegramBotService extends AbstractTransport
{
    protected const HOST = 'api.telegram.org';

    private $token;
    private $chatChannel;

    public function __construct(string $telegramToken, string $channel = null, HttpClientInterface $client = null, EventDispatcherInterface $dispatcher = null)
    {
        $this->token = $telegramToken;
        $this->chatChannel = $channel;
        $this->client = $client;

        parent::__construct($client, $dispatcher);
    }

    public function __toString(): string
    {
        if (null === $this->chatChannel) {
            return sprintf('telegram://%s', $this->getEndpoint());
        }

        return sprintf('telegram://%s?channel=%s', $this->getEndpoint(), $this->chatChannel);
    }

    public function supports(MessageInterface $message): bool
    {
        return $message instanceof ChatMessage;
    }

    protected function doSend(MessageInterface $message): SentMessage
    {
        $endpoint = sprintf('https://%s/bot%s/sendMessage', $this->getEndpoint(), $this->token);
        $options = ($opts = $message->getOptions()) ? $opts->toArray() : [];
        if (!isset($options['chat_id'])) {
            $options['chat_id'] = $message->getRecipientId() ?: $this->chatChannel;
        }

        $options['text'] = $message->getSubject();

        // if (!isset($options['parse_mode'])) {
        //     $options['parse_mode'] = TelegramOptions::PARSE_MODE_MARKDOWN_V2;
        // }

        $response = $this->client->request('POST', $endpoint, [
            'json' => array_filter($options),
        ]);

        if (200 !== $response->getStatusCode()) {
            $result = $response->toArray(false);

            throw new TransportException('Unable to post the Telegram message: '.$result['description'].sprintf(' (code %s).', $result['error_code']), $response);
        }

        $success = $response->toArray(false);

        $sentMessage = new SentMessage($message, (string) $this);
        $sentMessage->setMessageId($success['result']['message_id']);

        return $sentMessage;
    }

    public function sendNotify(string $message): SentMessage
    {
        return parent::send(new ChatMessage($message)); // TODO: Change the autogenerated stub
    }
}