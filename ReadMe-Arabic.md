# عميل Unsplash PHP لـ Laravel (إصدار Laravel)

[![Build Status](https://travis-ci.org/unsplash/unsplash-php.svg?branch=master)](https://travis-ci.org/unsplash/unsplash-php)
[![Latest Stable Version](https://poser.pugx.org/unsplash/unsplash/v/stable)](https://packagist.org/packages/unsplash/unsplash)
[![Total Downloads](https://poser.pugx.org/unsplash/unsplash/downloads)](https://packagist.org/packages/unsplash/unsplash)
[![License](https://poser.pugx.org/unsplash/unsplash/license)](https://packagist.org/packages/unsplash/unsplash)

توفر هذه الحزمة طريقة بسيطة وأنيقة للتفاعل مع [واجهة برمجة تطبيقات Unsplash](https://unsplash.com/documentation) داخل تطبيق Laravel الخاص بك. تعتمد هذه الحزمة على عميل `unsplash/unsplash` PHP الرسمي، وتقدم ميزات خاصة بـ Laravel مثل التكوين التلقائي، وواجهة (Facade)، والتخزين المؤقت (Caching).

- [الوثائق الرسمية لواجهة برمجة تطبيقات Unsplash](https://unsplash.com/documentation)
- [إرشادات واجهة برمجة تطبيقات Unsplash](https://help.unsplash.com/en/articles/2511245-unsplash-api-guidelines) (يرجى قراءتها والالتزام بها!)
- [سجل تغييرات عميل PHP الأصلي](https://github.com/unsplash/unsplash-php/blob/master/CHANGELOG.md)

**هام:** يجب على كل تطبيق يستخدم واجهة برمجة تطبيقات Unsplash الالتزام بـ [إرشادات واجهة برمجة التطبيقات](https://help.unsplash.com/en/articles/2511245-unsplash-api-guidelines). يشمل هذا:
- [ربط الصور مباشرة (Hotlinking)](https://help.unsplash.com/en/articles/2511253-hotlinking-images) بشكل صحيح.
- [نسب الصور إلى المصورين و Unsplash](https://help.unsplash.com/en/articles/2511243-guideline-attribution).
- [تتبع حدث التنزيل (Triggering a download event)](https://help.unsplash.com/en/articles/2511258-guideline-triggering-a-download) عندما يقوم المستخدم بتنزيل صورة. (تساعد هذه الحزمة في جلب البيانات؛ يجب تنفيذ تتبع التنزيل بناءً على وظائف تطبيقك).

## الميزات الرئيسية

- تكامل سلس مع Laravel (8.x, 9.x, 10.x, 11.x).
- واجهة `Unsplash` (Facade) لسهولة استدعاءات API المشابهة للثابتة.
- تسجيل تلقائي لمزود الخدمة (Service Provider) والواجهة (Facade).
- التكوين من خلال ملف `.env` وملف تكوين قابل للنشر.
- تخزين مؤقت مدمج لاستجابات API لتحسين الأداء وتقليل استهلاك حدود معدل API.
- دوال مساعدة للمهام الشائعة، مثل جلب عنوان URL لصورة عشوائية.

## التثبيت

قم بتثبيت الحزمة عبر Composer:

```bash
composer require unsplash/unsplash
```

ستقوم الحزمة تلقائيًا بتسجيل مزود الخدمة والواجهة الخاصة بها.

## الإعداد

1.  **نشر ملف التكوين (اختياري):**
    إذا كنت بحاجة إلى تخصيص الإعدادات الافتراضية، قم بنشر ملف التكوين باستخدام:
    ```bash
    php artisan vendor:publish --provider="Unsplash\Laravel\UnsplashServiceProvider" --tag="config"
    ```
    سيؤدي هذا إلى إنشاء ملف `config/unsplash.php` في تطبيقك.

2.  **إعداد متغيرات البيئة الخاصة بك:**
    أضف ما يلي إلى ملف `.env` الخاص بك:

    ```env
    UNSPLASH_APPLICATION_ID="YOUR_UNSPLASH_ACCESS_KEY"
    UNSPLASH_UTM_SOURCE="Your Laravel App Name"
    UNSPLASH_CACHE_DURATION=60 # مدة التخزين المؤقت بالدقائق (الافتراضي: 60)
    # UNSPLASH_CACHE_STORE=null # حدد مخزنًا مؤقتًا، أو null للافتراضي (مثل 'redis', 'memcached')
    ```

    -   `UNSPLASH_APPLICATION_ID`: **مطلوب.** معرف تطبيق Unsplash الخاص بك (مفتاح الوصول). يمكنك الحصول عليه عن طريق [تسجيل تطبيقك](https://unsplash.com/oauth/applications) على Unsplash. تذكر اتباع إرشادات Unsplash لنقل تطبيقك إلى وضع الإنتاج للاستفادة من حدود معدل API الأعلى.
    -   `UNSPLASH_UTM_SOURCE`: **مطلوب.** اسم تطبيقك. يُستخدم هذا في طلبات API وروابط الإسناد وفقًا لإرشادات Unsplash.
    -   `UNSPLASH_CACHE_DURATION`: عدد الدقائق التي يجب تخزين استجابات API مؤقتًا خلالها.
    -   `UNSPLASH_CACHE_STORE`: (اختياري) حدد مخزن Laravel المؤقت المحدد لاستخدامه. إذا كانت القيمة `null` أو لم يتم تعيينها، فسيتم استخدام مخزنك المؤقت الافتراضي.

## الاستخدام الأساسي (واجهة Laravel)

توفر واجهة `Unsplash` طريقة ملائمة للوصول إلى API.

### الحصول على صورة عشوائية

-   **الحصول على كائن صورة عشوائي:**
    ```php
    use Unsplash\Laravel\Facades\Unsplash;

    $photo = Unsplash::getRandomPhoto(['query' => 'nature', 'orientation' => 'landscape']);

    if ($photo) {
        // $photo هو مثيل من Unsplash\Photo
        echo "معرف الصورة: " . $photo->id;
        echo "URL العادي: " . $photo->urls['regular'];
        // انظر "الوصول إلى بيانات الصورة" و "الإسناد" أدناه
    }
    ```

-   **الحصول على URL صورة عشوائية مباشرة:**
    ```php
    use Unsplash\Laravel\Facades\Unsplash;

    // الحصول على URL صورة بالحجم العادي
    $url = Unsplash::getRandomPhotoUrl(['query' => 'minimalist wallpaper']);
    // <img src="{{ $url }}" alt="خلفية بسيطة عشوائية">

    // الحصول على حجم معين (مثل 'small', 'thumb', 'raw', 'full')
    $smallUrl = Unsplash::getRandomPhotoUrl(['query' => 'cats'], 'small');
    ```
    تشمل أنواع URL المتاحة عمومًا `raw`، `full`، `regular`، `small`، `thumb`.

### الحصول على صورة معينة بواسطة المعرف الخاص بها

-   **الحصول على كائن صورة بواسطة معرفها:**
    ```php
    use Unsplash\Laravel\Facades\Unsplash;

    $photoId = 'some_photo_id'; // استبدل بمعرف صورة فعلي
    $photo = Unsplash::findPhoto($photoId);

    if ($photo) {
        echo "وصف الصورة: " . $photo->description;
    }
    ```

### عرض صورة في Blade

إليك مثال على كيفية عرض صورة خلفية عشوائية على صفحة الترحيب في Laravel (`resources/views/welcome.blade.php`) أو أي عرض Blade آخر:

```blade
{{-- في أعلى ملف Blade الخاص بك أو في قسم ذي صلة --}}
@php
    $unsplashUımSource = config('unsplash.utm_source', 'Your Laravel App');
    $photo = Unsplash::getRandomPhoto([
        'query' => 'beautiful landscape', // خصص المظهر الخاص بك
        'orientation' => 'landscape'
    ]);
    $bgUrl = $photo ? $photo->urls['regular'] : 'default_background.jpg'; // صورة احتياطية
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel مع خلفية Unsplash</title>
    <style>
        body {
            background-image: url('{{ $bgUrl }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            color: white; /* اضبط لون النص للرؤية */
            font-family: sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: flex-end; /* تحديد موضع الإسناد في الأسفل */
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }
        .attribution {
            background-color: rgba(0,0,0,0.5);
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.8em;
        }
        .attribution a {
            color: #eee;
            text-decoration: none;
        }
        .attribution a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    {{-- محتوى صفحتك هنا --}}
    <h1>مرحبًا بك في تطبيقي الرائع</h1>

    @if ($photo)
        <div class="attribution">
            صورة بواسطة
            <a href="{{ $photo->user['links']['html'] }}?utm_source={{ urlencode($unsplashUımSource) }}&utm_medium=referral" target="_blank">
                {{ $photo->user['name'] }}
            </a>
            على
            <a href="https://unsplash.com/?utm_source={{ urlencode($unsplashUımSource) }}&utm_medium=referral" target="_blank">
                Unsplash
            </a>
        </div>
    @endif
</body>
</html>
```

### الوصول إلى بيانات الصورة

تعيد الدالتان `getRandomPhoto()` و `findPhoto()` كائن `Unsplash\Photo` (الذي يرث من `Unsplash\Endpoint`). يمكنك الوصول إلى خصائصه مباشرة:

```php
$photo = Unsplash::getRandomPhoto();

if ($photo) {
    $id = $photo->id;
    $description = $photo->description ?? 'صورة جميلة من Unsplash.';
    $regularUrl = $photo->urls['regular'];
    $smallUrl = $photo->urls['small'];
    $rawUrl = $photo->urls['raw']; // للتنزيلات

    // تفاصيل المصور
    $photographerName = $photo->user['name'];
    $photographerUsername = $photo->user['username'];
    $photographerProfileLink = $photo->user['links']['html'];

    // كائن الروابط
    $photoHtmlLink = $photo->links['html']; // رابط الصورة على Unsplash
    $downloadLocation = $photo->links['download_location']; // مهم لتتبع التنزيلات

    // لمزيد من التفاصيل حول الخصائص المتاحة، قم بفحص كائن $photo
    // أو ارجع إلى وثائق واجهة برمجة تطبيقات Unsplash لنقطة نهاية الصورة.
}
```

### تغيير حجم الصور ديناميكيًا

يسمح Unsplash بـ [تغيير حجم الصور ومعالجتها ديناميكيًا](https://unsplash.com/documentation#dynamically-resizable-images) عن طريق إلحاق معلمات بعناوين URL للصور (مثل `w`، `h`، `fit`، `crop`، `fm`، `q`). بينما تعيد هذه الحزمة عناوين URL القياسية التي توفرها واجهة برمجة التطبيقات، يمكنك إنشاء عناوين URL المخصصة هذه بنفسك باستخدام عناوين URL الأساسية المتوفرة في خاصية `urls` لكائن `Photo`.

على سبيل المثال، للحصول على عرض مخصص وارتفاع تلقائي، بجودة وتنسيق معينين:
```php
// $photo = Unsplash::getRandomPhoto();
// if ($photo) {
//     $customUrl = $photo->urls['raw'] . '&w=600&h=400&fit=crop&fm=webp&q=75';
//     // استخدم $customUrl
// }
```
ارجع إلى [وثائق مصدر Unsplash](https://unsplash.com/documentation#source-photos) لجميع المعلمات المتاحة.


## استخدام الخدمة مباشرة

إذا كنت تفضل حقن الاعتمادية (Dependency Injection)، يمكنك تحديد نوع `Unsplash\Laravel\UnsplashService` في وحدات التحكم (Controllers) أو الفئات الأخرى:

```php
use Unsplash\Laravel\UnsplashService;
use Illuminate\Http\Request;

class MyController
{
    protected UnsplashService $unsplashService;

    public function __construct(UnsplashService $unsplashService)
    {
        $this->unsplashService = $unsplashService;
    }

    public function showRandomImage(Request $request)
    {
        $photoUrl = $this->unsplashService->getRandomPhotoUrl(['query' => 'technology']);
        return view('my_view', ['imageUrl' => $photoUrl]);
    }
}
```

## التخزين المؤقت (Caching)

يتم تخزين استجابات API لعمليات البحث عن الصور مؤقتًا تلقائيًا لتحسين الأداء والبقاء ضمن حدود معدل API.
- يتم التحكم في مدة التخزين المؤقت بواسطة متغير البيئة `UNSPLASH_CACHE_DURATION` (أو `cache_duration` في `config/unsplash.php`)، المحدد بالدقائق.
- يمكنك تحديد مخزن مؤقت باستخدام `UNSPLASH_CACHE_STORE` (أو `cache_store` في ملف التكوين). إذا لم يتم تعيينه، فسيتم استخدام مخزن Laravel المؤقت الافتراضي.

## إرشادات واجهة برمجة التطبيقات والإسناد

**من الأهمية بمكان اتباع إرشادات واجهة برمجة تطبيقات Unsplash.**

-   **الربط المباشر (Hotlinking):** قم دائمًا بربط الصور مباشرة من عناوين URL الخاصة بـ Unsplash (مثل `images.unsplash.com/...`). تستخدم هذه الحزمة عناوين URL التي توفرها واجهة برمجة التطبيقات.
-   **الإسناد:** **يجب** عليك نسب الفضل للمصور و Unsplash.
    يبدو الإسناد النموذجي كما يلي:
    > صورة بواسطة <a href="[photographer_profile_url]?utm_source=YOUR_APP_NAME&utm_medium=referral">[Photographer Name]</a> على <a href="https://unsplash.com/?utm_source=YOUR_APP_NAME&utm_medium=referral">Unsplash</a>

    استبدل `YOUR_APP_NAME` بـ `utmSource` الخاص بك (الذي تم تكوينه عبر `UNSPLASH_UTM_SOURCE`).
    يحتوي كائن `Unsplash\Photo` على معلومات المستخدم والروابط الضرورية:
    ```php
    $photo = Unsplash::getRandomPhoto();
    $appName = config('unsplash.utm_source'); // أو اسم تطبيقك الفعلي

    if ($photo) {
        $attributionHtml = sprintf(
            'صورة بواسطة <a href="%s?utm_source=%s&utm_medium=referral" target="_blank">%s</a> على <a href="https://unsplash.com/?utm_source=%s&utm_medium=referral" target="_blank">Unsplash</a>',
            $photo->user['links']['html'],
            urlencode($appName),
            $photo->user['name'],
            urlencode($appName)
        );
        // echo $attributionHtml;
    }
    ```
    انظر مثال Blade أعلاه لتنفيذ عملي.

-   **تتبع التنزيلات:** إذا كان تطبيقك يسمح للمستخدمين بتنزيل الصور (وليس مجرد عرضها)، فيجب عليك [تتبع حدث تنزيل عبر واجهة برمجة التطبيقات](https://unsplash.com/documentation#track-a-photo-download). يحتوي كائن `Photo` على عنوان URL `download_location` في خاصية `links` الخاصة به. تحتاج إلى إجراء طلب GET إلى عنوان URL هذا عندما يبدأ المستخدم عملية تنزيل.
    ```php
    // $photo = Unsplash::findPhoto('some_id');
    // if ($photo && isset($photo->links['download_location'])) {
    //     // عندما ينقر المستخدم على زر التنزيل:
    //     // قم بإجراء طلب GET إلى $photo->links['download_location']
    //     // ثم قم بتوفير $photo->urls['raw'] أو $photo->urls['full'] للمستخدم.
    //     // ملاحظة: تحتوي فئة Unsplash\Photo على دالة download() تقوم بذلك.
    //     // $downloadableUrl = $photo->download(); // هذا يستدعي نقطة نهاية download_location
    // }
    ```
    تتعامل دالة `Unsplash\Photo->download()` من المكتبة الأساسية مع هذا:
    ```php
    $photoObject = Unsplash::findPhoto('PHOTO_ID');
    if ($photoObject) {
        $actualDownloadUrl = $photoObject->download(); // هذا يتتبع نقطة نهاية التنزيل ويعيد عنوان URL الفعلي للصورة
        // يمكنك بعد ذلك إعادة توجيه المستخدم إلى $actualDownloadUrl أو عرضه للتنزيل.
    }
    ```

## الاستخدام المتقدم

تبسّط حزمة Laravel هذه بشكل أساسي جلب بيانات الصور العامة. للتفاعلات الأكثر تقدمًا مثل البحث وإدارة المجموعات والإجراءات الخاصة بالمستخدم (الإعجابات والتحميلات وما إلى ذلك)، لا يزال بإمكانك الاستفادة من القوة الكاملة لعميل `unsplash/unsplash` الأساسي.

تتوفر فئات `Unsplash\Photo` و `Unsplash\Collection` و `Unsplash\Search` و `Unsplash\User` من المكتبة الأساسية. تقوم `UnsplashService` بتهيئة `Unsplash\HttpClient` ببيانات الاعتماد الخاصة بك، لذا يمكنك استخدام هذه الفئات مباشرة إذا لزم الأمر.

مثال (بحث):
```php
use Unsplash\Search;
// تأكد من تهيئة HttpClient بواسطة UnsplashService (مما يحدث تلقائيًا إذا تم استخدام الواجهة أو حقن الخدمة)

$results = Search::photos('puppies', 1, 10, 'landscape'); // الاستعلام، الصفحة، العدد لكل صفحة، الاتجاه
foreach ($results as $photo) {
    // $photo هو مثيل Unsplash\Photo
    echo $photo->urls['small'] . "\n";
}
```

### تفويض المستخدم (OAuth)

للإجراءات التي تتطلب مصادقة المستخدم (مثل الإعجاب بصورة نيابة عن مستخدم، تحميل الصور)، ستحتاج إلى تنفيذ [تدفق Unsplash OAuth2](https://unsplash.com/documentation#user-authentication-workflow). لا توفر هذه الحزمة معالجة OAuth جاهزة، ولكن عميل `unsplash/unsplash` الأساسي لديه الأدوات اللازمة. ارجع إلى وثائقه ومثال `/examples/oauth-flow.php` في المكتبة الأصلية للحصول على إرشادات.

## المساهمة

نرحب بتقارير الأخطاء وطلبات السحب على GitHub على https://github.com/unsplash/unsplash-php. يهدف هذا المشروع إلى أن يكون مساحة آمنة ومرحبة للتعاون، ويتوقع من المساهمين الالتزام بمدونة قواعد السلوك [Contributor Covenant](http://contributor-covenant.org/).

## الترخيص

ترخيص MIT. يرجى الاطلاع على [ملف الترخيص](LICENSE) لمزيد من المعلومات.
