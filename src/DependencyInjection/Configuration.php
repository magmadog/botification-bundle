<?php
namespace sarbaev\BotificationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('botification');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('telegram_token')
                    // ->isRequired()
                    // ->setDeprecated()
                    ->info('Telegram bot token')
                    ->defaultValue(0)
                    ->validate()
                        ->ifTrue(function ($v) { return $v <= 0; })
                        ->thenInvalid('Telegram token must be positive')
                    ->end()
                ->end()
                ->scalarNode('discord_token')
                    ->info('Discord bot token')
                    ->defaultValue(0)
                    ->validate()
                        ->ifTrue(function ($v) { return $v <= 0; })
                        ->thenInvalid('Number must be positive')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}