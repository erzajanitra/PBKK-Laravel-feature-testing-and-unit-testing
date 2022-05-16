# Feature Testing
## Pengertian
Feature test dapat digunakan untuk menguji gabungan komponen pada aplikasi yang kita buat. Test ini dilakukan untuk memastikan sistem pada aplikasi yang kita buat dapat berjalan sesuai fungsionalitasnya. Terdapat 2 cara untuk melakukan feature testing yang akan kita coba, yaitu :
1. HTTP Test </br>
   Laravel menyediakan API yang dapat digunakan untuk melakukan HTTP request kepada aplikasi yang kita miliki untuk mendapatkan HTTP response.
2. Browser Test </br>
   Browser test dilakukan terhadap sebuah aplikasi dengan menggunakan browser dan semua test case yang sudah di tulis akan dijalankan oleh Laravel Dusk secara otomatis
   

## Tutorial
### HTTP Test
#### Membuat Script PostTest
Untuk melakukan HTTP Test dapat menjalankan command berikut 
```
   php artisan make:test PostTest
```

Maka pada project laravel akan terdapat folder `tests` dan sub folder `Feature` dengan beberapa script di dalamnya

<img width="200" alt="image" src="https://user-images.githubusercontent.com/75319371/168455939-ce12cd21-657b-473c-9511-665af528faf7.png">

Berikut adalah script PostTest.php : [PostTest.php](https://github.com/erzajanitra/PBKK-Laravel-feature-testing-and-unit-testing/blob/824e7f4bc903182f3a8872f2a797c7c16712d9e8/laravel-testing/tests/Feature/PostTest.php)

Kemudian, tambahkan beberapa fungsi di bawah ini pada script `PostTest.php` sebagai test case yang dapat digunakan untuk menguji aplikasi kita.

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
#### Command untuk Testing
Untuk menjalankan HTTP Test dapat menggunakan command berikut 
```
   php artisan test
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
#### Membuat Script PostTest
Untuk melakukan Browser Test dapat menjalankan command berikut 
```
   php artisan dusk:test PostTest
```

Maka pada project laravel akan terdapat folder `tests` dan sub folder `Browser` dengan beberapa sub folder dan script di dalamnya

<img width="200" alt="image" src="https://user-images.githubusercontent.com/75319371/168589042-d2e5a742-b4e0-4ba0-b3fd-77ab68d14134.png">

Berikut adalah script PostTest.php : [PostTest.php](https://github.com/erzajanitra/PBKK-Laravel-feature-testing-and-unit-testing/blob/824e7f4bc903182f3a8872f2a797c7c16712d9e8/laravel-testing/tests/Browser/PostTest.php)

Kemudian, tambahkan beberapa fungsi di bawah ini pada script `PostTest.php` sebagai test case yang dapat digunakan untuk menguji aplikasi kita.
#### Basic Test
Fungsi ` testExample` digunakan untuk menguji ketika membuka page dengan route `/post` menggunakan method `visit`. Kemudian, menggunakan method `assertSee` untuk memastikan bahwa halaman yang diinginkan telah berhasil dibuka oleh user
```
   public function testExample()
    {   
        $this->browse(function (Browser $browser) {
            $browser->visit('/post')
                ->assertSee('Post');
        });
    }
```
#### Laravel Dusk Component
Laravel Dusk memiliki fitur Component yang dapat digunakan untuk menampilkan UI dan memiliki fungsionalitas dapat di re-used selama menggunakan aplikasi tersebut, seperti navigation bar atau notification window. Untuk menggunakan Dusk Component dapat menggunakan command
```
   php artisan dusk:component DatePicker
```
Setelah menjalankan command tersebut, maka script DatePicker.php akan tertambahkan pada sub folder Component

<img width="200" alt="image" src="https://user-images.githubusercontent.com/75319371/168599199-3bb4df47-8d03-40f3-bc62-2fa5918dbaa6.png">

Berikut adalah script DatePicker.php : [DatePicker.php](https://github.com/erzajanitra/PBKK-Laravel-feature-testing-and-unit-testing/blob/7695a7ad01fb7fe084252ddb14e5dfb46cad65bb/laravel-testing/tests/Browser/Components/DatePicker.php)

Component DatePicker adalah component yang selalu dapat ditampilkan pada setiap halaman di aplikasi yang kita buat secara otomatis. Pada script DatePicker terdapat beberapa method yang akan tergenerate secara otomatis, yaitu method `selector`, `assert`, `elements`, dan `selectDate`. Kemudian, untuk menjalankan test terhadap DatePicker, kita bisa menambahkan fungsi `testBasicExample` di bawah ini pada script PostTest.php. Method `selectDate` digunakan untuk memilih tanggal yang diinginkan dengan format (tahun, bulan, tanggal). Lalu, method `assertSee` digunakan untuk memastikan bahwa bulan yang dipilih telah sesuai.
```
   public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->within(new DatePicker, function ($browser) {
                        $browser->selectDate(2022, 5, 07);
                    })
                    ->assertSee('May');
        });
    }
```
#### Laravel Dusk Page
Dusk Pages allow you to define expressive actions that may then be performed on a given page via a single method. Pages also allow you to define short-cuts to common selectors for your application or for a single page.
Laravel Dusk memiliki fitur Page yang dapat digunakan untuk memudahkan testing pada halaman yang memiliki banyak aksi dengan menggunakan single method. Selain itu, dengan Dusk Page kita dapat menggunakan short-cuts untuk redirect ke halaman lainnya. 
```
   php artisan dusk:page Login
```
Setelah menjalankan command tersebut, maka script Login.php akan tertambahkan pada sub folder Component

<img width="200" alt="image" src="https://user-images.githubusercontent.com/75319371/168607434-155e5bc4-90c6-458c-97b9-775c482af6d9.png">

Berikut adalah script DatePicker.php : [Login.php](https://github.com/erzajanitra/PBKK-Laravel-feature-testing-and-unit-testing/blob/7695a7ad01fb7fe084252ddb14e5dfb46cad65bb/laravel-testing/tests/Browser/Pages/Login.php)

Pada script Login terdapat beberapa method yang akan tergenerate secara otomatis, yaitu method `url`, `assert`, dan `elements`. Kita juga bisa menambahkan sebuah method untuk memudahkan testing Login.
```
   public function login(Browser $browser, $username, $password)
   {
       $browser->type('username', $username)
               ->type('password', $password)
               ->press('Create Playlist');
   }
```
Kemudian, untuk menjalankan test terhadap Login, kita bisa menambahkan fungsi `testLogin` di bawah ini pada PostTest.php. 
```
   public function testLogin(){
        $this->browse(function (Browser $browser) {
            $browser->visit(new Login)
            ->login('user','password')
            ->assertSee('Successfully Login');
        });
    }
```
#### Command untuk Testing
Untuk menjalankan Dusk Test dapat menggunakan command berikut 
```
   php artisan dusk
```
