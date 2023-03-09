# PHP Hyperf api doc

# 介绍

> 基于 [tw2066/dto](https://github.com/tw2066/dto) [tw2066/api-docs](https://github.com/tw2066/api-docs) 框架改进而来，特别鸣谢tw2066/dto给的灵感

# 运行环境

* php >= 8.2
* hyperf >= 3.0

# 安装

```shell
composer require baoziyoo/hyperf-api-doc
php bin/hyperf.php vendor:publish baoziyoo/hyperf-api-doc
```

# 使用

## 例子

```php
use Baoziyoo\Hyperf\ApiDocs\Annotation\Api;
use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiHeader;
use Baoziyoo\Hyperf\ApiDocs\Annotation\ApiOperation;

#[Api(tags: 'demo管理')]
#[ApiHeader('token')]
#[ApiHeader('tokenType')]
class DemoController extends AbstractController
{
    #[ApiOperation('登陆')]
    public function index(#[RequestQuery] DemoQuery $request): Contact
    {
        $contact = new Contact();
        $contact->name = $request->name;
        var_dump($request);
        return $contact;
    }

    #[ApiOperation('更新token')]
    public function add(#[RequestBody] DemoBodyRequest $request, #[RequestQuery] DemoQuery $query)
    {
        var_dump($query);
        return json_encode($request, JSON_UNESCAPED_UNICODE);
    }

    public function fromData(#[RequestFormData] DemoFormData $formData): bool
    {
        $file = $this->request->file('photo');
        var_dump($file);
        var_dump($formData);
        return true;
    }
}
```

