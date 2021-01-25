<?php
namespace sarbaev\BotificationBundle\DependencyInjection;

use Exception;
use sarbaev\BotificationBundle\Service\DiscordBotService;
use sarbaev\BotificationBundle\Service\TelegramBotService;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class BotificationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container,
        new FileLocator(__DIR__.'/../../config')
        );
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition(TelegramBotService::class);
        $definition->setArguments([
            '$telegramToken' => $config['telegram_token'],
        ]);

        $definition = $container->getDefinition(DiscordBotService::class);
        $definition->setArguments([
            '$discordToken' => $config['discord_token'],
        ]);
    }
}