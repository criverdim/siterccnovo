<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Guia de Implantação e Padronização de Ambiente

- Objetivo: compilar e executar localmente com IP fixo (`http://127.0.0.1:8000`) e manter o IP externo (`https://177.10.16.6`) apenas para acesso público.
- Variáveis de ambiente:
  - `APP_URL=https://177.10.16.6` (produção)
  - `APP_URL_LOCAL=http://127.0.0.1:8000` (opcional; usado em ambientes `local`/`development`)
- Sessões e cache:
  - Se `SESSION_DRIVER=database`, garantir migrações da tabela `sessions`. Caso a tabela falte, o sistema alterna para `file` automaticamente.
  - Para `CACHE_STORE=database`, garantir a tabela `cache`. Se ausente, o sistema alterna para `array`.
- Assets e `storage`:
  - A URL do disco `public` é ajustada dinamicamente para `APP_URL`/`APP_URL_LOCAL` conforme o ambiente.

### Servidor Web (Produção)

- Nginx (exemplo):

```
server {
    listen 443 ssl;
    server_name 177.10.16.6;

    ssl_certificate /etc/ssl/certs/fullchain.pem;
    ssl_certificate_key /etc/ssl/private/privkey.pem;

    root /var/www/html/rcc-system/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_pass unix:/run/php/php-fpm.sock;
    }

    location ~* \.(png|jpg|jpeg|gif|svg|css|js)$ {
        try_files $uri =404;
        expires 30d;
        access_log off;
    }

    client_max_body_size 16m;
}
```

- Apache (exemplo com `mod_php`):

```
<VirtualHost *:443>
    ServerName 177.10.16.6
    DocumentRoot /var/www/html/rcc-system/public

    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/fullchain.pem
    SSLCertificateKeyFile /etc/ssl/private/privkey.pem

    <Directory /var/www/html/rcc-system/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

- Proxy/rede:
  - Abrir porta 443/tcp (HTTPS) e opcionalmente 80/tcp (HTTP->HTTPS redirect).
  - Se usar proxy reverso, garantir cabeçalhos `X-Forwarded-Proto`, `X-Forwarded-For` e `X-Forwarded-Host`.

### Ambiente de Desenvolvimento

- Executar servidor local com:
  - `php artisan serve --host=127.0.0.1 --port=8000`
- Configurar `.env` local:
  - `APP_ENV=local`
  - `APP_URL_LOCAL=http://127.0.0.1:8000`
  - `APP_DEBUG=true`
  - Sessão `file` e cache `array` são recomendados para evitar dependência de migrações.

### Tratamento de Erros (500)

- Logs:
  - Erros em rotas `/admin` são registrados com chave `admin.error` contendo `path`, `message` e outras informações.
- Página amigável:
  - Em exceções durante acesso ao `/admin`, o sistema exibe uma página de erro amigável com opção de tentar novamente.
- Redirecionamento:
  - Usuários não autenticados são redirecionados para `/login`; o acesso à página de login admin (`/admin/login`) é permitido sem autenticação.

### Conexões com Banco de Dados

- `.env` padrão usa MySQL local (`127.0.0.1:3306`). Validar credenciais e disponibilidade.
- Para sessões em banco: criar tabela `sessions` com `php artisan session:table && php artisan migrate`.
- Para cache em banco: criar tabela `cache` com `php artisan cache:table && php artisan migrate`.

### Testes

- Desenvolvimento (local):
  - Abrir `http://127.0.0.1:8000/admin/login` e validar login e navegação.
  - Verificar assets (`storage`) e ausência de avisos de certificado.
- Produção (externo):
  - Abrir `https://177.10.16.6/admin` e validar login.
  - Garantir que o certificado SSL é válido para o host público.
  - Verificar logs em `storage/logs/laravel.log`.

### Notas Operacionais

- Se houver erros de disco como `No space left on device`, liberar espaço em `storage/` e partições do servidor.
- Após alterações de `.env`, executar `php artisan optimize:clear`.

## Cores Institucionais (RCC)

- Paleta sólida (digital):
  - Verde principal: `#006036` (Pantone 364)
  - Verde claro digital: `#009049` (variação digital)
  - Amarelo: `#fdc800` (Pantone 7406)
  - Azul escuro: `#004058` (Pantone 548)
  - Azul claro: `#b8d0dc` (Pantone 552)
- Tons de cinza:
  - Escuro: `#585553`
  - Médio: `#6c6d70`
  - Claro: `#a6a9ab`
  - Muito claro: `#dddddc`
- Uso básico em interfaces:
  - Primário (`--cv-primary`): `#006036`
  - Ação/realce: `#fdc800`
  - Acento secundário: `#004058`
  - Superfícies suaves e bordas leves: `#b8d0dc`
  - Texto e divisores neutros: cinzas acima
- Exemplo de variáveis CSS:
  ```
  :root {
    --cv-primary: #006036;
    --cv-accent: #fdc800;
    --cv-secondary: #004058;
    --cv-soft: #b8d0dc;
    --cv-gray-700: #585553;
    --cv-gray-600: #6c6d70;
    --cv-gray-500: #a6a9ab;
    --cv-gray-300: #dddddc;
  }
  ```

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
