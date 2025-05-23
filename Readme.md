# Laravel [Openpay](https://www.openpay.mx/) plans

Laravel [Openpay](https://www.openpay.mx/) plans es una librería de laravel para gestionar las suscripciones de openpay.

## Instalación

```bash
composer require abel-olguin/laravel-openpay-plans

php artisan vendor:publish --provider="AbelOlguin\OpenPayPlans\PlansProvider"

php artisan migrate
```

Esto generará los archivos necesarios para el funcionamiento: vistas, config, traducciones y migraciones.

De igual manera necesitas configurar tu archivo `.env` con los siguientes datos de openpay:

```
PLANS_OPEN_ID=TU_ID_DE_OPENPAY
PLANS_OPEN_API_KEY=TU_API_KEY_DE_OPENPAY
PLANS_OPEN_COUNTRY=TU_CODIGO_DE_CIUDAD_DE_OPENPAY #(MEXICO ES MX)
PLANS_OPEN_PRODUCTION=true #(Si es true se generaran cargos reales)
```
En el modelo de usuarios debes usar el trait `HasPlans`:

```php
namespace App\Models;
use AbelOlguin\OpenPayPlans\Models\Traits\HasPlans;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasPlans;
}
```

Para usar las rutas por defecto puedes usar:

```php
\AbelOlguin\OpenPayPlans\Routes\Plans::routes();
```

Si prefieres hacer tus propias rutas te recomiendo usar el trait `\AbelOlguin\OpenPayPlans\Controllers\Traits\Subscriptions` 

```php
use AbelOlguin\OpenPayPlans\Controllers\Traits\Subscriptions;

class SubscriptionController
{
    use Subscriptions;
    ...
}
```

## Middleware

Esta librería tiene dos middleware disponibles los cuales se pueden usar de la siguiente forma

```php
Route::middleware('plans:pro,trial')->get();
```
El middleware dejará pasar a cualquier usuario que tenga el plan **pro** o el plan **trial**

```php
Route::middleware('plans.active')->get();
```
El middleware dejará pasar a cualquier usuario que tenga un plan activo, sea cual sea.

## Gates

Hay tres gates disponibles los cuales se pueden usar de la siguiente forma:

```php
use Illuminate\Support\Facades\Gate;
if (!Gate::forUser($user)->allows('has-plan', 'trial')) {
  abort(403);
}
```
1. El gate responderá con un error 403 en caso de que el usuario no tenga el plan **trial**

```php
use Illuminate\Support\Facades\Gate;
if (!Gate::forUser($user)->allows('has-active-plan')) {
  abort(403);
}
```
2. El gate responderá con un error 403 en caso de que el usuario no tenga un plan activo

```php
use Illuminate\Support\Facades\Gate;
if (!Gate::forUser($user)->allows('create-plan')) {
  abort(403);
}
```
3. El gate responderá con un error 403 en caso de que el usuario no pueda crear un plan, esto se determina usando la configuracion, 
si la llave `allow_multiple_plans` es `true`, permitira que el usuario pueda seguir suscribiendose a planes si, por el contrario, es
`false` significa que el usuario solo puede suscribirse a un plan y si intenta suscribirse a otros no podra hacerlo,
de igual forma este gate válida que el usuario no se suscriba al mismo plan.

## Comandos

Hay 3 comandos disponibles:
1. Una vez que creas tus planes en base de datos deberás usar el comando: 
   ```bash 
    php artisan plans:generate
    ```
2. Para eliminar todos los planes puedes usar:
    ```bash 
    php artisan plans:delete
    ```
3. Para desactivar las suscripciones que no han sido pagadas, fueron canceladas o ya terminaron (pensado para ser usado en un schedule):
    ```bash 
    php artisan plans:check
    ```


## Contributing

Los PR son bienvenidos :)

## License

[MIT](https://choosealicense.com/licenses/mit/)
