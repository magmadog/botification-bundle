# botification-bundle
 Symfony bundle to control the sending of notifications to telegram/discord channels
  
## Setup
* Add the following to your ```composer.json``` file:
```composer
"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/magmadog/botification-bundle.git"
        }
    ]
```
* Get the botification installed using:
```composer
composer require magmadog/botification-bundle
```
## Usage

### Telegram
* Create telegram bot.
    *  For creating write to [BotFather](https://t.me/botfather).
    * After creation, the bot is given a generated **token**.
    * Add the bot to the group where you want to receive notifications.
    * Add [IDBot](https://t.me/myidbot) to this group and write ```/getgroupid ```to receive **group id**.
* EXAMPLE USAGE
    ```
    ...
    use magmadog\BotificationBundle\Service\TelegramBotService;
    ...
    class ...
    {
        ...
        $telegramBot = new TelegramBotService(TELEGRAM_TOKEN, GROUP_ID);
        $telegramBot->sendNotify("Hello, World!");
        ...
    }
    ```
where:
- `TELEGRAM_TOKEN` is your Telegram token
- `GROUP_ID` is your Telegram group id
### Discord
* Create webhook.
    * In your discord server `Edit Channel -> Integrations -> Webhooks -> New Webhook`
    * Configure a new webhook
    * Press `Copy Webhook URL` and enter the received address in the search bar
    * Save strings `channel_id` and `token`
* Example usage:

    ```
    ...
    use magmadog\BotificationBundle\Service\DiscordBotService;
    ...
    class ...
    {
        ...
        $discordBot = new TelegramBotService(DISCORD_TOKEN, CHANNEL_ID);
        $discordBot->sendNotify("Hello, World!");
        ...
    }
    ```

where:
- `DISCORD_TOKEN` is your Discord Webhook token
- `CHANNEL_ID` is your Discord channel id

### Asynchronous notification sending mode 
Botification built on the basis of a `symfony/notifier` and uses `ChatMessage` to send notifications.

To enable asynchronous sending, you need add to configuration file `config/packages/messanger.yaml`:

    ```
    framework:
        ...
        Symfony\Component\Notifier\Message\ChatMessage: async
    ```
