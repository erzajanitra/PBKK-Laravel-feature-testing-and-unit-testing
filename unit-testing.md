# Testing dan Unit Testing
## Pengertian
Tesing adalah kegiatan untuk mengamati atau mengecek artifak dan kelakuan softaware dalam percobaan melalui validasi dan verifikasi. Testing dapat menyediakan penilaian objektif pada softaware kepada stakeholder atau developer tentang kualitas software yang tengah dikembangkan.

Unit Tersting adalah cara pengujian software dimana suatu uni individu pada suatu modul ditest untuk dicek apakah unit tersebut bisa berjalan dengan baik atau tidak. Dalam paradigma OOP, unit diartikan sebagai `class` atau `method`.

Pada unit testing, kita menggunakan `mock objeck` dan `method stubs` untuk membantu dalam melakukan test pada modul yang terisolasi.

## Tutorial
Pertama untuk membuat Unit Testing, dapat menjalankan command berikut
```
    php artisan make:test Post/PostControllerTest --unit
```

Maka pada project laravel akan terdapat folder `tests` dan sub folder `Unit` dengan beberapa script di dalamnya

<img width="164" alt="image" src="https://user-images.githubusercontent.com/68325900/168513652-965c6736-f677-402f-b6a2-05c805780bea.png">

Tambahkan fungsi di bawah ini pada `PostControllerTest.php` untuk menguji modul `PostController.php`.

- Fungsi `testStoreDataSuccessfullyPost` digunakan untuk menguji ketika kita ingin membuat post dengan sukses. bisa dilihat kita menggunakan object mocking untuk mengendalikan value dari object tersebut saat testing. Lalu kita ingin mengendalikan fungsi `store` dari `MySQLRepository`. Terus kita mengirim request yang akan dicek. Akhirnya kita akan mengecek apakah request post kita berhasil. Pada test ini kita menggunakan `assertStatus` untuk mengecek apakah statusnya `302` karena pada `PostController`, kita melakukan `return redirect('/post')->with('message', 'Your post has been added!');` sehingga yang kita lakukan adalah redirect yang mempunyai code `302`. Kita juga mengecek apakah route redirectnya benar atau tidak dengan menggunakan `assertRedirect `.
   ```
      public function testStoreDataSuccessfullyPost(){
            // membuat objek mocking untuk mengendalikan value dari object tersebut saat testing
            $repo = Mockery::mock(MySQLPostRepository::class);

            // control fungsi store dari mysqlpostrepository
            $repo->shouldReceive('store')->once();
            app()->instance(PostRepository::class, $repo);

            // untuk mengirim request yang akan dicek
            $response = $this->post('/post', [
                '_token' => csrf_token(),
                'title' => 'test',
                'description' => 'description'
            ]);
            // cek apakah post sudah bertambah
            $response->assertStatus(302);
            // cek apakah route redirect benar
            $response->assertRedirect('/post');
        }
   ```

- Fungsi `testStoreDataFailedPost` digunakan untuk menguji ketika ingin membuat post yang gagal. Fungsi ini mempunyai implementasi yang hampir sama tetapi dengan kita mengirimkan title kosong tanpa description ketika di PostController terdapat validasi yang memerlukan title dan description. Sehingga kita mengganti `assertRedirect` dengan '/', bukan '/post/'.
   ```
      public function testStoreDataFailedPost()
        {
            $repo = Mockery::mock(MySQLPostRepository::class);

            $repo->shouldReceive('store');
            app()->instance(PostRepository::class, $repo);
            $response = $this->post('/post', [
                'title' => ''
            ]);
            $response->assertStatus(302);
            $response->assertRedirect('/');
        }
   ```

- Fungsi `testRenderAllPostsPage` digunakan untuk mengetes fitur menampilkan semua halaman post. Kita lakukan mock seperti biasa, Tapi kali ini kita mengendalikan fungsi `getAll` agar mengembalikan array of post yang telah kita buat sebelumnya. Nantinya kita ingin memastikan apakah datanya masuk dengan menggunakan `assertSeeText` dimana ` $response->assertSeeText($posts[0]->title);`, dimana kita ingin mengecek apakah terdapat text yang memiliki value `$posts[0]->title` pada hasil render baldenya
    ```
      public function testRenderAllPostsPage()
        {
            // control fungsi getAll dari MySQLPostRepository
            $repo = Mockery::mock(MySQLPostRepository::class);
            $posts = [];
            for ($i = 0; $i < 4; $i++) {
                $post = new Post();
                $post->title = 'dasar' . $i;
                $post->description = 'description' . $i;
                $post->slug = 'dasard' . $i;
                array_push($posts, $post);
            }
            $repo->shouldReceive('getAll')->andReturn($posts);
            app()->instance(PostRepository::class, $repo);
            $response = $this->get('/post');
            $response->assertStatus(200);
            $response->assertSeeText($posts[0]->title);
        }
   ```

- Fungsi `testRenderShowOnePostPage` digunakan untuk mengetes fitur menampilkan satu halaman post. Kita lakukan mock seperti, lalu mengendalikan fungsi `findBySlug`.
    ```
      public function testRenderShowOnePostPage()
        {
            $repo = Mockery::mock(MySQLPostRepository::class);
            $post = new Post();
            $post->title = 'dasar';
            $post->description = 'description';
            $post->slug = 'dasar';
            $post->createdAt = now();
            $post->updatedAt = now();
            
            // findbySlug
            $repo->shouldReceive('findBySlug')->andReturn($post);
            app()->instance(PostRepository::class, $repo);
            $response = $this->get('/post/' . $post->slug);
            $response->assertSeeText($post->title);
        }
   ```

- Fungsi `testRenderShowEditPostPage` digunakan untuk mengetes fitur menampilkan halaman edit. Fungsi ini memiliki implementasi yang hampir sama dengan `testRenderShowOnePostPage`.
    ```
      public function testRenderShowEditPostPage()
        {
            $repo = Mockery::mock(MySQLPostRepository::class);
            $post = new Post();
            $post->title = 'dasar';
            $post->description = 'description';
            $post->slug = 'dasar';
            $post->createdAt = now();
            $post->updatedAt = now();
            $repo->shouldReceive('findBySlug')->andReturn($post);
            app()->instance(PostRepository::class, $repo);
            $response = $this->get('/post/' . $post->slug . '/edit');
            $response->assertStatus(200);
            $response->assertSeeText('Edit Post');
        }
   ```

- Fungsi `testEditDataSuccessfullyPost` digunakan untuk mengetes ketika mengedit post yang berhasil. Kita ingin mengendalikan fungsi `update` dan terpanggil sekali, yang menandakan fungsi tersebut telah dieksekusi dan proses update berhasil.
    ```
      public function testEditDataSuccessfullyPost()
        {
            $repo = Mockery::mock(MySQLPostRepository::class);

            // supaya fungsi update dipanggil sekali
            $repo->shouldReceive('update')->once();
            app()->instance(PostRepository::class, $repo);
            $response = $this->put('/post/dasar', [
                '_token' => csrf_token(),
                'title' => 'dasar',
                'description' => 'description'
            ]);
            $response->assertStatus(302);
            $response->assertRedirect('/post');
        }
   ```

- Fungsi `testEditDataFailedPost` digunakan untuk mengetes ketika mengedit post yang gagal. Fungsi ini memmiliki implementasi yang hampir sama dengan `testEditDataSuccessfullyPost`. Tapi kali kita mengirim request berupa token csrf sehingga nantinya request gagal karena tidak tervalidasi dengan baik.
    ```
      public function testEditDataFailedPost()
        {
            $repo = Mockery::mock(MySQLPostRepository::class);

            app()->instance(PostRepository::class, $repo);
            $response = $this->put('/post/dasar', [
                '_token' => csrf_token(),
            ]);
            $response->assertStatus(302);
            $response->assertRedirect('/');
        }
   ```
- Fungsi `testDeleteDataSuccessfullyPost` digunakan untuk mengetes ketika menghapus post yang berhasil. Kita membuat mock seperti biasa, dan memanggil fungsi `deleteBySlug` sekali, yang berarti fungsi tersebut berjalan dengan baik dan menghapus sebuah post
    ```
      public function testDeleteDataSuccessfullyPost()
        {
            $repo = Mockery::mock(MySQLPostRepository::class);
            // deleteBySlug
            $repo->shouldReceive('deleteBySlug')->once();
            app()->instance(PostRepository::class, $repo);
            $response = $this->delete('/post/dasar');
            $response->assertStatus(302);
            $response->assertRedirect('/post');
        }
   ```

#### Menjalankan Test
Untuk menjalankan test pada laravel dapat menggunakan command
```
    php artisan test
```

Untuk menjalankan test, spesifik pada unit test dapat menggunakan command
```
    php artisan test --testsuite=Unit
```