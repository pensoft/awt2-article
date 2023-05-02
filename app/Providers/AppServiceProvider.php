<?php

namespace App\Providers;

use App\Services\ArticleStorageService;
use App\Services\CitationStyleService;
use App\Services\CollaboratorService;
use App\Services\EventDispatcherService;
use App\Services\MicroserviceConnectorService;
use App\Services\PdfService;
use App\Transformers\CustomSerializer;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        if(env('FORCE_HTTPS')) {
            $url->forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if(env('FORCE_HTTPS')) {
            $this->app['request']->server->set('HTTPS', true);
        }

        $this->app->bind(MicroserviceConnectorService::class, function () {
            return new MicroserviceConnectorService();
        });

        $this->app->bind(EventDispatcherService::class, function () {
            return new EventDispatcherService($this->app->make(MicroserviceConnectorService::class));
        });

        $this->app->bind(CollaboratorService::class, function () {
            return new CollaboratorService($this->app->make(EventDispatcherService::class));
        });

        $this->app->bind(ArticleStorageService::class, function () {
            return new ArticleStorageService($this->app->make(MicroserviceConnectorService::class));
        });

        $this->app->bind(PdfService::class, function () {
            return new PdfService(
                $this->app->make(ArticleStorageService::class),
                $this->app->make(EventDispatcherService::class)
            );
        });

        $this->app->bind('League\Fractal\Manager', function ($app) {
            $manager = new \League\Fractal\Manager;

            // Use the serializer of your choice.
            $manager->setSerializer(new CustomSerializer);

            return $manager;
        });

        $this->app->bind(CitationStyleService::class, function () {
            $re = '/https:\/\/github.com\/(?<organization>.*)\/(?<repo>.*)/m';
            $config = $this->app['config']['app'];
            $githubConfig = $this->app['config']['github'];

            preg_match($re, $config['citation_style_repo'], $matches);

            $githubReader = app('github-reader')->read($matches['organization'], $matches['repo'], $githubConfig['default']);

            return new CitationStyleService($githubReader);
        });
    }
}
