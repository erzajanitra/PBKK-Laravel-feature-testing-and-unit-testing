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
![image](https://user-images.githubusercontent.com/68325900/168513652-965c6736-f677-402f-b6a2-05c805780bea.png)
