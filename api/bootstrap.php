<?php

use App\App;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\AutoloadSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use Sicet7\Plugin\AutowireAttributeLoader;
use Sicet7\Plugin\Console;
use Sicet7\Plugin\Container\ContainerBuilder;
use Sicet7\Plugin\Events;
use Sicet7\Plugin\HTTP;
use Sicet7\Plugin\HTTPAttributeLoader;
use Sicet7\Plugin\PSR17;
use Sicet7\Plugin\Server;

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

ContainerBuilder::load(new Events($sourceReflector));
ContainerBuilder::load(new Server());
ContainerBuilder::load(new PSR17());
ContainerBuilder::load(new HTTP());
ContainerBuilder::load(new AutowireAttributeLoader($sourceReflector));
ContainerBuilder::load(new HttpAttributeLoader($sourceReflector));

ContainerBuilder::load(new Console(
    Console::makeCommandMap($sourceReflector)
));

ContainerBuilder::load(new App());