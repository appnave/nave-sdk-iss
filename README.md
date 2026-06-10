# ISS SDK

Pacote Laravel privado para integrar a aplicação ao Hub de autenticação, permissões, usuários, empresas e notificações.

## Visão geral

- Consome o Hub via `Illuminate\Http\Client`.
- Registra o facade `hub` e os middlewares `hub.auth`, `hub.check` e `hub.programmatic`.
- Inclui o trait `HasCompanyLinks` para o model `User`.
- Inclui o `PermissionScope` para filtros por permissão.

## Requisitos

- PHP `^8.0` até `^8.3`
- Laravel compatível com `illuminate/contracts` `^8` até `^12`
- `spatie/laravel-permission`
- `ably/ably-php` para notificações

## Acesso a repositórios privados

Este pacote é distribuído como repositório privado via VCS. Nos projetos Laravel consumidores, adicione o repositório no `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/appnave/nave-sdk-iss"
    }
  ]
}
```

Depois, instale o pacote normalmente:

```bash
composer require appnave/nave-sdk-iss
```

Se o Composer precisar de autenticação para acessar o GitHub, configure um token localmente:

```bash
composer config -g github-oauth.github.com <SEU_TOKEN>
```

No GitHub Actions, exponha o token como secret e passe `COMPOSER_AUTH`:

```yaml
env:
  COMPOSER_AUTH: >
    {"github-oauth":{"github.com":"${{ secrets.COMPOSER_GITHUB_TOKEN }}"}}
```

## Instalação local

```bash
composer require appnave/nave-sdk-iss
php artisan hub:install
```

Esse comando publica `config/hub.php`, pode publicar as migrations do Spatie e executa `migrate`.

Variáveis mais usadas:

```dotenv
MS_HUB_BASE_URI="https://hub-server.nave.dev.br"
MS_HUB_FRONT_URI="https://hub.nave.dev.br"
MS_HUB_API_PREFIX="/api"
MS_HUB_API_VERSION="1"

HUB_PROGRAMMATIC_CLIENT=
HUB_PROGRAMMATIC_SECRET=

HUB_CLIENT_ID=
HUB_CLIENT_SECRET=
HUB_REDIRECT_URI=
HUB_SCOPE=profile

MS_HUB_DB_HOST=
MS_HUB_DB_PORT=
MS_HUB_DB_DATABASE=
MS_HUB_DB_USERNAME=
MS_HUB_DB_PASSWORD=
```

Se for usar notificações:

```dotenv
ABLY_KEY=
BROADCAST_CONNECTION=ably
```

## Comandos úteis

```bash
php artisan hub:install
php artisan hub:clean-permissions
composer check-style
composer fix-style
php artisan vendor:publish --provider="BildVitta\Hub\HubServiceProvider" --tag=hub-config
```

## Documentação da API

Não há Swagger/OpenAPI neste repositório.

As rotas publicadas pelo pacote estão em `routes/api.php`:

- `GET /api/auth/login`
- `GET /api/auth/callback`
- `GET /api/auth/logout`
- `GET /api/auth/refresh`
- `GET /api/users/me`
- `PATCH /api/users/me`
- `GET /api/users/me/edit`
- `GET /api/users/me/notifications`
- `PATCH /api/users/me/notifications`

## Convenções do projeto

- Em `config/permission.php`, use os models `\BildVitta\Hub\Entities\HubPermission` e `\BildVitta\Hub\Entities\HubRole` quando precisar alinhar o Spatie ao Hub.
- No model `User`, adicione `\BildVitta\Hub\Traits\User\HasCompanyLinks`; o trait já inclui `HasRoles`.
- Use o middleware `hub.auth` para autenticação do token e criação do usuário local quando necessário.
- Use o middleware `hub.check` nas rotas que exigem token válido.
- Use o middleware `hub.programmatic` quando a aplicação precisar obter token via client credentials.
- Para filtros por permissão em queries, use `\BildVitta\Hub\Scopes\PermissionScope`.
- Para notificações privadas, habilite o `BroadcastServiceProvider`, configure o canal `notifications.{uuid}` em `routes/channels.php` e use `Broadcast::routes(['middleware' => ['hub.check'], 'prefix' => 'api']);`.
- A fonte de verdade para `base_uri`, `front_uri`, `prefix` e credenciais está em `config/hub.php`.
