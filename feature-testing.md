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
- Authentication
   ```   
      public function test_post_requires_authentication()
       {
           $user = \App\Models\User::factory()->create();

           $response = $this->actingAs($user)
               ->withSession(['banned' => false])
               ->get('/post');
       }
   ```
- Session 
   ```
         public function test_interacting_with_the_session()
       {
           $response = $this->withSession(['banned' => false])->get('/');
       }
   ```

#### Debugging Responses
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
