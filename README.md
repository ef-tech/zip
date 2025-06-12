# zip

## ローカル環境のセットアップ

### env ファイルのコピー

```shell
$ cp .env.example .env
``` 

### コンテナを起動

```shell
$ sail up
```

### マイグレーションの実行

```shell
$ sail artisan migrate
```

### 郵便番号データをローカル DB にインポート

※ バルクインサートを利用していないので、少し時間がかかります。

```shell
$ sail artisan import:postal-codes
```

### 動作確認

下記 URL にアクセスして、郵便番号の情報が取得できることを確認してください。

http://localhost/api/v1/postal-code?postal_code=1620825

## 郵便番号データへの参照先を ローカル DB から ZipCloud への切り替える（恒久対応）

ZipServiceProvider の repositories() を修正

```diff
# app/Providers/ZipServiceProvider.php
-use Zip\Infrastructure\Repositories\EloquentPostalCodeRepository;
+use Zip\Infrastructure\Repositories\ZipCloudPostalCodeRepository;

class ZipServiceProvider extends ServiceProvider
{
    public function repositories(): array
    {
        return [
-            PostalCodeRepositoryInterface::class => EloquentPostalCodeRepository::class,
+            PostalCodeRepositoryInterface::class => ZipCloudPostalCodeRepository::class,
        ];
    }
}
```

動作確認用の URL にアクセスして、郵便番号の情報が取得できることを確認してください。<br>
※ ZipCloud の API を利用しているため、API のレスポンスが変わる可能性があります。

## 郵便番号データへの参照先を一時的に ZipCloud に切り替える

```diff
# app/Http/Controllers/Api/V1/PostalCodeController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\JsonResponse;
use Zip\Application\UseCases\SearchUseCase;
+ use Zip\Domain\Repositories\PostalCodeRepository;

class PostalCodeController extends Controller
{
    public function __invoke(SearchRequest $request, SearchUseCase $useCase): JsonResponse
    {
+        // NOTE: ファクトリメソッド PostalCodeRepository::create('zipCloud') にて ZipCloudPostalCodeRepository を生成
+        //       その後、SearchUseCase のインスタンスを生成し、リクエストの postal_code を渡して処理を実行
+        //       ※ PostalCodeRepository::create('eloquent') の場合は EloquentPostalCodeRepository を生成
+        $useCase = new SearchUseCase(PostalCodeRepository::create('zipCloud'));

        return response()->json($useCase->handle($request->input('postal_code')));
    }
}
```

EloquentPostalCodeRepository と ZipCloudPostalCodeRepository は PostalCodeRepositoryInterface を実装しているため、どちらを利用しても同じように動作します。<br>
動作確認用の URL にアクセスして、郵便番号の情報が取得できることを確認してください。<br>
※ ZipCloud の API を利用しているため、API のレスポンスが変わる可能性があります。
