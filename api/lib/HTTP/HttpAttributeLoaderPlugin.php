<?php

namespace Sicet7\HTTP;

use DI\Definition\Exception\InvalidDefinition;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Roave\BetterReflection\Reflection\ReflectionAttribute;
use Roave\BetterReflection\Reflector\Reflector as ReflectorInterface;
use Sicet7\Contracts\HTTP\RouteCollectorInterface;
use Sicet7\Contracts\Plugin\MutableDefinitionSourceInterface;
use Sicet7\Contracts\Plugin\PluginInterface;
use Sicet7\HTTP\Attributes\Middleware;
use Sicet7\HTTP\Attributes\Route;
use Sicet7\HTTP\Middlewares\DeferredMiddleware;

final readonly class HttpAttributeLoaderPlugin implements PluginInterface
{
    public const ROUTE_ATTRIBUTE_MAPPING_KEY = 'http.attribute.loader.plugin.route.attribute.map';

    /**
     * @param ReflectorInterface $reflector
     */
    public function __construct(
        public ReflectorInterface $reflector
    ) {
    }

    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     * @throws InvalidDefinition
     */
    public function register(MutableDefinitionSourceInterface $source): void
    {
        $routeAttributeMapping = [];

        //Loop over all the classes found by the provided reflector.
        foreach ($this->reflector->reflectAllClasses() as $class) {
            //If the given class is not a RequestHandler then skip it.
            if (!$class->implementsInterface(RequestHandlerInterface::class)) {
                continue;
            }

            $attributes = $class->getAttributes();

            //If is has no PHP Attributes then we skip it.
            if (empty($attributes)) {
                continue;
            }

            //Give me all the routes that extend or is the Route attribute
            $routeAttributes = $this->collectAttributes($attributes, static function (ReflectionAttribute $attribute): bool {
                $class = $attribute->getClass();

                return $class->getName() === Route::class ||
                    $class->isSubclassOf(Route::class) ||
                    $class->implementsInterface(Route::class);
            });

            //If no Route attributes was found then skip the class, as we then have no information
            //on how to register the routes.
            if (empty($routeAttributes)) {
                continue;
            }

            //The FQCN of the RequestHandler class that we're mounting a route for.
            $routeClassName = $class->getName();

            //Collect any middlewares that might be mounted on the RequestHandler
            $middlewareAttributes = $this->collectAttributes($attributes, static function (ReflectionAttribute $attribute): bool {
                $class = $attribute->getClass();

                return $class->getName() === Middleware::class ||
                    $class->isSubclassOf(Middleware::class) ||
                    $class->implementsInterface(MiddlewareInterface::class);
            });

            //Add instantiated attributes to the mapping.
            $routeAttributeMapping[$routeClassName] = [
                'routes' => $routeAttributes,
                'middlewares' => $middlewareAttributes,
            ];
        }

        //Register the mapping on the container
        $source->value(self::ROUTE_ATTRIBUTE_MAPPING_KEY, $routeAttributeMapping);

        //Decorate the RouteCollector with the routes from the mapping.
        $source->decorate(RouteCollectorInterface::class, function (
            RouteCollectorInterface $routeCollector,
            ContainerInterface $container
        ): RouteCollectorInterface {

            //Read the mapping from the container.
            $routeMap = $container->get(self::ROUTE_ATTRIBUTE_MAPPING_KEY);

            //Make sure the mapping is still a non-empty array.
            if (is_array($routeMap) && !empty($routeMap)) {
                foreach ($routeMap as $handlerFqcn => $attributeInfo) {
                    if (
                        !is_string($handlerFqcn) ||
                        !class_exists($handlerFqcn, true) ||
                        !is_array($attributeInfo) ||
                        !array_key_exists('routes', $attributeInfo) ||
                        empty($attributeInfo['routes'])
                    ) {
                        continue;
                    }
                    $routeAttributes = $attributeInfo['routes'];
                    $middlewareAttributes = $attributeInfo['middlewares'] ?? [];

                    foreach ($routeAttributes as $routeAttribute) {
                        if (!($routeAttribute instanceof Route)) {
                            continue;
                        }
                        $route = $routeAttribute->makeRoute($container, $handlerFqcn);
                        foreach ($middlewareAttributes as $middlewareAttribute) {
                            if ($middlewareAttribute instanceof Middleware) {
                                $route->addMiddleware(new DeferredMiddleware($container, $middlewareAttribute->class));
                            }
                            if ($middlewareAttribute instanceof MiddlewareInterface) {
                                $route->addMiddleware($middlewareAttribute);
                            }
                        }
                        $routeCollector->add($route);
                    }
                }
            }

            return $routeCollector;
        });
    }

    /**
     * @param array $attributes
     * @param callable $filterFunction
     * @return object[]
     */
    private function collectAttributes(array $attributes, callable $filterFunction): array
    {
        $filteredAttributes = array_values(array_filter($attributes, $filterFunction));
        $output = [];
        foreach ($filteredAttributes as $attribute) {
            $attributeClass = $attribute->getClass();
            $attributeClassName = $attributeClass->getName();
            $args = $attribute->getArguments();
            if (empty($args)) {
                $output[] = new $attributeClassName();
            } else {
                $output[] = new $attributeClassName(...$args);
            }
        }
        return $output;
    }
}