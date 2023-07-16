<?php

use App\App;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\AutoloadSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use Sicet7\Console\ConsolePlugin;
use Sicet7\Database\DatabasePlugin;
use Sicet7\Error\ErrorPlugin;
use Sicet7\Events\EventsPlugin;
use Sicet7\HTTP\HttpAttributeLoaderPlugin;
use Sicet7\HTTP\HttpPlugin;
use Sicet7\Log\LogPlugin;
use Sicet7\Plugin\AutowireAttributeLoaderPlugin;
use Sicet7\Plugin\Plugin;
use Sicet7\PSR17\PSR17Plugin;
use Sicet7\Server\ServerPlugin;

require_once dirname(__DIR__) . '/vendor/autoload.php';

//where and how should there be looked for application source code.
$sourceLocator = new AggregateSourceLocator([
    new DirectoriesSourceLocator(
        [
            __DIR__ . '/app',
        ],
        (new BetterReflection())->astLocator()
    ),
    new AutoloadSourceLocator()
]);

//which reflector should be used to reflect over the application code.
$sourceReflector = new DefaultReflector($sourceLocator);

Plugin::load(new LogPlugin());
Plugin::load(new ErrorPlugin());
Plugin::load(new EventsPlugin($sourceReflector));
Plugin::load(new DatabasePlugin());
Plugin::load(new ServerPlugin());
Plugin::load(new PSR17Plugin());
Plugin::load(new HttpPlugin());
Plugin::load(new AutowireAttributeLoaderPlugin($sourceReflector));
Plugin::load(new HttpAttributeLoaderPlugin($sourceReflector));

Plugin::load(new ConsolePlugin(
    ConsolePlugin::makeCommandMap($sourceReflector)
));

Plugin::load(new App());