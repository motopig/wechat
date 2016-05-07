<?php namespace Ecdo\Atlas;

use Illuminate\Support\ServiceProvider;
use Ecdo\Universe\TowerUtils;
use Ecdo\Universe\Cache\JsonCache;

class AtlasServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('atlas.star.creator', function ($app)
        {
            return new StarCreator($app['files']);
        });
        
        $this->app->bindShared('command.atlas.create', function ($app)
        {
            return new AtlasCreateCommand($app['atlas.star.creator']);
        });
        
        $this->app->bindShared('command.atlas.dump', function ($app)
        {
            return new AtlasDumpCommand();
        });
        
        $this->app->bindShared('atlas.asset.publisher', function ($app)
        {
            return new AtlasAssetPublisher($app['files']);
        });

        $this->app->bindShared('command.atlas.asset', function ($app)
        {
            return new AtlasAssetPublishCommand($app['atlas.asset.publisher']);
        });
        
        $this->app->bindShared('atlas.config.publisher', function ($app)
        {
            $path = $app['path'].'/config';
            $publisher = new AtlasConfigPublisher($app['files'], $path);
            $publisher->setPackagePath(AtlasUtils::getAtlasPath());
            
            return $publisher;
        });

        $this->app->bindShared('command.atlas.config', function ($app)
        {
            return new AtlasConfigPublishCommand($app['atlas.config.publisher']);
        });
        
        $this->commands([
            'command.atlas.create',
            'command.atlas.dump',
            'command.atlas.asset',
            'command.atlas.config'
        ]);
        
        $this->atlasAutoload();
        $this->registerAtlasProviders();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'atlas.star.creator',
            'atlas.asset.publisher',
            'atlas.config.publisher',
            'command.atlas.create',
            'command.atlas.dump',
            'command.atlas.asset',
            'command.atlas.config'
        ];
    }
    
    /**
     * 加载atlas的autoload和注册atlas config loader
     */
    protected function atlasAutoload()
    {
        AtlasUtils::atlasAutoload();
        AtlasUtils::registerConfigLoader();
    }
    
    /**
     * 注册atlas中的provider
     */
    protected function registerAtlasProviders()
    {
        $stars = array();
        $providers = AtlasUtils::scanStarsProviders($stars);
        AtlasUtils::registerAtlasProviders($providers);
    }
}
