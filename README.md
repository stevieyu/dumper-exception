### hyperf

```php
// AppExceptionHandler.php
        if(method_exists($throwable, 'render')){
            return $response->withAddedHeader('content-type', 'text/html; charset=utf-8')
                ->withStatus(500)
                ->withBody(new SwooleStream((string) $throwable->render()));
        }
```

### update
```shell

```