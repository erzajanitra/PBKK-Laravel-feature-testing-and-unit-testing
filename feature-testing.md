# Feature Testing
## Pengertian
Feature test dapat digunakan untuk menguji gabungan komponen pada aplikasi yang kita buat. Test ini dilakukan untuk memastikan sistem pada aplikasi yang kita buat dapat berjalan sesuai fungsionalitasnya. Terdapat 2 cara untuk melakukan feature testing yang akan kita coba, yaitu :
1. HTTP Test </br>
   Laravel menyediakan API yang dapat digunakan untuk melakukan HTTP request kepada aplikasi yang kita miliki untuk mendapatkan HTTP response.
2. Browser Test </br>
   Browser test dilakukan terhadap sebuah aplikasi dengan menggunakan browser dan semua test case yang sudah di tulis akan dijalankan oleh Laravel Dusk secara otomatis
   

## Tutorial
### HTTP Test
Untuk melakukan HTTP Test dapat menjalankan command berikut
```
   php artisan make:test PostTest
```

Maka pada project laravel akan terdapat folder `tests` dan sub folder `Feature` dengan beberapa script di dalamnya

<img width="164" alt="image" src="https://user-images.githubusercontent.com/75319371/168455939-ce12cd21-657b-473c-9511-665af528faf7.png">

Berikut adalah script PostTest.php : [PostTest.php](https://github.com/erzajanitra/PBKK-Laravel-feature-testing-and-unit-testing/blob/824e7f4bc903182f3a8872f2a797c7c16712d9e8/laravel-testing/tests/Feature/PostTest.php)

Tambahkan beberapa fungsi di bawah ini pada script `PostTest.php` sebagai test case yang dapat digunakan untuk menguji aplikasi kita.

#### Basic Request
- Fungsi `test_welcome_status` digunakan untuk menguji ketika membuka page dengan route `/post` menggunakan method `get`. Jika berhasil membuka page tersebut maka akan mendapatkan response berupa status code 200. 
   ```
      public function test_welcome_status()
       {
           $response = $this->get('/post');

           $response->assertStatus(200);
       }
   ```
   Berikut adalah tampilan dari halaman Post
- Fungsi `test_create_status` digunakan untuk menguji ketika membuka page dengan route `/post/create` menggunakan method `get`. Page ini akan tampil apabila kita mengklik button Create Post pada halaman post. Sama seperti fungsi `test_welcome_status`, jika berhasil membuka page tersebut maka akan mendapatkan response berupa status code 200. 
   ```
      public function test_create_status()
       {
           $response = $this->get('/post/create');

           $response->assertStatus(200);
       }
   ```
   Berikut adalah tampilan dari halaman Create Post
#### Request Header
Fungsi `test_post_with_headers` digunakan untuk mengirimkan header pada HTTP request yang dibuat dengan method `withHeaders` ketika user membuka halaman Post. Dengan method `withHeaders` juga, user dapat menambahkan custom header sesuai yang diinginkan. 
```
      public function test_post_with_headers()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/post', ['name' => 'Sally']);

        $response->assertStatus(302);
    }
```
#### Cookies
Fungsi `test_interacting_with_cookies` digunakan untuk menentukan cookies value atau menyimpan cookies sebelum melakukan HTTP request. Pada fungsi ini terdapat method `withCookie` dan method `withCookies`. `withCookie` untuk menerima cookies dengan 2 argument yang terdiri dari name dan value, sedangkan `withCookies`untuk menerima cookies dalam sebuah array.
```
     public function test_interacting_with_cookies()
    {
        $response = $this->withCookie('color', 'blue')->get('/');
 
        $response = $this->withCookies([
            'color' => 'blue',
            'name' => 'Taylor',
        ])->get('/');
    }
```
#### Session/Authentication
- Session </br>
   Pada fungsi `test_interacting_with_the_session` terdapat method `withSession` yang digunakan untuk set data pada sebuah session pada array. Session tersebut digunakan untuk menjaga sebuah state dari halaman yang sedang dibuka oleh user.
   ```
         public function test_interacting_with_the_session()
       {
           $response = $this->withSession(['banned' => false])->get('/');
       }
   ```
- Authentication </br>
 Pada fungsi `test_post_requires_authentication` terdapat method `actingAs` yang digunakan untuk menjadikan user yang sedang membuka halaman Post menjadi current user sehingga dapat mengakses halaman tersebut dalam session yang sama. Untuk menguji fungsi ini perlu membuat data User menggunakan factory sehingga tergenerate.
   ```   
      public function test_post_requires_authentication()
       {
           $user = \App\Models\User::factory()->create();

           $response = $this->actingAs($user)
               ->withSession(['banned' => false])
               ->get('/post');
       }
   ```


#### Debugging Responses
Pada fungsi `test_basic_test` terdapat method `dump`, `dumpHeaders`, dan `dumpSession`yang digunakan untuk debug HTTP response dari fungsi yang sebelumnya telah diujikan.
```
   public function test_basic_test()
    {
        $response = $this->get('/');
 
        $response->dumpHeaders();
 
        $response->dumpSession();
 
        $response->dump();
    }
```
#### File Upload
Fungsi ` test_avatars_can_be_uploaded` digunakan untuk mengujikan penguploadan file pada sebuah website. Pada fungsi ini menggunakan method `fake` dari `Illuminate\Http\UploadedFile` untuk membuat dummy file atau image untuk testing dan method `fake` dari ` Illuminate\Support\Facades\Storage` untuk mempermudah testing upload file. 
```
   public function test_avatars_can_be_uploaded()
    {
        Storage::fake('avatars');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->post('/avatar', [
            'avatar' => $file,
        ]);

        Storage::disk('avatars')->assertExists($file->hashName());
    }
```

### Browser Test
Untuk Browser Test dapat menggunakan salah satu fitur testing dari laravel untuk pengujian aplikasi laravel secara otomatis, yaitu Laravel Dusk. Laravel Dusk adalah browser automation and testing API yang disediakan oleh Laravel dan tidak mengharuskan kita untuk menginstall JDK atau Selenium ke browser kita, melainkan menggunakan ChromeDriver. Namun, kita juga bebas menggunakan driver yang compatible dengan Selenium yang lain.
#### Install Laravel Dusk
Sebelum menginstall Laravel Dusk perlu menambahkan `laravel/dusk` pada Dependency Composer dengan command berikut
```
   composer require --dev laravel/dusk
```
Setelah berhasil menambahkan `laravel/dusk`, install Laravel Dusk dengan command berikut
```
   php artisan dusk:install
```

Berikut adalah script PostTest.php : [PostTest.php](https://github.com/erzajanitra/PBKK-Laravel-feature-testing-and-unit-testing/blob/824e7f4bc903182f3a8872f2a797c7c16712d9e8/laravel-testing/tests/Browser/PostTest.php)

Tambahkan beberapa fungsi di bawah ini pada script `PostTest.php` sebagai test case yang dapat digunakan untuk menguji aplikasi kita.
#### Basic Test
#### Laravel Dusk Component
#### Laravel Dusk Feature

